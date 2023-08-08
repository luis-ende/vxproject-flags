<?php

namespace Database\Seeders;

use App\Imports\VxFlagsImport;
use App\Models\FlagGroupType;
use App\Repositories\CatPaisesRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class FlagGroupTipoSueloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('database/data/imports/EMS.xlsx');
        $import = new VxFlagsImport(3, FlagGroupType::TiposSuelo, new CatPaisesRepository());

        Excel::import($import, $path);
    }
}
