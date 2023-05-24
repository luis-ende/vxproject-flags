<?php

namespace App\Imports;

use App\Models\FlagGroupType;
use App\Models\VxFlag;
use App\Models\VxFlagAttributes;
use App\Services\GeoCalculatorService;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class VxFlagsImport implements ToModel, WithHeadingRow, OnEachRow, WithCalculatedFormulas
{
    use GeoCalculatorService;

    protected array $importedRows = [];

    public function __construct(
        protected int $flagGroupId,
        protected FlagGroupType $flagGroupType
    )
    {
    }

    public function getImportedRows(): array
    {
        return $this->importedRows;
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
                $this->importedRows[] = [
                    'key' => $row['descripcion'],
                    'action' => 'insert',
                ];
                return new VxFlag([
                    'id_flag_group' => $this->flagGroupId,
                    'longitude' => $row['longitud'],
                    'latitude' => $row['latitud'],
                    'description' => $row['descripcion'],
                    'flag_key' => $row['descripcion'],
                ]);
        }

        return null;
    }

    public function onRow(Row $row): void
    {
        if ($this->flagGroupType === FlagGroupType::TiposSuelo) {
            $rows = $row->toArray(null, true);
            $longitud = $this->DMSStringtoDEC($this->trimDmsFormat($row['longitud']));
            $latitud = $this->DMSStringtoDEC($this->trimDmsFormat($row['latitud']));
            unset($rows['14']);
            unset($rows['15']);
            unset($rows['16']);
            unset($rows['17']);
            $descripcion = $row['sitio'] . "\n" . $row['sismo'] . " " . $row['zona'] . "-" . $row['tipo_terreno'];
            $jsonAttr = json_encode($rows);
            $flagKey = $row['sitio'];
            $action = 'insert';

            if (VxFlag::where('flag_key', $flagKey)->exists()) {
                $action = 'update';
            }

            $vxFlag = VxFlag::updateOrCreate(
                [
                    'flag_key' => $flagKey,
                ],
                [
                    'id_flag_group' => $this->flagGroupId,
                    'longitude' => $longitud,
                    'latitude' => $latitud,
                    'description' => $descripcion,
                ]
            );
            VxFlagAttributes::updateOrCreate(
                [
                    'id_vx_flag' => $vxFlag->id,
                ],
                [
                    'attributes' => $jsonAttr,
                ]
            );

            $this->importedRows[] = [
                'key' => $flagKey,
                'action' => $action,
            ];
        }
    }

    private function trimDmsFormat(string $dms): string
    {
        $items = explode(' ', $dms);
        array_walk($items, function (&$item) {
            $item = ltrim($item, '0');
            if ($item[0] === '.' || $item[0] === "'") {
                $item = '0' . $item;
            }
        });

        return implode(' ', $items);
    }
}
