<?php

namespace App\Http\Controllers;

use App\Services\DatabaseBackupService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DatabaseBackupController extends Controller
{
    protected DatabaseBackupService $backupService;

    public function __construct(DatabaseBackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Muestra la interfaz de administración de la base de datos.
     */
    public function index()
    {
        $titulo = 'Base de Datos - SGIC';
        $paginaTitulo = 'Base de Datos';
        $paginaSubtitulo = 'Módulo para la exportación y restauración de la base de datos del sistema.';

        $stats = $this->backupService->getDatabaseStats();
        $backups = $this->backupService->getBackupsList();

        return view('modules.base_datos.index', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'stats', 'backups'));
    }

    /**
     * Genera y descarga de inmediato un respaldo completo en formato .sql
     */
    public function export()
    {
        try {
            $result = $this->backupService->exportDatabase();

            return response()->download($result['path'], $result['filename'], [
                'Content-Type' => 'application/sql',
            ]);
        } catch (Exception $e) {
            return redirect()->route('database-backup.index')
                ->with('error', 'Error al exportar la base de datos: ' . $e->getMessage());
        }
    }

    /**
     * Importa y restaura la base de datos desde un archivo .sql subido por el usuario.
     */
    public function import(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|max:102400', // Hasta 100MB
        ], [
            'backup_file.required' => 'Debe seleccionar un archivo .sql para restaurar.',
            'backup_file.file'     => 'El archivo subido no es válido.',
            'backup_file.max'      => 'El archivo de respaldo no debe superar los 100 MB.',
        ]);

        $file = $request->file('backup_file');

        // Validar extensión
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension !== 'sql') {
            return redirect()->route('database-backup.index')
                ->with('error', 'El archivo debe tener formato .sql');
        }

        try {
            $tempPath = $file->getRealPath();
            $this->backupService->importDatabase($tempPath);

            // Limpiar caché del sistema
            try {
                Artisan::call('cache:clear');
            } catch (Exception $ignored) {
            }

            return redirect()->route('database-backup.index')
                ->with('success', 'Base de datos restaurada satisfactoriamente con el archivo subido.');
        } catch (Exception $e) {
            return redirect()->route('database-backup.index')
                ->with('error', 'Error durante la restauración: ' . $e->getMessage());
        }
    }

    /**
     * Restaura la base de datos desde un archivo de respaldo almacenado en el servidor.
     */
    public function restore(string $filename)
    {
        try {
            $safeFilename = basename($filename);
            $filePath = $this->backupService->getBackupDir() . DIRECTORY_SEPARATOR . $safeFilename;

            if (!File::exists($filePath) || !str_ends_with($safeFilename, '.sql')) {
                return redirect()->route('database-backup.index')
                    ->with('error', 'El archivo de respaldo no fue encontrado.');
            }

            $this->backupService->importDatabase($filePath);

            try {
                Artisan::call('cache:clear');
            } catch (Exception $ignored) {
            }

            return redirect()->route('database-backup.index')
                ->with('success', "Base de datos restaurada correctamente desde el respaldo: {$safeFilename}");
        } catch (Exception $e) {
            return redirect()->route('database-backup.index')
                ->with('error', 'Error al restaurar: ' . $e->getMessage());
        }
    }

    /**
     * Descarga un archivo de respaldo existente en el servidor.
     */
    public function download(string $filename)
    {
        $safeFilename = basename($filename);
        $filePath = $this->backupService->getBackupDir() . DIRECTORY_SEPARATOR . $safeFilename;

        if (!File::exists($filePath) || !str_ends_with($safeFilename, '.sql')) {
            return redirect()->route('database-backup.index')
                ->with('error', 'El archivo de respaldo no existe o no es accesible.');
        }

        return response()->download($filePath, $safeFilename, [
            'Content-Type' => 'application/sql',
        ]);
    }

    /**
     * Elimina un archivo de respaldo almacenado en el servidor.
     */
    public function destroy(string $filename)
    {
        $deleted = $this->backupService->deleteBackup($filename);

        if ($deleted) {
            return redirect()->route('database-backup.index')
                ->with('success', 'Copia de seguridad eliminada del servidor.');
        }

        return redirect()->route('database-backup.index')
            ->with('error', 'No se pudo eliminar el archivo de respaldo.');
    }
}
