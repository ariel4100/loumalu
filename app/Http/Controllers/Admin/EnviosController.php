<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Envio;
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
        $categorias = Envio::get();

        return Inertia::render('Admin/Envios', [
            'categorias' => $categorias->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->getTranslations('title'),
                    'description' => $item->getTranslations('description'),
                    'text' => $item->getTranslations('text'),
                    'section' => $item->section,
                    'order' => $item->order,
                    'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
                    'image_2' => $item->image_2 ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image_2) : '',
                    'image_3' => $item->image_3 ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image_3) : '',
                ];
            }),

        ]);

    }
    public function store(Request $request)
    {
//        dd($request->all());
        try {
            DB::beginTransaction();
            if ($request->id){
                $item = Envio::find($request->id);
            }else{
                $item = new Envio();
            }
 
            $orden = Transaction::find($request->pedido);
            dd($orden);
            if(isset($orden) == false){
                session()->flash('error', 'El numero de pedido no se encuentra registrado.');
                return Redirect::route('adm.envios.index');
            }
            $item->transaction_id = $orden->id;
            $item->client_id = $orden->client_id;
            $item->nro_pedido = $request->nro_pedido;
            $item->fecha = $request->fecha;
            $item->guia = $request->guia;
            $item->transporte = $request->transporte;
 
            $item->save();

            DB::commit();

            session()->flash('message', 'Se guardo correctamente.');
            return Redirect::route('adm.envios.index');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Se encontraron algunos errores.'.$e->getMessage());
            return Redirect::route('adm.envios.index');
        }
    }
}
