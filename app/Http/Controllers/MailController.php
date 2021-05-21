<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class MailController extends Controller
{
    public function contacto(Request $request)
    {
//        session()->flash('success', 'Correo enviado correctamente.');
//
//        return Redirect::route('contacto');

//        if($request->attached){
//            foreach ($request->attached as $key => $value) {
//                if(is_string($value)) {
////                    return dd($value);
//                    $archivo[$key]['image'] = $value;
////                    $gallery[$key]['title'] = $value['title'] ?? '';
//                } else {
////                    return dd($value);
////                    dd($value['image']);
//                    $path = $value->storeAs('uploads/cotizacion',$value->getClientOriginalName());
//                    $archivo[$key]['image'] = $path;
//                }
//            }
//        }

        $file = $request->attached ?? 0;
        $data['data'] = json_decode($request->datos,true);
        $subject = env('APP_NAME').' - Mensaje de Contacto de la Pagina Web';
//        dd($request->all());

    //    $for = ['soporte@osole.es'];
        $for = ['iventas@loumalu.com.ar'];

        Mail::send('mail.contacto', $data, function($msj) use($subject,$for,$file){
            $msj->from(env('MAIL_FROM_ADDRESS'),env('APP_NAME'));
            $msj->replyTo($for);
            $msj->subject($subject);
            $msj->to($for);

        });
        if (count(Mail::failures()) > 0)
            return Redirect::route('contacto')->withErrors(['error' => "Ha ocurrido un error al enviar el correo"]);
        else
            return Redirect::route('contacto')->with(['success' => "Correo enviado correctamente"]);
    }

    public function presupuesto(Request $request)
    {

        $file = $request->attached ?? 0;
        $data['data'] = json_decode($request->datos,true);
        $subject = env('APP_NAME').' - Mensaje de Presupuesto de la Pagina Web';
//        dd($request->all());

    //    $for = ['soporte@osole.es'];
    $for = ['iventas@loumalu.com.ar'];

        Mail::send('mail.presupuesto', $data, function($msj) use($subject,$for,$file){
            $msj->from("iventas@loumalu.com.ar",env('APP_NAME'));
            $msj->replyTo($for);
            $msj->subject($subject);
            $msj->to($for);

            if($file) {
                $msj->attach($file->getRealPath(), array(
                        'as' => $file->getClientOriginalName(), // If you want you can chnage original name to custom name
                        'mime' => $file->getMimeType())
                );
            }
        });
        if (count(Mail::failures()) > 0)
            return Redirect::route('presupuesto')->withErrors(['error' => "Ha ocurrido un error al enviar el correo"]);
        else
            return Redirect::route('presupuesto')->with(['success' => "Correo enviado correctamente"]);
    }

    public function reclamos(Request $request)
    {

//        dd($request->all());
        $file = $request->foto ?? 0;

        $data['data'] = json_decode($request->datos,true);
        $data['items'] = json_decode($request->items,true);
        $subject = env('APP_NAME').' - Mensaje de Reclamos de la Pagina Web';
//        dd($request->all());

        $for = ['soporte@osole.es'];
//        $for = ['emona@emona.com.ar'];
        Mail::send('mail.reclamos', $data, function($msj) use($subject,$for,$file ){
            $msj->from(env('MAIL_FROM_ADDRESS'),env('APP_NAME'));
            $msj->replyTo($for);
            $msj->subject($subject);
            $msj->to($for);

            if($file) {
                $msj->attach($file->getRealPath(), array(
                        'as' => $file->getClientOriginalName(), // If you want you can chnage original name to custom name
                        'mime' => $file->getMimeType())
                );
            }
        });
        if (count(Mail::failures()) > 0)
            return Redirect::route('privada.reclamos')->withErrors(['error' => "Ha ocurrido un error al enviar el correo"]);
        else
            return Redirect::route('privada.reclamos')->with(['success' => "Correo enviado correctamente"]);
    }

    public function suscriptor(Request $request)
    {

        $request->validate([
            'email' => 'required|unique:newsletters|max:255',
        ]);
//        session()->flash('success', 'Gracias por suscribirte.');

        $item = new Newsletter();
        $item->email = $request->email;
        $item->save();
        if ($item){
            return response()->json([
                'status' => 'success',
                'message' => 'Se ha suscripto',
            ]);
        }

    }
}
