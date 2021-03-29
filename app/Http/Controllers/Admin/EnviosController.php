<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Envio;
use App\Models\OrderIntertrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\Transaction;

class EnviosController extends Controller
{
    public function index()
    {
        $envios = Envio::get();
        // dd($envios);
        return Inertia::render('Admin/Envios', [
            'envios' => $envios

        ]);

    }
    public function store(Request $request)
    {
    //    dd($request->all());
        try {
            DB::beginTransaction();
            if ($request->id){
                $item = Envio::find($request->id);
            }else{
                $item = new Envio();
            }
 
            $orden = OrderIntertrade::find($request->pedido);
            
            if(isset($orden)){
                $item->client_id = $orden->cliente_id;
                $item->nro_pedido = $request->pedido;
                $item->fecha = $request->fecha;
                $item->guia = $request->guia;
                $item->transporte = $request->transporte;
    
                $item->save();
             }
            // dd($orden);
            // $item->transaction_id = $orden->id;
            

            DB::commit();
            if($orden == null){
                //$request->session()->flash('success', 'Task was successful!');

                session()->flash('error', 'El numero de pedido no se encuentra registrado.');
                return Redirect::route('adm.envios.index');
            }
            session()->flash('message', 'Se guardo correctamente.');
            return Redirect::route('adm.envios.index');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Se encontraron algunos errores.'.$e->getMessage());
            return Redirect::route('adm.envios.index');
        }
    }
    public function destroy($id)
    {
        $item = Envio::find($id);
        session()->flash('success', 'Se elimino correctamente.');

        $item->delete();
        return Redirect::route('adm.envios.index');
        
    }    
}
