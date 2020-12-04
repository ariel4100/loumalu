<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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


//        dd($mis_archivos);
        return Inertia::render('Client/Pedidos', [
//
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

        return Inertia::render('Client/EstadoCuenta', [

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
}
