<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Category;
use App\Models\Content;
use App\Models\Descargas;
use App\Models\Download;
use App\Models\Family;
use App\Models\FamilyIntertrade;
use App\Models\News;
use App\Models\Product;
use App\Models\ProductIntertrade;
use App\Models\Slider;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FrontendController extends Controller
{
    public function home()
    {
        $destacados = FamilyIntertrade::where('destacado',1)->orderBy('orden')->get();
        $destacados_productos = ProductIntertrade::where('destacado',1)->orderBy('orden')->get();
        $marcas = Content::where('section','inicio')->first()->Block;
        $sliders = Slider::where('section','inicio')->get();
//        dd($destacados_productos);
        //$novedades = News::orderBy('order')->limit(3)->get();
        return Inertia::render('Web/Home', [
            'sliders' => $sliders->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'text' => $item->text,
                    'order' => $item->order,
                    'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
                ];
            }),
            'destacados' => $destacados->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->nombre,
                    'order' => $item->orden,
                    'ruta' => route('productos',$item->slug),
                    'image' => $item->ruta ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->ruta) : '',
                ];
            }),
            'destacados_p' => $destacados_productos->map(function ($item) {
                return [
                    'id' => $item->id,
                    'code' => $item->codigo,
                    'marca' => $item->marca,
                    'title' => $item->nombre,
                    'slug' => $item->slug,
                    'ruta' => route('producto',@$item->slug),
                    'image' => $item->gallery ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->gallery[0]) : '',
                ];
            }),
            'marcas' => $marcas->map(function ($item) {
                return [
                    'title' => $item->title,
                    'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
                ];
            }),
        ]);
    }
    public function empresa()
    {
        $sliders = Slider::where('section','empresa')->get();
        $contenido = Content::with('block')->where('section','empresa')->first();
        $contenidoMap =  $contenido->Block->map(function ($item) {
            return [
                'title' => $item->title,
                'text' => $item->text,
                'type' => $item->type,
                'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
            ];
        });

        return Inertia::render('Web/Empresa', [
            'sliders' => $sliders->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'text' => $item->text,
                    'order' => $item->order,
                    'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
                ];
            }),
            'timelines' => $contenidoMap->where('type','timeline')->values(),
            'imagenes' => $contenidoMap->where('type','img')->values(),
            'bloques' => $contenidoMap->whereNull('type'),
            'contenido' => $contenido,

        ]);
    }

    public function descargas()
    {
        $sliders = Slider::where('section','descargas')->get();
        $descargas = Download::orderBy('order')->get();
        $contenido = Content::with('block')->where('section','descargas')->first();
        $contenidoMap =  $contenido->Block->map(function ($item) {
            return [
                'title' => $item->title,
                'text' => $item->text,
                'type' => $item->type,
                'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
            ];
        });

        return Inertia::render('Web/Descargas', [
            'sliders' => $sliders->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'text' => $item->text,
                    'order' => $item->order,
                    'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
                ];
            }),
            'timelines' => $contenidoMap->where('type','timeline')->values(),
            'imagenes' => $contenidoMap->where('type','img')->values(),
            'bloques' => $contenidoMap->whereNull('type'),
            'contenido' => $contenido,
            'descargas' => $descargas->map(function ($item) {
                return [
                    'title' => $item->title,
                    'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
                    'file' => $item->file ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->file) : '',
                ];
            }),

        ]);
    }

    public function familias($slug = '')
    {
        $lang = app()->getLocale();
        $familias = FamilyIntertrade::orderBy('orden')->get();


        return Inertia::render('Web/Product/Family', [
                'familias' => $familias->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->nombre,
                        'order' => $item->orden,
                        'ruta' => route('productos',$item->slug),
                        'image' => $item->ruta ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->ruta) : '',
                    ];
                }),
            ]);

    }



    public function productos($slug = '')
    {
        $lang = app()->getLocale();
        $familia = FamilyIntertrade::where("slug",$slug)->first();
        $familias = FamilyIntertrade::orderBy('orden')->get();

//        $productos = $familia->productos;
        $productos =  ProductIntertrade::where("categoria_id",$familia->id)->get();;


//        dd($productos);
        return Inertia::render('Web/Product/Family', [
            'familia' => $familia->only('nombre','id','slug'),
            'sidenav' => 1,
            'familias' => $familias->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->nombre,
                    'ruta' => route('producto',$item->slug),
                    'productos' => $item->productos->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'title' => $item->nombre,
                            'ruta' => route('producto',$item->slug),
                        ];
                    }),                ];
            }),
            'productos' => $productos->map(function ($item) {
                return [
                    'id' => $item->id,
                    'code' => $item->codigo,
                    'title' => $item->nombre,
                    'marca' => $item->marca,
                    'text' => $item->text,
                    'order' => $item->orden,
                    'ruta' => route('producto',$item->slug),
                    'image' => $item->gallery ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->gallery[0]) : '',
                ];
            }),
        ])->withViewData(['title' => $familia->title]);
    }

    public function producto($slug = '')
    {

        $lang = app()->getLocale();
        $producto = ProductIntertrade::with('family')->where("slug",$slug)->first();
//        dd($producto);
        $familia = $producto->family;
        $familias = FamilyIntertrade::with('productos')->orderBy('orden')->get();

        $galeria = collect($producto->gallery)->map(function ($item) {
            return Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item);
        });
