<?php

namespace Database\Seeders;

use App\Models\PeriodType;
use App\Models\SubscriptionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Backup\Tasks\Cleanup\Period;

class SubscriptionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubscriptionType::create([
            'name' => 'Suscripción mensual',
            'description' => 'Periodo de suscripción expira en un mes.',
            'period_type' => PeriodType::Mensual->value,
        ]);

        SubscriptionType::create([
            'name' => 'Suscripción anual',
            'description' => 'Periodo de suscripción expira en un año.',
            'period_type' => PeriodType::Anual->value,
        ]);
    }
}
