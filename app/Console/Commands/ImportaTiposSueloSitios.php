<?php

namespace App\Console\Commands;

use App\Imports\VxFlagsImport;
use App\Models\FlagGroupType;
use App\Models\FlagsImportacion;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Exceptions\NoFilePathGivenException;
use Maatwebsite\Excel\Facades\Excel;

class ImportaTiposSueloSitios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vxproject:importa-tipos-suelo-sitios {import_file_path?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa sitios del grupo Tipos de suelo.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->argument('import_file_path')) {
            $path = $this->argument('import_file_path');
        } else {
            $path = env('TIPOS_SUELO_SITIOS_IMPORT_FILE_PATH');
        }

        if (!$path) {
            throw new NoFilePathGivenException();
        }

        // TODO Id de grupo de flags (3) debe ser una constante o ser obtenida de otro lado
        $import = new VxFlagsImport(3, FlagGroupType::TiposSuelo);

        try {
            Excel::import($import, $path);
            $importedRows = $import->getImportedRows();
            $importInfo = [
                'archivo' => $path,
                'num_importados' => count($importedRows),
                'importados_info' => $importedRows,
            ];
            FlagsImportacion::create([
                'import_log' => json_encode($importInfo)
            ]);
        } catch (\Throwable $e) {
            $this->error('Error de importación: ' . $e->getMessage());
        }

        $this->info('Proceso de importación de sitios del grupo Tipo de suelo finalizado exitosamente.');
    }
}
