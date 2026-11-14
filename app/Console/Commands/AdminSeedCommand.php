<?php

namespace App\Console\Commands;

use Database\Seeders\AdminSeeder;
use Illuminate\Console\Command;

class AdminSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia la base de datos por completo y registra únicamente al usuario administrador con rol Jefe de comuna';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->warn('Iniciando limpieza de la base de datos y creación del usuario administrador...');

        $this->call(AdminSeeder::class);

        $this->newLine();
        $this->info('✓ Base de datos completamente vaciada.');
        $this->info('✓ Único usuario registrado con éxito:');
        $this->table(
            ['Campo', 'Valor'],
            [
                ['Nombre', 'Juan'],
                ['Apellido', 'Pérez'],
                ['Cédula / Usuario', '11123456'],
                ['Contraseña', 'jefe'],
                ['Rol', 'Jefe de comuna (admin)'],
                ['Estado', 'Activo'],
                ['Pregunta de seguridad', '¿Qué color le gusta más?'],
                ['Respuesta de seguridad', 'azul'],
            ]
        );

        return self::SUCCESS;
    }
}
