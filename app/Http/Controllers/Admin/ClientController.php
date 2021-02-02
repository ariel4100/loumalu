<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientIntertrade;
use App\Models\Monitoreo;
use App\Models\OrderIntertrade;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index()
    {
        $items = Client::all();
        $items_inter = ClientIntertrade::all();

        return Inertia::render('Admin/Clientes', [
            'items' => $items->map(function ($item) use($items_inter) {
                $inter = $items_inter->where('email',$item->email)->first();
//                dd($inter);
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'email' => $item->email,
                    'username' => $item->username,
                    'fecha_nac' => $inter->fecha_nacimiento,
                    'domicilio' => $inter->domicilio,
                    'dni' => $inter->dni,
                    'descuento' => $inter->descuento,
                    'codigo_postal' => $inter->codigo_postal,
                    'ciudad' => $inter->ciudad,
                    'telefono' => $inter->telefono,
                    'apellido' => $inter->apellido,
                ];
            }),

        ]);

    }
    public function monitoreo()
    {
        $items = Monitoreo::all();

//        $grouped = $items->groupBy('client_id');
//        $items->duplicates('client_id');

//        dd($items->unique('client_id'));

        return Inertia::render('Admin/Monitoreo', [
            'items' => $items ? $items->unique('client_id')->map(function ($item) {
                if ($item->client){
                    return [
                        'fecha' => $item->created_at->format('d/m/y'),
                        'cliente' => $item->client->username,
                        'ip' => $item->ip,
                        'entradas' => Monitoreo::where('client_id',$item->client_id)->get()->count(),
                        'pedidos' => OrderIntertrade::where('cliente_id',$item->client_id)->get()->count(),
                    ];
                }
            })->filter()->values() : [],

        ]);

    }

    public function store(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
        try {
            DB::beginTransaction();
            if ($request->id){
                $item = Client::find($request->id);
                $cleinte_inter = ClientIntertrade::where('email',$item->email)->first();
            }else{
                $cleinte_inter = new ClientIntertrade();
                $item = new Client();
            }

            //BDD DE AGUILA
            $cleinte_inter->nombre = $request['name'];
            $cleinte_inter->apellido = $request['apellido'];
            $cleinte_inter->email = $request['email'];
            $cleinte_inter->usuario = $request['username'];
            $cleinte_inter->descuento = $request['descuento'];
            $cleinte_inter->fecha_nacimiento = $request['fecha_nac'];
            $cleinte_inter->domicilio = $request['domicilio'];
            $cleinte_inter->codigo_postal = $request['cp'];
            $cleinte_inter->ciudad = $request['ciudad'];
            $cleinte_inter->telefono = $request['telefono'];
            $cleinte_inter->dni = $request['dni'];
            $cleinte_inter->save();

            //BDD LOCAL
            $item->name   = $request->name;
            $item->username   = $request->username;
            $item->email   = $request->email;
            $item->descuento   = $request->descuento;

            if ($request->password)
            {
                $item->password = Hash::make($request->password);
            }

//            $item->slug    = str::slug($request->title);
            $item->save();

            DB::commit();

            session()->flash('message', 'Se ha guardado correctamente.');

            return Redirect::route('adm.clientes.index');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('errors', 'Fallo en el sistema.');

            return Redirect::route('adm.clientes.index');
//            return response()->json([
//                'status' => 'error',
//                'message' => __('category.store.error-default'),
//                'errors' => [
//                    $e->getMessage()
//                ]
//            ]);
        }
    }

    public function destroy($id)
    {
        Client::find($id)->delete();
        session()->flash('message', 'Se ha eliminado correctamente.');
        return Redirect::route('adm.clientes.index');
    }
}
