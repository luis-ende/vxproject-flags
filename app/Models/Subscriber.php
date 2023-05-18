<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Subscriber extends Model
{
    use HasFactory;

    public static function calculateExpirationDate(int $subscriptionType, Carbon $startDate): Carbon
    {
        $periodDays = 0;
        switch ($subscriptionType) {
            case PeriodType::Mensual->value:
                $periodDays = 30;
                break;
            case PeriodType::Anual->value:
                $periodDays = 365;
                break;
        }

        return $startDate->addDays($periodDays + 1);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subscription_type_id',
        'expiration_date',
    ];
}
