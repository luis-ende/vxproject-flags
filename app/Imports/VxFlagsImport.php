<?php

namespace App\Imports;

use App\Models\VxFlag;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VxFlagsImport implements ToModel, WithHeadingRow
{
    public function __construct(protected int $flagGroupId)
    {
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new VxFlag([
            'id_flag_group' => $this->flagGroupId,
            'longitude' => $row['longitud'],
            'latitude' => $row['latitud'],
            'description' => $row['descripcion'],
        ]);
    }

}
