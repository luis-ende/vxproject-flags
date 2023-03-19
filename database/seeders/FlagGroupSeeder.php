<?php

namespace Database\Seeders;

use App\Models\FlagGroup;
use App\Models\FlagGroupType;
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
            'color' => 'mediumpurple',
            'tipo' => FlagGroupType::TemperaturasCFE->value
        ]);

        FlagGroup::create([
            'nombre' => 'Temperaturas CFE 2020',
            'color' => 'red',
            'tipo' => FlagGroupType::TemperaturasCFE->value
        ]);

        FlagGroup::create([
            'nombre' => 'Tipo de suelo',
            'color' => 'green',
            'tipo' => FlagGroupType::TiposSuelo->value
        ]);
    }
}
