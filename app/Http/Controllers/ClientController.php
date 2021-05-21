<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Envio;
use App\Models\OrderIntertrade;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductIntertrade;
use App\Models\Pruebas;
use App\Models\Transaction;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\Content;
use App\Models\Download;

class ClientController extends Controller
{
    private $auth;

    public function __construct(){
//        $this->auth = auth()->guard('client')->user();
        $this->middleware('client');
        $this->middleware(function ($request, $next) {
            $this->auth = auth()->guard('client')->user();

            return $next($request);
        });
    }
    public function pedidos()
    {
//        dd(auth()->guard('client')->user());
        $auth = auth()->guard('client')->user();

//        $productos = Product::with('product_intertrade')->orderBy('order')->get();
        $descargas = Download::get();

    //    dd($productos );
        return Inertia::render('Client/Pedidos', [
            'descargas' => $descargas->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url(@$item->image) : '',
                    'file' => $item->file ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url(@$item->file) : '',
                ];
            }),
//            'sliders' => $sliders->map(function ($item) {
//                return [
//                    'id' => $item->id,
//                    'title' => $item->title,
//                    'text' => $item->text,
//                    'order' => $item->order,
//                    'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
//                ];
//            }),
        ]);
    }
    public function carrito()
    {
//        dd(auth()->guard('client')->user());
        $auth = auth()->guard('client')->user();
        $content = Content::where("section",'carrito')->first();
        $descuento_general = $content->data['descuento_general'] ?? 0;
        $texto = $content->data['texto'] ?? 0;
        $descuento_cliente = $auth->descuento;
        //OTRA COSA XD
//        $articulo_rubro  = Pruebas::get();
//        dd($articulo_rubro);


//        dd($descuento_cliente);
        return Inertia::render('Client/Carrito', [
            'descuento_general' => $descuento_general ?? 0,
            'descuento_cliente' => $descuento_cliente ?? 0,
            'texto' => $texto ?? 0,
        ]);
    }

    public function estado_cuenta()
    {
//        dd(auth()->guard('client')->user());

        $pedidos = OrderIntertrade::with('order')->where('cliente_id',auth()->guard('client')->user()->id)->orderBy('created_at','desc')->get();
        return Inertia::render('Client/EstadoCuenta', [
            'pedidos' => $pedidos->map(function ($item) {
                return [
                    'productos' => $item->order,
                    'numero' => $item->id,
                    'fecha' => $item->created_at->format('d/m/Y'),
                    'estado' => $item->status,
                    'total' => floatval($item->subtotal),
                    'total_iva' => floatval($item->total),
                    'mensaje' => $item->mensaje,
                ];
            }),
        ]);
    }

    public function envios()
    {
//        dd(auth()->guard('client')->user());
        $auth = auth()->guard('client')->user();
        $envios = Envio::where('client_id',$auth->id)->orderBy('id','desc')->get();
        return Inertia::render('Client/Envios', [
            'envios' => $envios
        ]);
    }

    public function reclamos()
    {
//        dd(auth()->guard('client')->user());
        $auth = auth()->guard('client')->user();

        $contenido = Content::where('section','reclamos')->first()->Block;

        return Inertia::render('Client/Reclamos', [
            'bloques' => $contenido->map(function ($item) {
                return [
                    'title' => $item->title,
                    'text' => $item->text,
                ];
            }),
        ]);
    }

    public function finalizar_compra(Request $request){
//        dd($this->auth);

        try {
            DB::beginTransaction();

            //BDD SISTEMA AGUILA
            $transaccion_inter = new OrderIntertrade();
            $transaccion_inter->total = $request->total;
            $transaccion_inter->subtotal = $request->subtotal;
            $transaccion_inter->subtotal_iva = $request->subtotaliva;
            $transaccion_inter->status = 'pendiente';
            $transaccion_inter->mensaje = $request->mensage;
            $transaccion_inter->fecha = now();
            $transaccion_inter->cliente_id =  auth()->guard('client')->user()->id;
            $transaccion_inter->save();

            //BDD LOCAL RESPALDO
            $transaccion = new Transaction();
            $transaccion->total = $request->total;
            $transaccion->subtotal = $request->subtotal;
            $transaccion->subtotaliva = $request->subtotaliva;
            $transaccion->status = 'pendiente';
            $transaccion->mensaje = $request->mensage;
            $transaccion->client_id = auth()->guard('client')->user()->id;
            $transaccion->save();

            //BDD LOCAL
            $pedido = new Order();
            $pedido->productos = $request->carrito;
            $pedido->transaction_id = $transaccion->id;
            $pedido->save();

            //BDD SISTEMA AGUILA
            foreach($request->carrito ?? [] as $value){
                OrderProduct::create([
                    'producto_codigo' => $value['codigo'],
                    'producto_precio' => $value['precio'],
                    'producto_nombre' => $value['producto'],
                    'cantidad' => $value['cantidad'] ,
                    'orden_id' => $transaccion_inter->id,
                 ]);
            }
//            $pedido_intertrade = new OrderProduct();
//            $pedido_intertrade->nroPedidoProduteca = $transaccion->id;
//            $pedido_intertrade->fechaAltaOrden = now();
//            $pedido_intertrade->producto_codigo = $transaccion->subtotal;
//             $pedido_intertrade->email = $this->auth->email;
//
//            $pedido_intertrade->save();

            DB::commit();

            return 1;

        } catch (\Exception $e) {
            DB::rollback();
//            session()->flash('error', 'Se encontraron algunos errores.'.$e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }


    }
}
