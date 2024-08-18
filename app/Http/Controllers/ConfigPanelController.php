<?php

namespace App\Http\Controllers;

use App\Models\FlagGroup;
use App\Repositories\VxFlagsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConfigPanelController extends Controller
{
    public function show(VxFlagsRepository $flagsRepo)
    {
        $flagsGroups = $flagsRepo->getFlagsGroups(false);
        $tipoSueloSitiosImport = $flagsRepo->getFlagsImportInfo(3);

        return Inertia::render('ConfigPanel', compact('flagsGroups', 'tipoSueloSitiosImport'));
    }

    public function tiposSueloColors(Request $request, $groupId)
    {
        $group = FlagGroup::find($groupId);
        if ($group) {
            $config = json_decode($group->config, false);
            $config->sites_colors = $request->tipoSueloColors;
            $group->config = json_encode($config);
            $group->save();
        }

        return back(303);
    }
}
