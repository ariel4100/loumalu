<?php

namespace App\Http\Controllers;

use App\Imports\DataImport;
use App\Models\Family;
use App\Models\FamilyIntertrade;
use App\Models\ProductIntertrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;


class ImportController extends Controller
{
    public function import()
    {
        $reader = new ReaderCsv();
        $spreadsheet = $reader->load('rubrosarticulos.csv');
        $sheet = $spreadsheet->getActiveSheet();
//        dd($sheet->toArray());
//        (new DataImport)->import('rubros.csv', null, \Maatwebsite\Excel\Excel::CSV);
//        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//        DB::table('products')->truncate();
//        DB::connection('aguila')->table('productos')->truncate();

        foreach($sheet->toArray() as $k => $row)
        {
            if ($k == 0)
                continue;
//            dd($row);
//            $familias = new Family();
//            $familias->id = intval($row[0]);
//            $familias->title = ['es' => $row[1]];
//            $familias->slug = ['es' =>  Str::slug($row[1])];
//            $familias->order = $row[4];
//            $familias->save();

//            $item = new FamilyIntertrade();
//            $item->id = intval($row[0]);
//            $item->nombre = $row[1];
//            $item->slug   = Str::slug($row[1]);
//            $item->orden   = $row[4];
//            $item->save();

//            $items = ProductIntertrade::all();
//            foreach ($items as $pro){
//                if ($pro->marca_id == intval($row[0])){
//                    $pro->fill(['marca'=> $row[1]]);
//                    $pro->save();
//                }
////                $pro->where('marca_id',$row[0]);
////                dd($pro->where('marca_id',$row[0])->first());
//            }
            $items = ProductIntertrade::all();
            foreach ($items as $pro){
                if ($pro->codigo ==  $row[2]){
//                    dd($pro);
                    $pro->fill(['categoria_id'=> intval($row[1])]);
                    $pro->save();
                }
//                $pro->where('marca_id',$row[0]);
//                dd($pro->where('marca_id',$row[0])->first());
            }
//            $item_inter = new ProductIntertrade();
//            $item_inter->codigo   = $row[0];
//            $item_inter->nombre   = $row[2];
//            $item_inter->precio   = floatval($row[5]);
//            $item_inter->descripcion   = $row[2];
//            $item_inter->stock = 1;
//            $item_inter->marca   = $row[1];
//            $item_inter->marca_id   = $row[1];
//            $item_inter->unidad   = $row[6];
//            $item_inter->save();
        }
        dd(ProductIntertrade::all());
        return Family::all();
    }
}
