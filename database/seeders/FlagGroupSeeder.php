<?php

namespace Database\Seeders;

use App\Models\FlagGroup;
use App\Models\FlagGroupType;
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
            'color' => '#FE8100',
            'type' => FlagGroupType::TemperaturasCFE->value
        ]);

        FlagGroup::create([
            'nombre' => 'Temperaturas CFE 2020',
            'color' => '#00FF03',
            'type' => FlagGroupType::TemperaturasCFE->value
        ]);

        FlagGroup::create([
            'nombre' => 'Tipo de suelo',
            'color' => '#0380FE',
            'type' => FlagGroupType::TiposSuelo->value
        ]);
    }
}
