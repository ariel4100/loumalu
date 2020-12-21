<?php

namespace App\Http\Controllers\Admin;

use App\Extensions\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Family;
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
        $familias = Family::whereNull('padre_id')->orderBy('order')->get();

        $familiasMap = $familias->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'padre_id' => $item->padre_id,
            ];
        });



//        dd($categoriasconhijos);
//        $productos = Product::get();
        $productos = ProductIntertrade::with('product')->get();
//        $productos_aguila = Product::on(env('aguila'))->get();

//        dd($productos);
        return Inertia::render('Admin/Product', [
            'familias' => $familiasMap,
            'productos' => $productos->map(function ($item) {
//                dd($item->product);
                return [
                    'id' => $item->IdMlProducto,
                    'cod' => $item->CodigoStProducto,
                    'title' => $item->NombreStProducto,
                    'text' => $item->product ? Helpers::getTranslations($item->product,'text') : ['es' => ''],
                    'family_id' => $item->product ? $item->product->family_id : '',
                    'price' => floatval($item->PrecioMLProducto),
                    'stock' => $item->StockMLProducto,
                    'featured' => $item->product ? $item->product->featured : '',
                    'order' => $item->product ? $item->product->order : '',
//                    'productos' => $item->related->map(function ($item) {
//                        return [
//                            'id' => $item->id,
//                            'title' => $item->title,
//                        ];
//                    }),
                    'gallery' => collect(@$item->product->gallery ?? [])->map(function ($item) {
                        $url_image =  Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item);
//                        dd($item);
                        return $url_image;
                    }),
                    'file' => @$item->product->file ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->product->file) : '',

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
                $item_inter = ProductIntertrade::where('IdMlProducto',$request->id)->first();
                $item = Product::firstOrCreate([
                    'mlproducto_id' => $item_inter->IdMlProducto
                ]);
            }else{
                $item = new ProductIntertrade();
            }
//            dd($item);
            //modelo Intertrade
            $item_inter->CodigoStProducto   = $request->cod;
            $item_inter->NombreStProducto   = $request->title;
//            $item->PrecioMLProducto   = $request->title;
//            $item->StockMLProducto   = $request->title;
//            $item->EstadoMLProducto   = $request->title;
//            $item->idStProducto   = $request->title;
//            $item->EsKitMLProducto   = $request->title;
//            $item->Marca   = $request->title;
//            $item->NombreStClasificacionSimple   = $request->title;

            $file_save = Helpers::saveImage($request->file('archivo'), 'productos',$item->archivo);
            $file_save ? $item->file = $file_save : false;

            if (isset($request->gallery)){
                $images = Helpers::saveMultipleImage($request->gallery, 'productos');
            }

//            dd([$images]);

            $item->setTranslations('text', (array) json_decode($request->text));
            $item->setTranslations('slug', ['es'=> str::slug($request->title)]);
            $item->order   = $request->order;
            $item->gallery   = @$images;
            isset($request->featured) ? $item->featured = 1 : false;
            $item->family_id   = $request->family_id;

//            $item->slug    = str::slug($request->title);
            $item->save();
            $item_inter->save();
//            $productos = collect(json_decode($request->productos));
//
            //productos relacionados
//            if (count($productos) > 0){
//                $item->related()->sync($productos->pluck('id'));
//            }
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
