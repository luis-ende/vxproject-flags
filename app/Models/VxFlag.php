<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VxFlag extends Model
{
    use HasFactory;

    public function group(): BelongsTo
    {
        return $this->belongsTo(FlagGroup::class, 'id_flag_group');
    }

    public function attributes(): HasOne
    {
        return $this->hasOne(VxFlagAttributes::class, 'id_vx_flag', 'id');
    }
}
