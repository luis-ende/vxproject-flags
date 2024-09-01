<?php

namespace App\Http\Controllers;

use App\Models\FlagGroup;
use App\Models\VxFlag;
use App\Models\VxFlagNotes;
use App\Repositories\VxFlagsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class FlagsController extends Controller
{
    public function getFlagsByGroup(Request $request, VxFlagsRepository $flagsRepo, int $groupId)
    {
        try {
            $regiones = null;
            $paisId = null;
            if ($request->has('pais')) {
                $paisId = $request->input('pais');
            }

            if ($request->has('reg') && $request->get('reg') !== 'R0') {
                $regiones = explode(',', $request->input('reg'));
            }

            return response()->json([
                'flags' =>$flagsRepo->getVxFlagsByGroupId($groupId, $paisId, $regiones),
            ]);
        } catch(\Throwable $e) {
            return response()->json(['error' => 'Ocurrió un error al consultar flags'], 500);
        }
    }

    public function getVxFlagInfo(int $vxFlagId, VxFlagsRepository $flagsRepo): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'attributes' => $flagsRepo->getVxFlagAttributes($vxFlagId),
        ]);
    }

    public function getVxFlagNotes(int $vxFlagId, VxFlagsRepository $flagsRepo): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'notes' => $flagsRepo->getVxFlagNotes($vxFlagId, Auth::user()->id),
        ]);
    }

    public function destroy(int $vxFlagId)
    {
        VxFlag::where('id', $vxFlagId)->delete();

        return back(303);
    }

    public function groupUpdate(Request $request, int $groupId)
    {
        $this->validate($request, [
            'color' => 'required|string|size:7'
        ]);

        FlagGroup::where('id', $groupId)->update([
            'color' => $request->input('color')
        ]);

        return back(303);
    }

    public function updateVxFlagNotes(Request $request, int $vxFlagId)
    {
        $this->validate($request, [
            'notes' => 'required|string'
        ]);

        VxFlagNotes::upsert([
            [
                'id_vx_flag' => $vxFlagId,
                'user_id' => Auth::user()->id,
                'notes' => $request->input('notes'),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ], ['id_vx_flag', 'user_id'], ['notes', 'created_at', 'updated_at']);

        return back(303);
        //return response()->json(true);
    }

    public function flagsTiposSueloImport()
    {
        Artisan::call('vxproject:importa-tipos-suelo-sitios');

        return back(303);
    }
}
