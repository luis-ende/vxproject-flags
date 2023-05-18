<?php

namespace App\Repositories;

use App\Models\FlagGroup;
use App\Models\FlagGroupType;
use App\Models\VxFlagAttributes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class VxFlagsRepository
{
    protected Collection $flagsRegiones;

    public function getFlagsGroups(bool $withFlags = true): array
    {
        if ($withFlags) {
            $this->loadFlagsRegiones();
        }

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
                               }

                               return $group;
                           })->toArray();
    }

    public function getVxFlagsByGroupId(int $groupId): array
    {
        $flagGroup = FlagGroup::findOrFail($groupId);
        if ($flagGroup->type === FlagGroupType::TiposSuelo->value) {
            $this->loadFlagsRegiones();
        }

        return $this->getVxFlagsByGroup($flagGroup);
    }

    public function getVxFlagsByGroup(FlagGroup $flagGroup): array
    {
        return $flagGroup->flags->map(function($flag) {
                $region = '';
                if (isset($this->flagsRegiones)) {
                    $attr = $this->flagsRegiones->firstWhere('id_vx_flag', $flag->id);
                    if ($attr && $attr->region) {
                        $region = $attr->region;
                    }
                }

                return [
                    'id' => $flag->id,
                    'latitude' => $flag->latitude,
                    'longitude' => $flag->longitude,
                    'description' => $flag->description,
                    'region' => $region,
                    'visible' => true,
                ];
            })->toArray();
    }

    public function getVxFlagAttributes(int $vxFlagId) {
        return VxFlagAttributes::where('id_vx_flag', $vxFlagId)
                                    ->value('attributes');
    }

    protected function loadFlagsRegiones()
    {
        $this->flagsRegiones = VxFlagAttributes::select(
            'id',
            'id_vx_flag',
            DB::raw("attributes->>'region' as region")
        )->get();
    }
}