<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $pedidos = Transaction::with('order')->get();

        return Inertia::render('Admin/Pedidos', [

            'pedidos' => $pedidos->map(function ($item) {
                return [
                    'productos' => @$item->order->first()->productos,
                    'numero' => $item->id,
                    'id' => $item->id,
                    'fecha' => $item->created_at->format('d/m/Y'),
                    'cliente' => $item->client->name,
                    'estado' => $item->status,
                    'total' => floatval($item->subtotal),
                    'total_iva' => floatval($item->total),
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
                $item = Transaction::find($request->id);
            }

            $item->status = $request->estado;
            $item->save();

            DB::commit();

            return Redirect::route('adm.pedidos.index');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Se encontraron algunos errores.'.$e->getMessage());
            return Redirect::route('adm.pedidos.index');
        }
    }

    public function destroy($id)
    {
        $item = Transaction::find($id);

        $item->delete();
        session()->flash('message', 'Se elimino correctamente.');
        return Redirect::route('adm.pedidos.index');
    }
}
