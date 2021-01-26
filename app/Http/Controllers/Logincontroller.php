<?php

namespace App\Http\Controllers;

use App\Extensions\Helpers;
use App\Models\Client;
use App\Models\ClientIntertrade;
use App\Models\Monitoreo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class Logincontroller extends Controller
{
    public function login(Request $request)
    {
        try {
            //        return dd($request->all());
            $credentials = $request->only('username', 'password');
//        Auth::guard('client')->attempt($credentials);
//
//        return dd(Auth::guard('client')->attempt($credentials));
            if (Auth::guard('client')->attempt($credentials)) {
                $session = new Monitoreo();
                $session->ip = $request->ip();
                $session->client_id = Auth::guard('client')->user()->id;
                $session->save();
                // Authentication passed...
                return response()->json([
                    'success'
                ]);
            }else{
                return response()->json([
                    'errors' => 1,
                    'message' => __('auth.failed'),
                ]);
            }
         } catch (\Exception $e) {
//            session()->flash('error', 'Se encontraron algunos errores.'.$e->getMessage());
            return response()->json([
                'errors' => 1,
                'message' => $e->getMessage(),
            ]);

        }

    }
    public function register(Request $request)
    {

//        return dd($request->all());

        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255','unique:clients'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients'],
            'password' => ['required', 'string', 'min:3'],

        ], [
            'email.unique' => trans('validation.unique', [ 'attribute' => 'Email']),
            'username.unique' => trans('validation.unique', [ 'attribute' => 'Usuario']),
        ]);


        //BDD DE AGUILA
        $cleinte_inter = new ClientIntertrade();
        $cleinte_inter->nombre = $request['nombre'];
        $cleinte_inter->apellido = $request['apellido'];
        $cleinte_inter->email = $request['email'];
        $cleinte_inter->fecha_nacimiento = $request['fecha_nac'];
        $cleinte_inter->domicilio = $request['domicilio'];
        $cleinte_inter->codigo_postal = $request['cp'];
        $cleinte_inter->ciudad = $request['ciudad'];
        $cleinte_inter->telefono = $request['telefono'];
        $cleinte_inter->dni = $request['dni'];
        $cleinte_inter->save();

        $user = Client::create([
            'name' => $request['nombre'],
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        if ($user){
            $credentials = $request->only('username', 'password');

            if (Auth::guard('client')->attempt($credentials)) {
                // Authentication passed...
                $session = new Monitoreo();
                $session->ip = $request->ip();
                $session->client_id = Auth::guard('client')->user()->id;
                $session->save();
                return response()->json([
                    'success'
                ]);
            }
        }else{
            return response()->json([
                'errors' => 1,
                'message' => ['error' => __('auth.failed')],
            ]);
        }


    }
}
