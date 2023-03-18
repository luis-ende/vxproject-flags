<?php

namespace Database\Seeders;

use App\Imports\VxFlagsImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class VxFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('database/data/imports/TEMPERATURAS 2008.xlsx');
        $import = new VxFlagsImport(1);

        Excel::import($import, $path);

        $path = base_path('database/data/imports/TEMPERATURAS 2020.xlsx');
        $import = new VxFlagsImport(2);

        Excel::import($import, $path);
    }
}
