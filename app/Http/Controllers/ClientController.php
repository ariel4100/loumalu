<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductIntertrade;
use App\Models\Transaction;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\Content;

class ClientController extends Controller
{
    public $auth;

    public function __constructor(){
        $this->auth = auth()->guard('client')->user();
    }
    public function pedidos()
    {
//        dd(auth()->guard('client')->user());
        $auth = auth()->guard('client')->user();

//        $productos = Product::with('product_intertrade')->orderBy('order')->get();
        $productos = ProductIntertrade::with('product')->get();

//        dd($productos->flatten());
        return Inertia::render('Client/Pedidos', [
            'productos' => $productos->map(function ($item) {
                return [
                    'id' => $item->IdMlProducto,
                    'rubro' => $item->CodigoStProducto,
                    'producto' => $item->NombreStProducto,
                    'precio' => floatval($item->PrecioMLProducto),
                    'codigo' => $item->CodigoStProducto,
                    'marca' => $item->Marca,
                    'cantidad' => 0,
                    'unidad' => 2,
                    'image' => $item->product ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url(@$item->product->gallery[0]) : '',
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

        return Inertia::render('Client/Carrito', [

        ]);
    }

    public function estado_cuenta()
    {
//        dd(auth()->guard('client')->user());

        $pedidos = Transaction::with('order')->where('client_id',auth()->guard('client')->user()->id)->orderBy('created_at','desc')->get();
        return Inertia::render('Client/EstadoCuenta', [
            'pedidos' => $pedidos->map(function ($item) {
                return [
                    'productos' => @$item->order->first()->productos,
                    'numero' => $item->id,
                    'fecha' => $item->created_at->format('d/m/Y'),
                    'estado' => $item->status,
                    'total' => floatval($item->subtotal),
                    'total_iva' => floatval($item->total),
                ];
            }),
        ]);
    }

    public function envios()
    {
//        dd(auth()->guard('client')->user());
        $auth = auth()->guard('client')->user();

        return Inertia::render('Client/Envios', [

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
//        dd($request->total);

        try {
            DB::beginTransaction();
            $transaccion = new Transaction();
            $transaccion->total = $request->total;
            $transaccion->subtotal = $request->subtotal;
            $transaccion->subtotaliva = $request->subtotaliva;
            $transaccion->mensaje = $request->mensage;
            $transaccion->client_id = auth()->guard('client')->user()->id;
            $transaccion->save();

            $pedido = new Order();
            $pedido->productos = $request->carrito;
            $pedido->transaction_id = $transaccion->id;
            $pedido->save();


            DB::commit();

            return 1;

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Se encontraron algunos errores.'.$e->getMessage());
            return Redirect::back();
        }


    }
}
