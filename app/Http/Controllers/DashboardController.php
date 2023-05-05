<?php

namespace App\Http\Controllers;

use App\Repositories\VxFlagsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function show(VxFlagsRepository $flagsRepo): \Inertia\Response
    {
        $flagsGroups = $flagsRepo->getFlagsGroups();

        return Inertia::render('Dashboard', compact('flagsGroups'));
    }

    public function getVxFlagInfo(int $vxFlagId, VxFlagsRepository $flagsRepo): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'attributes' => $flagsRepo->getVxFlagAttributes($vxFlagId),
        ]);
    }
}
