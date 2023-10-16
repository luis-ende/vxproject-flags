<?php

namespace Database\Seeders;

use App\Imports\VxFlagsImport;
use App\Models\FlagGroupType;
use App\Repositories\CatPaisesRepository;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class VxFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catPaisesRepo = new CatPaisesRepository();

        $path = base_path('database/data/imports/TEMPERATURAS_2008.xlsx');
        $import = new VxFlagsImport(1, FlagGroupType::TemperaturasCFE, $catPaisesRepo);

        Excel::import($import, $path);

        $path = base_path('database/data/imports/TEMPERATURAS_2020.xlsx');
        $import = new VxFlagsImport(2, FlagGroupType::TemperaturasCFE, $catPaisesRepo);

        Excel::import($import, $path);
    }
}
