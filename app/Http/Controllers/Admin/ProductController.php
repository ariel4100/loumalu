<?php

namespace App\Http\Controllers\Admin;

use App\Extensions\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Family;
use App\Models\FamilyIntertrade;
use App\Models\Product;
use App\Models\ProductIntertrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        ini_set('memory_limit', '-1');

        $familias = Family::orderBy('order')->get();

        $familiasMap = $familias->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,

            ];
        });



//        dd($categoriasconhijos);
//        $productos_con_detalle = Product::orderBy('order')->get();
        $productos = Product::with('family')->get();
//        $productos_aguila = Product::on(env('aguila'))->get();
//        dd($productos );
        $productos_ordered = $productos->map(function ($item) {
//                dd($item->product);
            return [
                'id_inter' => $item->id,
                'id' => $item->id,
                'cod' => $item->code,
                'title' => $item->title,
                'marca' => $item->marca,
                'clasificacion' => $item->marca,
                'descripcion' => $item->description,
                'family_id' => $item->family_id,
                'name_family' => $item->family ? @$item->family->title : '',
                'price' => floatval($item->price),
                'stock' => $item->stock,
                'unidad' => $item->unit,
                'featured' => $item->featured,
                'order' => $item->order,
//                'productos' => $item->product ? $item->product->related->map(function ($value) {
//                    return [
//                        'id' => $value->id,
//                        'title' => $value->product_intertrade->nombre,
//                    ];
//                }) : [],
               'gallery' => collect(@$item->product->gallery ?? [])->map(function ($item) {
                   $url_image =  Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item);
//                        dd($item);
                   return $url_image;
               }),
                'file' => @$item->file ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->file) : '',

            ];
        });
//        dd($productos_con_detalle);
        return Inertia::render('Admin/Product', [
            'familias' => $familiasMap,
            'productos' => $productos_ordered->sortBy('order')->values()->all(),
//            'productos_detalle' => $productos_con_detalle->map(function ($value) {
//                return [
//                    'id' => $value->id,
//                    'title' => $value->nombre ?? 'otro',
//                ];
//            }),

        ]);

    }

    public function store(Request $request)
    {
    //    dd($request->all());
        try {
            DB::beginTransaction();
            if ($request->id){
                $item = Product::find($request->id) ;
//                $item = Product::firstOrCreate([
//                    'mlproducto_id' => $item_inter->id
//                ]);
            }else{
                $item = new Product();
            }
//            dd([$item,$item_inter]);
            //modelo Intertrade
            $item->title   = $request->title;
            $item->text   = $request->descripcion;
            $item->description   = $request->descripcion;
            isset($request->stock) ? $item->stock = 1 : $item->stock = 0;

//            dd('aca');
            $file_save = Helpers::saveImage($request->file('archivo'), 'productos',$item->file);
            $file_save ? $item->file = $file_save : false;

            if (isset($request->gallery)){
                $images = Helpers::saveMultipleImage($request->gallery, 'productos');
            }

//            dd([$images]);

//            $item->setTranslations('text', (array) json_decode($request->text));
//            $item->setTranslations('slug', ['es'=> str::slug($request->title)]);
            $item->order   = $request->order;
            $item->slug   = str::slug($request->title);
           $item->gallery   = @$images;
            isset($request->featured) ? $item->featured = 1 : $item->featured = 0;
            $item->family_id   = $request->family_id;

//            $item->slug    = str::slug($request->title);
//            $item->save();
            $item->save();
            $productos = collect(json_decode($request->productos));

//
//            productos relacionados
            if (count($productos) > 0){
                $item->related()->sync($productos->pluck('id'));
            }

            DB::commit();

            session()->flash('message', 'Se ha guardado correctamente.');
            return Redirect::route('adm.productos.index');

//            return response()->json([
//                'status' => 'success',
//                'message' => __('productos.store.success')
//            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => __('productos.store.error-default'),
                'errors' => [
                    $e->getMessage()
                ]
            ]);
        }
    }

    public function show($id)
    {
        try {
            $item = Product::with('industrias')->find($id);
            if ($item) {
                $new = $item->replicate();
                $new->setTranslation('title', 'es', $new->getTranslation('title', 'es'). ' - Copia');
//                $new->slug = '223322';
                $new->push();

                $new->related()->sync($item->related);
//                $new->familias()->sync($item->familias);
//                $item->familias()->sync($familias->pluck('id'));

//                dd([$item,$new->industrias->pluck('id')]);
//                $new->save();
                session()->flash('message', 'Se ha duplicado correctamente.');
                return Redirect::route('adm.productos.index');
            } else {
                session()->flash('errors', 'Ocurrio un error al guardar.');
                return Redirect::route('adm.productos.index');

            }
        } catch (\Exception $e) {
            session()->flash('errors', 'Ocurrio un error al guardar. error:'. $e->getMessage());
            return Redirect::route('adm.productos.index');
        }
    }

    public function destroy($id)
    {
        Product::find($id)->delete();
        session()->flash('message', 'Se elimino correctamente.');
        return Redirect::route('adm.productos.index');
    }
}
