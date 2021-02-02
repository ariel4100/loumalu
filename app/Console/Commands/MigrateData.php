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
        $starttime = microtime(true);
        Artisan::call('down');
//        $this->info('- Consultando Family');
//        $families =  FamilyIntertrade::all();
//        $counter = 0;
//        foreach ($families as $family) {
//            $item = Family::firstOrNew([
//                'id' => $family->id
//            ]);
//            $item->title = $family->nombre;
//            $item->order = $family->orden;
//            $item->image = $family->ruta;
//            $item->featured = $family->destacado;
//            $item->slug = $family->slug;
//            $item->save();
//            $counter++;
//        }
//        $this->info('-- Family Guardados: '.$counter);

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->info('- Consultando Productos');
        $families =  ProductIntertrade::all();
        $counter = 0;
        foreach ($families as $family) {
            $item = Product::firstOrNew([
                'id' => $family->id
            ]);
            $item->code = $family->codigo;
            $item->title = $family->nombre;
            $item->short_description = $family->descripcion;
            $item->description = $family->descripcion;
            $item->text = $family->descripcion;
            $item->price = floatval($family->precio);
            // $item->family_id = $family->categoria_id;
            $item->marca = $family->marca;
            $item->stock = $family->stock;
            $item->unit = $family->unidad;
            $item->slug = Str::slug($family->nombre);
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
