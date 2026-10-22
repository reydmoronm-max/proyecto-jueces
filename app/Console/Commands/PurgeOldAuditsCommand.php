<?php

namespace App\Console\Commands;

use App\Services\AuditService;
use Illuminate\Console\Command;

class PurgeOldAuditsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:purge {--years=1 : Cantidad de años de antigüedad para depurar} {--force : Forzar la ejecución sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Depura registros de auditoría anteriores a una cantidad de años determinada (por defecto 1 año)';

    /**
     * Execute the console command.
     */
    public function handle(AuditService $auditService): int
    {
        $years = (int) $this->option('years');
        if ($years < 1) {
            $years = 1;
        }

        $force = $this->option('force');

        if (!$force && !$this->confirm("¿Está seguro de que desea eliminar todos los registros de auditoría con más de {$years} año(s) de antigüedad?")) {
            $this->info('Operación cancelada.');
            return self::SUCCESS;
        }

        $this->info("Iniciando depuración de auditorías con más de {$years} año(s)...");

        $resultado = $auditService->purgeAuditoriasAntiguas($years);

        $this->info("✓ Depuración completada exitosamente.");
        $this->info("Registros eliminados: {$resultado['eliminados']}");
        $this->info("Fecha de corte aplicada: {$resultado['fecha_limite']}");

        return self::SUCCESS;
    }
}
