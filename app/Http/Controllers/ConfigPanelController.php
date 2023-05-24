<?php

namespace App\Http\Controllers;

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
}
