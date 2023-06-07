<?php

namespace App\Repositories;

use App\Models\FlagGroup;
use App\Models\FlagGroupType;
use App\Models\FlagsImportacion;
use App\Models\VxFlag;
use App\Models\VxFlagAttributes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class VxFlagsRepository
{
    public function getFlagsGroups(bool $withFlags = true): array
    {
        return FlagGroup::orderBy('id')
                           ->get()
                           ->map(function($flagGroup) use($withFlags) {
                               $group = [
                                   'id' => $flagGroup->id,
                                   'type' => $flagGroup->type,
                                   'name' => $flagGroup->nombre,
                                   'color' => $flagGroup->color,
                               ];

                               if ($withFlags) {
                                   $group['flags'] =
                                       $this->getVxFlagsByGroup($flagGroup);
                               } else {
                                   $group['flags'] = [];
                               }

                               return $group;
                           })->toArray();
    }

    public function getVxFlagsByGroupId(int $groupId, array $regiones = null): array
    {
        $query = VxFlag::select('id', 'latitude', 'longitude', 'description', 'region')
                ->where('id_flag_group', $groupId);

        if ($regiones) {
            $query = $query->whereIn('region', $regiones);
        }

        return $query->get()
                     ->toArray();

//        $flagGroup = FlagGroup::findOrFail($groupId);
//
//        return $this->getVxFlagsByGroup($flagGroup);
    }

    public function getVxFlagsByGroup(FlagGroup $flagGroup): array
    {
        return $flagGroup->flags->map(function($flag) {
                return [
                    'id' => $flag->id,
                    'latitude' => $flag->latitude,
                    'longitude' => $flag->longitude,
                    'description' => $flag->description,
                    'region' => $flag->region,
                ];
            })->toArray();
    }

    public function getVxFlagAttributes(int $vxFlagId) {
        return VxFlagAttributes::where('id_vx_flag', $vxFlagId)
                                    ->value('attributes');
    }

    public function getFlagsImportInfo(int $flagGroupId)
    {
        return FlagsImportacion::where('id_flag_group', $flagGroupId)
                                    ->latest()->first();
    }
}