//        $productos = @$producto->related ?? collect([]);

        $producto_intertrade = collect([
            'id' => $producto->id,
            'title' => $producto->nombre,
            'marca' => $producto->marca,
            'code' => $producto->codigo,
            'price' => $producto->precio,
            'text' => $producto->descripcion,
            'order' => $producto->orden,
            'file' => $producto->archivo ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($producto->archivo) : '',
            'ruta' => route('producto',$producto->slug),
        ]);

        return Inertia::render('Web/Product/Product', [
            'familia' => $familia->only('nombre','id','slug'),
            'gallery' => $galeria,
            'producto' => $producto_intertrade,
            'familias' => $familias->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->nombre,
                    'ruta' => route('producto',$item->slug),
                    'productos' => $item->productos->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'title' => $item->nombre,
                            'ruta' => route('producto',$item->slug),
                        ];
                    }),
                ];
            }),
//            'productos' => $productos->map(function ($item) {
//
//                return [
//                    'id' => $item->id,
//                    'title' => $item->nombre,
//                    'price' => $item->precio,
//                    'code' => $item->codigo,
//                    'marca' => $item->marca,
//                    'text' => $item->desccripcion,
//                    'order' => $item->orden,
//                    'ruta' => route('producto',$item->slug),
//                    'image' => $item->gallery ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->gallery[0]) : '',
//                ];
//            }),

        ])->withViewData(['title' => $producto->nombre]);
    }

    public function novedades($slug = '')
    {
        if ($slug){
            $lang = app()->getLocale();
            $category = Category::where("slug->$lang",$slug)->first();
            $novedades = $category->news;

        }else{
            $novedades = News::orderBy('order')->get();

        }
        $categorias = Category::where('section','blog')->orderBy('order')->get();

        return Inertia::render('Web/Novedades', [
            'novedades' => $novedades->map(function ($item){
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'text' => $item->text,
                    'categoria' => $item->category->title ?? 'Otro',
                    'category_id' => $item->category_id,
                    'slug' => $item->slug,
                    'ruta' => route('novedad',$item->slug),
                    'date' => $item->created_at->format('d-m-Y'),
                    'image' => $item->galley ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->galley[0]) : '',
                ];
            }),
            'categorias' => $categorias->map(function ($item){
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'category_news' => route('novedades',$item->slug),
                    'news_count' => $item->news->count(),
                ];
            }),

        ])->withViewData(['title' => @$category->title ?? 'BLOG']);
    }

    public function novedad($slug)
    {
//        dd(app()->getLocale());
        $lang = app()->getLocale();
        $news = News::where("slug->$lang",$slug)->first();

        $sliders = collect($news->galley)->map(function ($item) {
            return [
                'image' => $item ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item) : '',
            ];
        });
//        dd($slug);
        $categoria = $news->category;
        $categorias = Category::where('section','blog')->orderBy('order')->get();
        return Inertia::render('Web/Novedad', [
            'categorias' => $categorias->map(function ($item){
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'category_news' => route('novedades',$item->slug),
                    'news_count' => $item->news->count(),
                ];
            }),
            'novedad' => $news->only('id', 'title', 'text', 'image','date'),
            'categoria' => $categoria->only('title'),
            'sliders' => $sliders,
        ])->withViewData(['title' => $news->title]);
    }

    public function contacto()
    {
        $sliders = Slider::where('section','contacto')->get();
        $contenido = Content::with('block')->where('section','contacto')->first();

        return Inertia::render('Web/Contacto', [
            'sliders' => $sliders->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'text' => $item->text,
                    'order' => $item->order,
                    'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
                ];
            }),
            'contenido' => $contenido->only('title'),
        ]);
    }

    public function buscador(Request $request)
    {

        if ($request->name){
            $productos = Product::where('title', 'LIKE', "%$request->name%")
                ->orWhere('text', 'LIKE',"%$request->name%")
                ->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'text' => $item->description,
                        'order' => $item->order,
                        'ruta' => route('producto',$item->slug),
                        'image' => $item->gallery ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->gallery[0]) : '',
                    ];
                });
        }else{
            $productos = [];
        }


        return Inertia::render('Web/Buscador', [
            'productos' => $productos
        ]);
    }

    public function buscador_pro(Request $request)
    {
//        dd($request->all());
//        $productos_con_familia = ProductIntertrade::get();

        $productos = [];
        //filtra por marca y rubro
        if ($request->marca && $request->familia && $request->nombre == null){
            $productos = ProductIntertrade::where('marca',$request->marca)->where('categoria_id',$request->familia)->get();
        }
        //filtra por los 3
        if ($request->marca && $request->familia && $request->nombre){
            $productos = ProductIntertrade::where('marca',$request->marca)
                ->where('categoria_id',$request->familia)

                ->get();
        }
        //filtra por rubro
        if ($request->familia  && $request->marca == null && $request->nombre  == null){
            $productos = ProductIntertrade::where('categoria_id',$request->familia)->get();
        }
        //filtra por marca
        if ($request->marca && $request->familia == null && $request->nombre  == null){
            $productos = ProductIntertrade::where('marca',$request->marca)->get();
        }
        //filtra por nombre
        if ($request->nombre && $request->familia == null && $request->marca   == null){
            $productName = $request->nombre;
            $productos = ProductIntertrade::all()->filter(function ($item) use ($productName) {
                // replace stristr with your choice of matching function

                if (false !== stristr($item->nombre, $productName)){
                    return $item;
                }
            });
        }

//        dd($productos);
        return Inertia::render('Web/BuscadorPro', [
            'productos' => $productos->map(function ($item) {

                return [
                    'id' => $item->id,
                    'title' => $item->nombre,
                    'price' => $item->precio,
                    'code' => $item->codigo,
                    'marca' => $item->marca,
                    'text' => $item->desccripcion,
                    'order' => $item->orden,
                    'ruta' => route('producto',$item->slug),
                    'image' => $item->gallery ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->gallery[0]) : '',
                ];
            }),
        ]);
    }
}
