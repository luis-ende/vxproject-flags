<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CatPaisesRepository {

    protected Collection $paises;

    public function __construct() {
        $this->paises = Cache::rememberForever('cat_paises', function () {
            return DB::table('cat_paises')
                        ->select('id', 'codigo_iso', 'pais')
                        ->orderBy('codigo_iso')
                        ->get();
        });
    }
    public function getPaisId(string $code): ?int {
        $pais = $this->paises->firstWhere('codigo_iso', $code);

        if ($pais) {
            return $pais->id;
        } else {
            return null;
        }
    }
}