<?php

namespace App\Http\Controllers;

use App\Repositories\VxFlagsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminPanelController extends Controller
{
    public function show(VxFlagsRepository $flagsRepo)
    {
        $flagsGroups = $flagsRepo->getFlagsGroups(false);

        return Inertia::render('ConfigPanel', compact('flagsGroups'));
    }
}
