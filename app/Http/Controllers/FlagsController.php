<?php

namespace App\Http\Controllers;

use App\Repositories\VxFlagsRepository;
use http\Env\Response;
use Illuminate\Http\Request;

class FlagsController extends Controller
{
    public function getFlagsByGroup(Request $request, VxFlagsRepository $flagsRepo, int $groupId)
    {
        try {
            return response()->json([
                'flags' =>$flagsRepo->getVxFlagsByGroupId($groupId),
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
}
