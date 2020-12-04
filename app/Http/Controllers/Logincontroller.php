<?php

namespace App\Http\Controllers;

use App\Extensions\Helpers;
use App\Models\Client;
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
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255','unique:clients'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients'],
            'password' => ['required', 'string', 'min:3'],

        ], [
            'email.unique' => trans('validation.unique', [ 'attribute' => 'Email']),
            'username.unique' => trans('validation.unique', [ 'attribute' => 'Usuario']),
        ]);

        $user = Client::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        if ($user){
            $credentials = $request->only('username', 'password');

            if (Auth::guard('client')->attempt($credentials)) {
                // Authentication passed...
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
