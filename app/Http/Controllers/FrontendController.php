<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Category;
use App\Models\Content;
use App\Models\Descargas;
use App\Models\Download;
use App\Models\Family;
use App\Models\News;
use App\Models\Product;
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
        $destacados = Family::where('featured',1)->orderBy('order')->get();
        $marcas = Content::where('section','inicio')->first()->Block;
        $sliders = Slider::where('section','inicio')->get();
//        dd($marcas);
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
                    'title' => $item->title,
                    'text' => $item->text,
                    'order' => $item->order,
                    'ruta' => route('subfamilias',$item->slug),
                    'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
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
        $familias = Family::whereNull('padre_id',null)->orderBy('order')->get();


        return Inertia::render('Web/Product/Family', [
                'familias' => $familias->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'text' => $item->text,
                        'order' => $item->order,
                        'ruta' => route('subfamilias',$item->slug),
                        'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
                    ];
                }),
            ]);

    }

    public function subfamilias($slug = '')
    {
        $lang = app()->getLocale();
        $familia = Family::where("slug->$lang",$slug)->first();
        $familias = $familia->childFamilies;

        return Inertia::render('Web/Product/Family', [
            'familia' => $familia->only('title','id','slug'),
            'familias' => $familias->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'text' => $item->text,
                    'order' => $item->order,
                    'ruta' => route('productos',$item->slug),
                    'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
                ];
            }),
        ])->withViewData(['title' => $familia->title]);;

    }

    public function productos($slug = '')
    {
        $lang = app()->getLocale();
        $familia = Family::where("slug->$lang",$slug)->first()->padre;
        $subfamilia = Family::where("slug->$lang",$slug)->first();

        $productos = $subfamilia->productos;

        return Inertia::render('Web/Product/Family', [
            'familia' => $familia->only('title','id','slug'),
            'subfamilia' => $subfamilia->only('title','id','slug'),
            'familias' => $productos->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'text' => $item->text,
                    'order' => $item->order,
                    'ruta' => route('producto',$item->slug),
                    'image' => $item->gallery ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->gallery[0]) : '',
                ];
            }),
        ])->withViewData(['title' => $subfamilia->title]);
    }

    public function producto($slug = '')
    {
        $lang = app()->getLocale();
        $producto = Product::where("slug->$lang",$slug)->first();
        $familia = $producto->family->padre;
        $subfamilia = $producto->family;
        $galeria = collect($producto->gallery)->map(function ($item) {
            return Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item);
        });
        $productos = $producto->related;

        return Inertia::render('Web/Product/Product', [
            'familia' => $familia->only('title','id','slug'),
            'gallery' => $galeria,
            'producto' => $producto->only('file','gallery','banner','video','text_video','text','title','id','slug'),
            'subfamilia' => $subfamilia->only('title','id','slug'),
            'productos' => $productos->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'text' => $item->text,
                    'order' => $item->order,
                    'ruta' => route('producto',$item->slug),
                    'image' => $item->gallery ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->gallery[0]) : '',
                ];
            }),

        ])->withViewData(['title' => $subfamilia->title]);
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

}
