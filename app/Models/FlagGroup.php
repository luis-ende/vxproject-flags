<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlagGroup extends Model
{
    use HasFactory;

    public function flags(): HasMany
    {
        return $this->hasMany(VxFlag::class, 'id_flag_group', 'id');
    }
}
