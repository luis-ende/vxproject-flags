<?php

namespace App\Repositories;

use App\Models\FlagGroup;
use App\Models\VxFlagAttributes;
use Illuminate\Support\Facades\DB;

class VxFlagsRepository
{
    public function getFlagsGroups(): array
    {
        $flagsRegiones = VxFlagAttributes::select(
            'id',
            'id_vx_flag',
            DB::raw("attributes->>'region' as region")
        )->get();

        return FlagGroup::orderBy('id')
                           ->get()
                           ->map(function($item) use($flagsRegiones) {
                               return [
                                   'id' => $item->id,
                                   'type' => $item->type,
                                   'name' => $item->nombre,
                                   'color' => $item->color,
                                   'flags' => $item->flags->map(function($flag) use($flagsRegiones) {
                                       $region = '';
                                       $attr = $flagsRegiones->firstWhere('id_vx_flag', $flag->id);
                                       if ($attr && $attr->region) {
                                           $region = $attr->region;
                                       }

                                       return [
                                           'id' => $flag->id,
                                           'latitude' => $flag->latitude,
                                           'longitude' => $flag->longitude,
                                           'description' => $flag->description,
                                           'region' => $region,
                                           'visible' => true,
                                       ];
                                   })->toArray()
                               ];})->toArray();
    }

    public function getVxFlagAttributes(int $vxFlagId) {
        return VxFlagAttributes::where('id_vx_flag', $vxFlagId)
                                    ->value('attributes');
    }
}