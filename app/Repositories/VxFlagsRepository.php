<?php

namespace App\Repositories;

use App\Models\FlagGroup;
use App\Models\VxFlagAttributes;

class VxFlagsRepository
{
    public function getFlagsGroups(): array
    {
        return FlagGroup::all()->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'name' => $item->nombre,
                    'description' => $item->descripcion,
                    'color' => $item->color,
                    'flags' => $item->flags->map(function($flag) {
                        return [
                            'id' => $flag->id,
                            'latitude' => $flag->latitude,
                            'longitude' => $flag->longitude,
                            'description' => $flag->description,
                        ];
                    })->toArray()
                ];
        })->toArray();
    }

    public function getVxFlagAttributes(int $vxFlagId) {
        return VxFlagAttributes::where('id_vx_flag', $vxFlagId)
                                    ->value('attributes');
    }
}