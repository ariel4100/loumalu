<?php

namespace App\Http\Controllers\Admin;

use App\Extensions\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\FamilyIntertrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class FamilyController extends Controller
{
    public function index()
    {
//        $categorias = Family::orderBy('order')->get();
        $categorias = FamilyIntertrade::get();


        return Inertia::render('Admin/Family', [
            'categorias' => $categorias->map(function ($item,$key) {
                return [
                    'id' => $item->id,
                    'title' => $item->nombre,

//                    'title' => Helpers::getTranslations($item,'title'),
//                    'text' => Helpers::getTranslations($item,'text'),
//                    'description' => $item->getTranslations('description'),
//                    'padre_id' => $item->padre_id,
//                    'video' => $item->video,
                    'featured' => $item->destacado,
                    'order' => $item->orden,
//                    'child_families' => $item->childFamilies,
                    'image' => $item->ruta ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->ruta) : '',
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
                $item = FamilyIntertrade::find($request->id);
            }else{
                $item = new FamilyIntertrade();
            }


            $file_save = Helpers::saveImage($request->file('image'), 'familias',$item->image);
            $file_save ? $item->ruta = $file_save : false;

//            $item->setTranslations('title', (array) json_decode($request->title));
//            if ($request->text){
//                $item->setTranslations('text', (array) json_decode($request->text));
//
//            }else{
//                $item->setTranslations('text', ['es' => '']);
//            }
//            $item->setTranslations('slug', collect(json_decode($request->title))->slug()->toArray());

            $item->nombre = $request->title;
            $item->slug   = str::slug($request->title);
            $item->orden   = $request->order;
            $request->featured ? $item->destacado = 1 : $item->destacado = 0;

            $item->save();

            DB::commit();

            session()->flash('message', 'Se guardo correctamente.');
            return Redirect::route('adm.familias.index');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Se encontraron algunos errores.'.$e->getMessage());
            return Redirect::route('adm.familias.index');
        }
    }

    public function destroy($id)
    {
        $item = Family::find($id);

        $item->delete();
        session()->flash('message', 'Se elimino correctamente.');
        return Redirect::route('adm.familias.index');
    }
}
