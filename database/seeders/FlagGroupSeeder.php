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
            'type' => FlagGroupType::TiposSuelo->value,
            'config' => '{
                    "sites_colors": {
                        "A-I": "#28B463",
                        "A-II": "#229954",
                        "A-III": "#138D75",
                        "B-I": "#2C86C1",
                        "B-II": "#2471A3",
                        "B-III": "#2E4053",
                        "C-I": "#D4AC0D",
                        "C-II": "#D68910",
                        "C-III": "#9b582b",
                        "D-I": "#F5B7B1",
                        "D-II": "#CB4335",
                        "D-III": "#A93226"
                    }
               }',
        ]);
    }
}
