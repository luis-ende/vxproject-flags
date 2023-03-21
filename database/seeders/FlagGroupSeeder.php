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
            'color' => '#117864',
            'type' => FlagGroupType::TemperaturasCFE->value
        ]);

        FlagGroup::create([
            'nombre' => 'Temperaturas CFE 2020',
            'color' => '#943126',
            'type' => FlagGroupType::TemperaturasCFE->value
        ]);

        FlagGroup::create([
            'nombre' => 'Tipo de suelo',
            'color' => '#B9770E',
            'type' => FlagGroupType::TiposSuelo->value
        ]);
    }
}
