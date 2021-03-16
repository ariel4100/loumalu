<?php

namespace App\Providers;

use App\Models\Content;
use App\Models\Family;
use App\Models\FamilyIntertrade;
use App\Models\Meta;
use App\Models\Product;
use App\Models\ProductIntertrade;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if(request()->is('adm*')){

//            return 'adm.layouts.app';
            Inertia::setRootView('adm.layouts.app');
        } else {
            Inertia::setRootView('app');
//            return 'app';
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (php_sapi_name() != 'cli') {
            Collection::macro('slug', function () {
                return $this->map(function ($value) {
                    return Str::slug($value);
                });
            });
            $metas =  Meta::get();
            $contacto = Content::where('section', 'contacto')->first();
            // $marcas = Product::get()->pluck('marca')->unique();
            // $familias = Family::select('id','title')->get();

            Inertia::share([
                'appUrl' => config('app.url'),
                // 'familias_global' => $familias,
                // 'marcas_global' => $marcas,
                'favicon' => @$contacto->image[0]['image'] ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($contacto->image[0]['image']) : '',
                'header' => @$contacto->image[1]['image'] ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($contacto->image[1]['image']) : '',
                'footer' => @$contacto->image[2]['image'] ? Storage::disk(env('DEFAULT_STORAGE_DISK'))->url($contacto->image[2]['image']) : '',
                'redes' => @$contacto->data['redes'],
                'direcciones' => @$contacto->data['address'],
                'emails' => @$contacto->data['emails'],
                'telefonos' => @$contacto->data['phones'],
                'idioma' => LaravelLocalization::getSupportedLocales(),
                'flash' => function () {
                    return [
                        'success' => Session::get('success'),
                        'message' => Session::get('message'),
                        'error' => Session::get('error'),
                    ];
                },
                'auth' => function () {
                    return [
                        'user' => auth()->guard('client')->user() ? [
                            'id' => auth()->guard('client')->user()->id,
                            'name' => auth()->guard('client')->user()->name,
                            'username' => auth()->guard('client')->user()->username,
                            'email' => auth()->guard('client')->user()->email,
                        ] : null,
                        'check' => auth()->guard('client')->check()
                    ];
                },

            ]);


            view()->share([
                'favicon' => @$contacto->image[0]['image'],
                'header' => @$contacto->image[1]['image'],
                'metas' => @$metas
            ]);
        }
    }
}
