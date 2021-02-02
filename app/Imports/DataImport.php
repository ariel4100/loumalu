<?php

namespace App\Imports;

use App\Models\Family;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class DataImport implements ToModel
{
//    use Importable;

    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        dd($row);
        return new Family([
            'title' => ['es' => $row[0]],
            'slug' => ['es' => Str::slug($row[0])]
        ]);
    }
}
