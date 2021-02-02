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
        $familias = Family::orderBy('order')->get();

        $familiasMap = $familias->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->nombre,

            ];
        });



//        dd($categoriasconhijos);
        $productos_con_detalle = Product::orderBy('order')->get();
        $productos = Product::get();
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
                'productos' => $item->product ? $item->product->related->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'title' => $value->product_intertrade->nombre,
                    ];
                }) : [],
//                'gallery' => collect(@$item->product->gallery ?? [])->map(function ($item) {
//                    $url_image =  Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item);
////                        dd($item);
//                    return $url_image;
//                }),
                'file' => @$item->file ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->file) : '',

            ];
        });
//        dd($productos_con_detalle);
        return Inertia::render('Admin/Product', [
            'familias' => $familiasMap,
            'productos' => $productos_ordered->sortBy('order')->values()->all(),
            'productos_detalle' => $productos_con_detalle->map(function ($value) {
                return [
                    'id' => $value->id,
                    'title' => $value->nombre ?? 'otro',
                ];
            }),

        ]);

    }

    public function store(Request $request)
    {
//        dd($request->all());
        try {
            DB::beginTransaction();
            if ($request->id_inter){
                $item_inter = Product::find($request->id_inter) ;
//                $item = Product::firstOrCreate([
//                    'mlproducto_id' => $item_inter->id
//                ]);
            }else{
                $item_inter = new Product();
            }
//            dd([$item,$item_inter]);
            //modelo Intertrade
            $item_inter->codigo   = $request->cod;
            $item_inter->nombre   = $request->title;
            $item_inter->precio   = floatval($request->price);
            $item_inter->descripcion   = $request->descripcion;
            isset($request->stock) ? $item_inter->stock = 1 : $item_inter->stock = 0;
//            $item->EstadoMLProducto   = $request->title;
//            $item->idStProducto   = $request->title;
//            $item->EsKitMLProducto   = $request->title;
            $item_inter->marca   = $request->marca;
            $item_inter->unidad   = $request->unidad;
//            $item_inter->NombreStClasificacionSimple   = $request->clasificacion;

//            dd('aca');
            $file_save = Helpers::saveImage($request->file('archivo'), 'productos',$item_inter->archivo);
            $file_save ? $item_inter->archivo = $file_save : false;

            if (isset($request->gallery)){
                $images = Helpers::saveMultipleImage($request->gallery, 'productos');
            }

//            dd([$images]);

//            $item->setTranslations('text', (array) json_decode($request->text));
//            $item->setTranslations('slug', ['es'=> str::slug($request->title)]);
            $item_inter->orden   = $request->order;
            $item_inter->slug   = str::slug($request->title);
//            $item->gallery   = @$images;
            isset($request->featured) ? $item_inter->destacado = 1 : $item_inter->destacado = 0;
            $item_inter->categoria_id   = $request->family_id;

//            $item->slug    = str::slug($request->title);
//            $item->save();
            $item_inter->save();
            $productos = collect(json_decode($request->productos));

//
//            productos relacionados
            if (count($productos) > 0){
                $item_inter->related()->sync($productos->pluck('id'));
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
