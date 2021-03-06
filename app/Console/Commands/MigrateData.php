<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductIntertrade;
use Illuminate\Console\Command;
use App\Models\Family;
use App\Models\FamilyIntertrade;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'intertrade:md';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'INTERTRADE Migration data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        DB::table('families')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('products')->truncate();

        $starttime = microtime(true);
        Artisan::call('down');
       $this->info('- Consultando Family');
       $families =  FamilyIntertrade::all();
       $counter = 0;
       foreach ($families as $family) {
           $item = Family::firstOrNew([
               'id' => $family->id
           ]);
           $item->title = str_replace("Productos-","",$family->nombre);
           $item->order = $family->orden;
           $item->image = $family->ruta;
           $item->featured = $family->destacado;
           $item->slug = Str::slug($family->nombre);
           $item->save();
           $counter++;
       }
       $this->info('-- Family Guardados: '.$counter);

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->info('- Consultando Productos');
        $productos =  ProductIntertrade::all();
        $counter = 0;
        foreach ($productos as $pro) {
            $item = Product::firstOrNew([
                'id' => $pro->id
            ]);
            $item->code = $pro->codigo;
            $item->title = $pro->nombre;
            $item->short_description = $pro->descripcion;
            $item->description = $pro->descripcion;
            $item->text = $pro->descripcion;
            $item->price = floatval($pro->precio);
            $item->family_id = $pro->categoria_id;
            $item->marca = $pro->marca;
            $item->stock = $pro->stock;
            $item->unit = $pro->unidad;
            $item->slug = Str::slug($pro->nombre);
            $item->save();
            $counter++;
        }
        $this->info('-- Productos Guardados: '.$counter);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Calculo de Tiempo
        $endtime = microtime(true);

        $duration = $endtime-$starttime;
        $hours = (int)($duration/60/60);
        $minutes = (int)($duration/60)-$hours*60;
        $seconds = (int)$duration-$hours*60*60-$minutes*60;

        $this->info("---Tiempo Transcurrido: ${hours}h ${minutes}m ${seconds}s");

        Artisan::call('up');
    }
}
