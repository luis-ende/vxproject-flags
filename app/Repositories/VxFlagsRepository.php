<?php

namespace App\Repositories;

use App\Models\FlagGroup;

class VxFlagsRepository
{
    public function getFlagsGroups(): array
    {
        return FlagGroup::all()->map(function($item) {
                return [
                    'id' => $item->id,
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
}