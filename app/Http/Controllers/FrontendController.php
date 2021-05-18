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
        $destacados = Family::where('featured',1)->orderBy('order')->get();
        $destacados_productos = Product::where('featured',1)->orderBy('order')->get();
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
                    'title' => $item->title,
                    'order' => $item->order,
                    'ruta' => route('productos',$item->slug),
                    'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
                ];
            }),
            'destacados_p' => $destacados_productos->map(function ($item) {
                return [
                    'id' => $item->id,
                    'code' => $item->code,
                    'marca' => $item->marca,
                    'title' => $item->title,
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

    public function galeria()
    {
        $contenido = Content::with('block')->where('section','galeria')->first();
        $contenidoMap =  $contenido->Block->map(function ($item) {
            return [
                'title' => $item->title,
                'text' => $item->text,
                'type' => $item->type,
                'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
            ];
        });

        return Inertia::render('Web/Galeria', [
 
            'timelines' => $contenidoMap->where('type','timeline')->values(),
            'imagenes' => $contenidoMap->where('type','img')->values(),
            'bloques' => $contenidoMap->whereNull('type'),
            'contenido' => $contenido,
 

        ]);
    }
    public function asistencia()
    {
        $contenido = Content::with('block')->where('section','asistencia')->first();
        $contenidoMap =  $contenido->Block->map(function ($item) {
            return [
                'title' => $item->title,
                'text' => $item->text,
                'type' => $item->type,
                'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
            ];
        });

        return Inertia::render('Web/Asistencia', [
 
            'textos' => $contenidoMap->where('type','texto')->values(),
            'imagenes' => $contenidoMap->where('type','img')->values(),
            'bloques' => $contenidoMap->whereNull('type'),
            'contenido' => $contenido,
 

        ]);
    }

    public function familias($slug = '')
    {
        ini_set('memory_limit','-1');
        $lang = app()->getLocale();
        $familias = Family::orderBy('order')->get();


        return Inertia::render('Web/Product/Family', [
                'familias' => $familias->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'order' => $item->order,
                        'ruta' => route('productos',$item->slug),
                        'image' => $item->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->image) : '',
                    ];
                }),
            ]);

    }



    public function productos($slug = '')
    {
        ini_set('memory_limit','-1');
        $lang = app()->getLocale();
        $familia = Family::where("slug",$slug)->first();
   

       $familias = Family::with('productos')->get();
        $productos =  Product::where("family_id",$familia->id)->get();


        
        $imagen_familia = $familia->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($familia->image) : null;

        $productos->append('magic_code');
        $productos->append('check_subprod');
        /*
        $filtered = $prods->reject(function ($item) {
            return $item->check_subprod == true;
        });
        
        dd($filtered->pluck('title'));
        */
        $removes = [];
        foreach($productos as $p){
            //dd($p->check_subprod);
            
            
            if(!in_array($p->id, $removes)){

            $medidas[] = $p;

            // echo "<h5>".$p->code." - ".$p->title."</h5>";
            foreach($p->magic_code as $m){

                $removes[] = $m->id;
                // echo "<br>----".$m->code." - ".$m->title;
                
                
            }
            //   echo "<hr>";
            }
          
            
        }

        $productos = collect($medidas);
        //    dd($productos);
        return Inertia::render('Web/Product/Family', [
            'familia' => $familia->only('title','id','slug'),
            'sidenav' => 1,
            'familias' => $familias,
            'productos' => $productos->map(function ($item) use($imagen_familia){
                return [
                    'id' => $item->id,
                    'code' => $item->code,
                    'title' => $item->title,
                    'marca' => $item->marca,
                    'text' => $item->text,
                    'order' => $item->order,
                    'ruta' => route('producto',$item->slug),
                    'imagef' => $imagen_familia,
                    'image' => $item->gallery ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->gallery[0]) : '',
                ];
            }),
        ])->withViewData(['title' => $familia->title]);
    }

    public function producto($slug = '')
    {
        ini_set('memory_limit','-1');
        $lang = app()->getLocale();
        $producto = Product::with('family')->where("slug",$slug)->first();
    //    dd($producto);
        $familia = $producto->family;
        $familias = Family::with('productos')->orderBy('order')->get();

        $galeria = collect($producto->gallery)->map(function ($item) {
            return Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item);
        });
       $productos = @$producto->related ?? collect([]);


        $imagen_familia = $producto->family->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($producto->family->image) : null;

        $producto_intertrade = collect([
            'id' => $producto->id,
            'title' => $producto->title,
            'marca' => $producto->marca,
            'code' => $producto->code,
            'price' => $producto->price,
            'text' => $producto->description,
            'order' => $producto->order,
            'imagef' => $imagen_familia,

            'file' => $producto->file ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($producto->file) : null,
            'ruta' => route('producto',$producto->slug),
        ]);


               
        // $prods->append('magic_code');
        // $prods->append('check_subprod');
        /*
        $filtered = $prods->reject(function ($item) {
            return $item->check_subprod == true;
        });
        
        dd($filtered->pluck('title'));
        */
        $removes = [];

        // $productos[] = $producto;

        // echo "<h5>".$producto->code." - ".$producto->title."</h5>";
        // foreach($producto->magic_code as $m){

        //     $removes[] = $m->id;
        //     echo "<br>----".$m->code." - ".$m->title;
            
            
        // }
        //   echo "<hr>";
 

        $medidas = collect($producto->magic_code);


        return Inertia::render('Web/Product/Product', [
            'familia' => $familia->only('title','id','slug'),
            'gallery' => $galeria,
            'producto' => $producto_intertrade,
            'medidas' => $medidas,
            'familias' => $familias->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'ruta' => route('producto',$item->slug),
                    'productos' => $item->productos->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'title' => $item->title,
                            'ruta' => route('producto',$item->slug),
                        ];
                    }),
                ];
            }),
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

        ])->withViewData(['title' => $producto->nombre]);
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
        $productos->append('magic_code');
        $productos->append('check_subprod');
        /*
        $filtered = $prods->reject(function ($item) {
            return $item->check_subprod == true;
        });
        
        dd($filtered->pluck('title'));
        */
        $removes = [];
        foreach($productos as $p){
            //dd($p->check_subprod);
            
            
            if(!in_array($p->id, $removes)){

            $medidas[] = $p;

            // echo "<h5>".$p->code." - ".$p->title."</h5>";
            foreach($p->magic_code as $m){

                $removes[] = $m->id;
                // echo "<br>----".$m->code." - ".$m->title;
                
                
            }
            //   echo "<hr>";
            }
          
            
        }

        $productos = collect($medidas);

        return Inertia::render('Web/Buscador', [
            'productos' => $productos
        ]);
    }

    public function buscador_pro(Request $request)
    {
    //    dd($request->all());
//        $productos_con_familia = ProductIntertrade::get();
        $productName = $request->nombre;
        $productos = [];
        //filtra por marca y rubro
        if ($request->marca && $request->familia && $request->nombre == null){
            $productos = Product::where('marca',$request->marca)->where('family_id',$request->familia)->get();
        }
        //filtra por los 3
        if ($request->marca && $request->familia && $request->nombre){
            // $productos = Product::where('marca',$request->marca)
            //     ->where('family_id',$request->familia)
            //     ->where('title', 'like', '%'.$productName.'%')
            //     ->orwhere('code', 'like', '%'.$productName.'%')
            //     ->get();
            $productos = Product::familia($request->familia)->marca($request->marca)->where(function($query) use ($productName){
                    $query->where('title', 'LIKE', '%'.$productName.'%')
                          ->orWhere('code', 'LIKE', '%'.$productName.'%');
                })->get();
                // dd([$productos,$request->all()]);
        }
        //filtra por rubro
        if ($request->familia  && $request->marca == null && $request->nombre  == null){
            $productos = Product::where('family_id',$request->familia)->get();
        }
        //filtra por marca
        if ($request->marca && $request->familia == null && $request->nombre  == null){
            $productos = Product::where('marca',$request->marca)->get();
        }
        //filtra por nombre
        if ($request->nombre && $request->familia == null && $request->marca   == null){
            $productName = $request->nombre;
            $productos =  Product::where('title', 'like', '%'.$productName.'%')
            ->orwhere('code', 'like', '%'.$productName.'%')
            ->orwhere('marca', 'like', '%'.$productName.'%')
            ->get();
            // dd($productos);
            // $productos = Product::all()->filter(function ($item) use ($productName) {
            //     // replace stristr with your choice of matching function

            //     if (false !== stristr($item->title, $productName)){
            //         return $item;
            //     }
            //     if (false !== stristr($item->codigo, $productName)){
            //         return $item;
            //     }
                
            // });
        }
        // dd($productos);
        $productos->append('magic_code');
        $productos->append('check_subprod');
        /*
        $filtered = $prods->reject(function ($item) {
            return $item->check_subprod == true;
        });
        
        dd($filtered->pluck('title'));
        */
        $removes = [];
        foreach($productos as $p){
            //dd($p->check_subprod);
            
            
            if(!in_array($p->id, $removes)){

            $medidas[] = $p;

            // echo "<h5>".$p->code." - ".$p->title."</h5>";
            foreach($p->magic_code as $m){

                $removes[] = $m->id;
                // echo "<br>----".$m->code." - ".$m->title;
                
                
            }
            //   echo "<hr>";
            }
          
            
        }

        $productos = collect($medidas);
        

      
        return Inertia::render('Web/BuscadorPro', [
            'productos' => $productos->map(function ($item) {
                
                $imagen_familia = @$item->family->image ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->family->image) : null;

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'price' => $item->price,
                    'code' => $item->code,
                    'marca' => $item->marca,
                    'text' => $item->description,
                    'order' => $item->order,
                    'imagef' => $imagen_familia,

                    'ruta' => route('producto',$item->slug),
                    'image' => $item->file ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($item->file) :  null,
                ];
            }),
        ]);
    }
    public function presupuesto()
    {
    
        
        return Inertia::render('Web/Presupuesto', [
             
        ]);
    }
 
}
