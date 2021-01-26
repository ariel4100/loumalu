<?php

namespace App\Http\Controllers;

use App\Models\OrderIntertrade;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $pedidos = OrderIntertrade::with('order')->orderBy('created_at','desc')->get();

        return Inertia::render('Admin/Pedidos', [

            'pedidos' => $pedidos->map(function ($item) {
                if ($item->client){
                    return [
                        'productos' => @$item->order,
                        'numero' => $item->id,
                        'id' => $item->id,
                        'fecha' => $item->created_at->format('d/m/Y'),
                        'cliente' => $item->client ? $item->client->name : 'Este cliente fue eliminado',
                        'estado' => $item->status,
                        'mensaje' => $item->mensaje,
                        'total' => floatval($item->subtotal),
                        'total_iva' => floatval($item->total),
                    ];
                }
            })->filter()->values(),

        ]);
    }

    public function store(Request $request)
    {
//        dd($request->all());
        try {
            DB::beginTransaction();
            if ($request->id){
                $item = OrderIntertrade::find($request->id);
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
