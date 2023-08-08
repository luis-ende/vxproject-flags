<?php

namespace App\Http\Controllers;

use App\Repositories\CatPaisesRepository;
use App\Repositories\VxFlagsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function show(VxFlagsRepository $flagsRepo, CatPaisesRepository $catPaisesRepo): \Inertia\Response
    {
        $flagsGroups = $flagsRepo->getFlagsGroups(false);
        $mapLayerUrl = env('MAP_TILER_API_ENDPOINT');
        $tiposSueloPaises = $flagsRepo->getPaises();
        $paisIdDefault = $catPaisesRepo->getPaisDefaultId();

        return Inertia::render('Dashboard',
                                compact('flagsGroups', 'mapLayerUrl', 'tiposSueloPaises', 'paisIdDefault'));
    }
}
