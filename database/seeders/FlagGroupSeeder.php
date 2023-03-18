<?php

namespace Database\Seeders;

use App\Models\FlagGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlagGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FlagGroup::create([
            'nombre' => 'Temperaturas CFE 2008',
            'color' => 'mediumpurple'
        ]);

        FlagGroup::create([
            'nombre' => 'Temperaturas CFE 2020',
            'color' => 'red'
        ]);
    }
}
