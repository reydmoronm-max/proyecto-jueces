<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DatabaseBackupService
{
    protected string $backupDir;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups');
        if (!File::exists($this->backupDir)) {
            File::makeDirectory($this->backupDir, 0755, true);
        }
    }

    /**
     * Obtiene el directorio donde se guardan los respaldos.
     */
    public function getBackupDir(): string
    {
        return $this->backupDir;
    }

    /**
     * Exporta la base de datos completa a un archivo .sql
     *
     * @return array ['success' => bool, 'filename' => string, 'path' => string, 'size' => string]
     */
    public function exportDatabase(): array
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $dbName = DB::getDatabaseName();
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "respaldo_{$dbName}_{$timestamp}.sql";
        $filePath = $this->backupDir . DIRECTORY_SEPARATOR . $filename;

        $handle = fopen($filePath, 'w');
        if (!$handle) {
            throw new Exception("No se pudo crear el archivo de respaldo en: {$filePath}");
        }

        try {
            $pdo = DB::connection()->getPdo();

            // Encabezado del archivo SQL
            fwrite($handle, "-- ========================================================\n");
            fwrite($handle, "-- Respaldo de Base de Datos: {$dbName}\n");
            fwrite($handle, "-- Generado por: Sistema de Gestión e Información Comunal\n");
            fwrite($handle, "-- Fecha y Hora: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "-- Servidor MySQL: " . $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION) . "\n");
            fwrite($handle, "-- ========================================================\n\n");

            fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n");
            fwrite($handle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
            fwrite($handle, "SET AUTOCOMMIT = 0;\n");
            fwrite($handle, "START TRANSACTION;\n");
            fwrite($handle, "SET time_zone = \"+00:00\";\n");
            fwrite($handle, "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n");
            fwrite($handle, "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n");
            fwrite($handle, "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n");
            fwrite($handle, "/*!40101 SET NAMES utf8mb4 */;\n\n");

            // Obtener todas las tablas
            $tablesResult = DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
            $tables = [];
            foreach ($tablesResult as $row) {
                $rowArray = (array) $row;
                $tables[] = reset($rowArray);
            }

            foreach ($tables as $table) {
                // Estructura de la tabla
                fwrite($handle, "\n-- --------------------------------------------------------\n");
                fwrite($handle, "-- Estructura de tabla para la tabla `{$table}`\n");
                fwrite($handle, "-- --------------------------------------------------------\n\n");

                fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");

                $createResult = DB::select("SHOW CREATE TABLE `{$table}`");
                $createRow = (array) $createResult[0];
                $createSql = $createRow['Create Table'] ?? reset($createRow);
                fwrite($handle, $createSql . ";\n\n");

                // Datos de la tabla
                fwrite($handle, "-- Volcado de datos para la tabla `{$table}`\n\n");

                $columns = DB::getSchemaBuilder()->getColumnListing($table);
                $escapedColumns = array_map(fn($col) => "`{$col}`", $columns);
                $columnList = implode(', ', $escapedColumns);

                // Exportar registros en bloques de 200 filas
                $rows = DB::table($table)->get();
                if ($rows->isNotEmpty()) {
                    $batchSize = 200;
                    $chunks = $rows->chunk($batchSize);

                    foreach ($chunks as $chunk) {
                        $valuesList = [];
                        foreach ($chunk as $row) {
                            $rowArray = (array) $row;
                            $escapedValues = [];
                            foreach ($columns as $col) {
                                $val = $rowArray[$col] ?? null;
                                if ($val === null) {
                                    $escapedValues[] = 'NULL';
                                } else {
                                    $escapedValues[] = $pdo->quote((string) $val);
                                }
                            }
                            $valuesList[] = '(' . implode(', ', $escapedValues) . ')';
                        }

                        fwrite($handle, "INSERT INTO `{$table}` ({$columnList}) VALUES\n");
                        fwrite($handle, implode(",\n", $valuesList) . ";\n\n");
                    }
                }
            }

            fwrite($handle, "\nCOMMIT;\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n");
            fwrite($handle, "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n");
            fwrite($handle, "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n");
            fwrite($handle, "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n");

            fclose($handle);

            $fileSizeBytes = filesize($filePath);

            return [
                'success' => true,
                'filename' => $filename,
                'path' => $filePath,
                'size' => $this->formatBytes($fileSizeBytes),
            ];
        } catch (Exception $e) {
            fclose($handle);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            Log::error('Error al exportar base de datos: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Restaura la base de datos desde un archivo .sql
     *
     * @param string $filePath Ruta absoluta al archivo SQL
     * @return array ['success' => bool, 'message' => string]
     */
    public function importDatabase(string $filePath): array
    {
        if (!File::exists($filePath)) {
            throw new Exception('El archivo de respaldo especificado no existe.');
        }

        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            // Leer y procesar el archivo SQL
            $sqlContent = File::get($filePath);

            if (empty(trim($sqlContent))) {
                throw new Exception('El archivo SQL está vacío.');
            }

            // Ejecutar el script SQL completo
            DB::unprepared($sqlContent);

            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            return [
                'success' => true,
                'message' => 'Base de datos restaurada satisfactoriamente.',
            ];
        } catch (Exception $e) {
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            } catch (Exception $ignored) {
            }

            Log::error('Error al importar base de datos: ' . $e->getMessage());
            throw new Exception('Error al restaurar la base de datos: ' . $e->getMessage());
        }
    }

    /**
     * Lista todos los archivos de respaldo disponibles en storage/app/backups
     *
     * @return array
     */
    public function getBackupsList(): array
    {
        $files = File::glob($this->backupDir . DIRECTORY_SEPARATOR . '*.sql');
        $backups = [];

        foreach ($files as $file) {
            $filename = basename($file);
            $size = filesize($file);
            $mtime = filemtime($file);

            $backups[] = [
                'filename' => $filename,
                'path' => $file,
                'size' => $this->formatBytes($size),
                'size_raw' => $size,
                'date' => date('d/m/Y h:i A', $mtime),
                'timestamp' => $mtime,
            ];
        }

        // Ordenar del más reciente al más antiguo
        usort($backups, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        return $backups;
    }

    /**
     * Elimina un archivo de respaldo específico.
     */
    public function deleteBackup(string $filename): bool
    {
        // Sanitizar el nombre del archivo para evitar Directory Traversal
        $safeFilename = basename($filename);
        $filePath = $this->backupDir . DIRECTORY_SEPARATOR . $safeFilename;

        if (File::exists($filePath) && str_ends_with($safeFilename, '.sql')) {
            return File::delete($filePath);
        }

        return false;
    }

    /**
     * Obtiene estadísticas generales de la base de datos y respaldos.
     */
    public function getDatabaseStats(): array
    {
        $dbName = DB::getDatabaseName();
        $tablesResult = DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
        $tablesCount = count($tablesResult);

        // Estimar total de registros y tamaño en MB
        $sizeQuery = DB::select("
            SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb,
                SUM(table_rows) AS total_rows
            FROM information_schema.TABLES
            WHERE table_schema = ?
        ", [$dbName]);

        $dbSizeMb = $sizeQuery[0]->size_mb ?? '0.00';
        $totalRecords = $sizeQuery[0]->total_rows ?? 0;

        $backups = $this->getBackupsList();
        $totalBackups = count($backups);
        $lastBackup = !empty($backups) ? $backups[0]['date'] : 'Ninguno registrado';

        return [
            'db_name' => $dbName,
            'tables_count' => $tablesCount,
            'size_mb' => $dbSizeMb . ' MB',
            'total_records' => number_format((float) $totalRecords, 0, ',', '.'),
            'total_backups' => $totalBackups,
            'last_backup' => $lastBackup,
        ];
    }

    /**
     * Formatea bytes a KB, MB o GB legibles.
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
