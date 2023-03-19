<?php

namespace App\Imports;

use App\Models\FlagGroupType;
use App\Models\VxFlag;
use App\Models\VxFlagAttributes;
use App\Services\GeoCalculatorService;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class VxFlagsImport implements ToModel, WithHeadingRow, OnEachRow
{
    use GeoCalculatorService;

    public function __construct(
        protected int $flagGroupId,
        protected FlagGroupType $flagGroupType
    )
    {
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        switch ($this->flagGroupType)
        {
            case FlagGroupType::TemperaturasCFE:
                return new VxFlag([
                    'id_flag_group' => $this->flagGroupId,
                    'longitude' => $row['longitud'],
                    'latitude' => $row['latitud'],
                    'description' => $row['descripcion'],
                ]);
        }

        return null;
    }

    public function onRow(Row $row): void
    {
        if ($this->flagGroupType === FlagGroupType::TiposSuelo) {
            $rows = $row->toArray();
            $longitud2 = $this->DMSStringtoDEC($row['longitud2']);
            $latitud = $this->DMSStringtoDEC($row['latitud']);
            unset($rows['14']);
            unset($rows['15']);
            unset($rows['16']);
            unset($rows['17']);
            $jsonAttr = json_encode($rows);
            $vxFlag = VxFlag::create([
                'id_flag_group' => $this->flagGroupId,
                'longitude' => $longitud2,
                'latitude' => $latitud,
                'description' => $row['observaciones'],
            ]);
            VxFlagAttributes::create([
                'id_vx_flag' => $vxFlag->id,
                'attributes' => $jsonAttr,
            ]);
        }
    }
}
