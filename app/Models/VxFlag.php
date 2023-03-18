<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VxFlag extends Model
{
    use HasFactory;

    public function group(): BelongsTo
    {
        return $this->belongsTo(FlagGroup::class, 'id_flag_group');
    }
}
