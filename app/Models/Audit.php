<?php

namespace App\Models;

use Carbon\Carbon;
use OwenIt\Auditing\Models\Audit as BaseAudit;

class Audit extends BaseAudit
{
    /**
     * Diccionario de traducción de nombres de campos técnicos a etiquetas legibles en español.
     */
    protected static array $diccionarioCampos = [
        // Datos personales
        'primer_nombre' => 'Primer Nombre',
        'segundo_nombre' => 'Segundo Nombre',
        'primer_apellido' => 'Primer Apellido',
        'segundo_apellido' => 'Segundo Apellido',
        'nombre' => 'Nombre',
        'apellido' => 'Apellido',
        'cedula' => 'Cédula',
        'cedula_tipo' => 'Tipo de Cédula',
        'cedula_usuario' => 'Cédula de Usuario',
        'fecha_nacimiento' => 'Fecha de Nacimiento',
        'genero' => 'Género',
        'sexo' => 'Sexo',
        'edad' => 'Edad',
        'estado_civil' => 'Estado Civil',
        'nacionalidad' => 'Nacionalidad',
        'nivel_instruccion' => 'Nivel de Instrucción',
        'ocupacion' => 'Ocupación u Oficio',
        'profesion' => 'Profesión',
        'trabaja' => '¿Trabaja actualmente?',
        'ingreso_mensual' => 'Ingreso Mensual Aprox.',

        // Contacto y ubicación
        'telefono' => 'Teléfono',
        'telefono_celular' => 'Teléfono Celular',
        'telefono_habitacion' => 'Teléfono de Habitación',
        'telefono_fijo' => 'Teléfono Fijo',
        'correo' => 'Correo Electrónico',
        'email' => 'Correo Electrónico',
        'direccion' => 'Dirección de Habitación',
        'direccion_habitacion' => 'Dirección de Habitación',
        'calle' => 'Calle / Avenida',
        'manzana' => 'Manzana / Vereda',
        'numero_casa' => 'Número de Casa',
        'punto_referencia' => 'Punto de Referencia',

        // Salud y vulnerabilidad
        'discapacidad' => '¿Posee Discapacidad?',
        'tipo_discapacidad' => 'Tipo de Discapacidad',
        'enfermedad_cronica' => '¿Enfermedad Crónica?',
        'nombre_enfermedad' => 'Nombre de la Enfermedad',
        'recibe_medicacion' => '¿Recibe Medicación?',
        'medicamentos_requeridos' => 'Medicamentos Requeridos',
        'embarazada' => '¿Está Embarazada?',
        'semanas_embarazo' => 'Semanas de Embarazo',
        'lactancia' => '¿En período de Lactancia?',
        'pensionado' => '¿Es Pensionado?',
        'tipo_pension' => 'Tipo de Pensión',

        // Núcleo familiar y vivienda
        'es_jefe_familia' => '¿Es Jefe de Familia?',
        'familia_id' => 'Núcleo Familiar',
        'parentesco_jefe' => 'Parentesco con Jefe de Familia',
        'parentesco' => 'Parentesco',
        'carga_familiar' => 'Carga Familiar',
        'codigo_familia' => 'Código de Familia',
        'tipo_vivienda' => 'Tipo de Vivienda',
        'condicion_vivienda' => 'Condición de la Vivienda',
        'tenencia_vivienda' => 'Tenencia de Vivienda',
        'tipo_techo' => 'Tipo de Techo',
        'tipo_piso' => 'Tipo de Piso',
        'tipo_paredes' => 'Tipo de Paredes',
        'habitaciones' => 'Cantidad de Habitaciones',
        'banos' => 'Cantidad de Baños',
        'servicios_basicos' => 'Servicios Básicos',
        'cocina_gas' => '¿Cocina a Gas?',
        'cilindro_gas' => 'Cilindros de Gas',
        'tipo_gas' => 'Tipo de Gas',
        'clap' => '¿Recibe CLAP?',
        'carnet_patria' => '¿Posee Carnet de la Patria?',
        'codigo_carnet' => 'Código Carnet de la Patria',
        'serial_carnet' => 'Serial Carnet de la Patria',

        // Expedientes y Justicia de Paz
        'numero_expediente' => 'Número de Expediente',
        'num_expediente' => 'Número de Expediente',
        'asunto' => 'Asunto / Motivo',
        'motivo' => 'Motivo',
        'estado' => 'Estado del Caso',
        'estatus' => 'Estatus',
        'fecha_apertura' => 'Fecha de Apertura',
        'fecha_cierre' => 'Fecha de Cierre',
        'observacion' => 'Observaciones',
        'observaciones' => 'Observaciones',
        'descripcion' => 'Descripción',
        'acuerdos' => 'Acuerdos Alcanzados',
        'conclusiones' => 'Conclusiones',
        'denunciado_a' => 'Denunciado(a)',

        // Citaciones
        'fecha_cita' => 'Fecha de la Cita',
        'hora_cita' => 'Hora de la Cita',
        'lugar_cita' => 'Lugar de la Cita',
        'motivo_cita' => 'Motivo de la Cita',
        'estado_citacion' => 'Estado de la Citación',
        'asistio' => '¿Asistió a la Cita?',
        'numero_citacion' => 'Número de Citación',

        // Visitas
        'motivo_visita' => 'Motivo de la Visita',
        'fecha_visita' => 'Fecha de la Visita',
        'hora_visita' => 'Hora de la Visita',
        'resultado_visita' => 'Resultado de la Visita',
        'acuerdo_visita' => 'Acuerdo de Visita',

        // Vocerías y Consejos Comunales
        'nombre_consejo' => 'Nombre del Consejo Comunal',
        'codigo_situr' => 'Código SITUR',
        'rif' => 'RIF',
        'comunidad' => 'Comunidad',
        'parroquia' => 'Parroquia',
        'sector' => 'Sector',
        'categoria_id' => 'Categoría / Comité',
        'categoria_voceria_id' => 'Categoría de Vocería',
        'nombre_categoria' => 'Nombre de la Categoría',
        'persona_id' => 'Persona Vinculada',
        'consejo_comunal_id' => 'Consejo Comunal',
        'periodo_inicio' => 'Inicio de Período',
        'periodo_fin' => 'Fin de Período',

        // Círculo de Abuelos y Proyectos
        'nombre_jornada' => 'Nombre de la Jornada',
        'fecha_jornada' => 'Fecha de la Jornada',
        'atendidos' => 'Cantidad de Atendidos',
        'nombre_proyecto' => 'Nombre del Proyecto',
        'monto_aprobado' => 'Monto Aprobado',
        'porcentaje_avance' => 'Porcentaje de Avance',
        'fecha_inicio' => 'Fecha de Inicio',
        'fecha_fin' => 'Fecha Estimada de Fin',

        // Usuarios y Seguridad
        'rol' => 'Rol de Usuario',
        'activo' => 'Estado de la Cuenta (Activo)',
        'pregunta_seguridad' => 'Pregunta de Seguridad',
        'password' => 'Contraseña',
        'remember_token' => 'Token de Sesión',
        'respuesta_seguridad' => 'Respuesta de Seguridad',
    ];

    /**
     * Mapeo de clases de modelos a nombres legibles de módulos.
     */
    protected static array $mapaModulos = [
        'App\Models\Persona' => 'Personas / Censo',
        'App\Models\Familia' => 'Núcleos Familiares',
        'App\Models\Expediente' => 'Expedientes de Paz',
        'App\Models\Citaciones' => 'Citaciones',
        'App\Models\Visita' => 'Visitas y Mediaciones',
        'App\Models\Vocero' => 'Voceros Comunales',
        'App\Models\ConsejoComunal' => 'Consejos Comunales',
        'App\Models\CategoriaVoceria' => 'Categorías de Vocería',
        'App\Models\Proyecto' => 'Proyectos Comunitarios',
        'App\Models\JornadaAbuelo' => 'Círculo de Abuelos',
        'App\Models\User' => 'Gestión de Usuarios',
        'App\Models\Actas' => 'Actas y Cartas',
        'App\Models\Involucrados' => 'Involucrados en Casos',
    ];

    /**
     * Devuelve el nombre amigable del módulo afectado.
     */
    public function getModuloNombreAttribute(): string
    {
        if ($this->auditable_type && isset(static::$mapaModulos[$this->auditable_type])) {
            return static::$mapaModulos[$this->auditable_type];
        }

        if ($this->tags) {
            return $this->tags;
        }

        if (in_array($this->event, ['login', 'logout'])) {
            return 'Autenticación y Seguridad';
        }

        if (in_array($this->event, ['backup_export', 'backup_import', 'backup_restore'])) {
            return 'Base de Datos';
        }

        if ($this->event === 'purge') {
            return 'Mantenimiento de Auditoría';
        }

        if ($this->auditable_type) {
            return class_basename($this->auditable_type);
        }

        return 'Sistema General';
    }

    /**
     * Devuelve el nombre amigable del evento.
     */
    public function getAccionNombreAttribute(): string
    {
        return match ($this->event) {
            'created' => 'CREACIÓN',
            'updated' => 'MODIFICACIÓN',
            'deleted' => 'ELIMINACIÓN',
            'restored' => 'RESTAURACIÓN',
            'login' => 'INICIO DE SESIÓN',
            'logout' => 'CIERRE DE SESIÓN',
            'backup_export' => 'EXPORTAR BD',
            'backup_import' => 'IMPORTAR BD',
            'backup_restore' => 'RESTAURAR BD',
            'purge' => 'DEPURACIÓN',
            default => strtoupper($this->event),
        };
    }

    /**
     * Color de badge Bootstrap / Hope UI según el evento.
     */
    public function getBadgeColorAttribute(): string
    {
        return match ($this->event) {
            'created' => 'success',
            'updated' => 'primary',
            'deleted' => 'danger',
            'login' => 'info',
            'logout' => 'secondary',
            'backup_export', 'backup_import', 'backup_restore' => 'warning',
            'purge' => 'dark',
            default => 'primary',
        };
    }

    /**
     * Icono RemixIcon representativo según el evento.
     */
    public function getIconoAttribute(): string
    {
        return match ($this->event) {
            'created' => 'ri-add-circle-fill',
            'updated' => 'ri-edit-circle-fill',
            'deleted' => 'ri-delete-bin-fill',
            'login' => 'ri-login-box-fill',
            'logout' => 'ri-logout-box-r-fill',
            'backup_export', 'backup_import', 'backup_restore' => 'ri-database-2-fill',
            'purge' => 'ri-brush-fill',
            default => 'ri-history-line',
        };
    }

    /**
     * Traduce un nombre de campo a etiqueta amigable.
     */
    public static function traducirCampo(string $campo): string
    {
        if (isset(static::$diccionarioCampos[$campo])) {
            return static::$diccionarioCampos[$campo];
        }

        return ucwords(str_replace(['_', '-'], ' ', $campo));
    }

    /**
     * Devuelve un resumen legible en español de la acción realizada.
     */
    public function getDescripcionAmigableAttribute(): string
    {
        $usuario = $this->user ? "{$this->user->nombre} {$this->user->apellido}" : 'Usuario del Sistema';
        $modulo = $this->modulo_nombre;

        return match ($this->event) {
            'created' => "El usuario {$usuario} creó un nuevo registro en {$modulo} (ID: #{$this->auditable_id}).",
            'updated' => "El usuario {$usuario} modificó el registro #{$this->auditable_id} en {$modulo} (" . count($this->getModified()) . " campo(s) cambiados).",
            'deleted' => "El usuario {$usuario} eliminó el registro #{$this->auditable_id} en {$modulo}.",
            'login' => "El usuario {$usuario} inició sesión satisfactoriamente.",
            'logout' => "El usuario {$usuario} cerró su sesión en el sistema.",
            'backup_export' => "El usuario {$usuario} exportó una copia de respaldo de la base de datos.",
            'backup_restore' => "El usuario {$usuario} restauró la base de datos.",
            'backup_import' => "El usuario {$usuario} importó un archivo de respaldo a la base de datos.",
            'purge' => "Se realizó una depuración de registros de auditoría antiguos.",
            default => "Acción {$this->event} realizada en {$modulo} por {$usuario}.",
        };
    }

    /**
     * Formatea un valor para presentación visual.
     */
    public static function formatearValor(mixed $valor): string
    {
        if (is_null($valor)) {
            return '— (Vacío)';
        }

        if (is_bool($valor)) {
            return $valor ? 'Sí' : 'No';
        }

        if (is_array($valor)) {
            return json_encode($valor, JSON_UNESCAPED_UNICODE);
        }

        if ($valor === '1' || $valor === 1) {
            return 'Sí / 1';
        }

        if ($valor === '0' || $valor === 0) {
            return 'No / 0';
        }

        return (string) $valor;
    }

    /**
     * Obtiene el listado de cambios formateado con nombres en español para la vista de detalle.
     */
    public function getCambiosDetallados(): array
    {
        $detalles = [];
        $modificados = $this->getModified();

        foreach ($modificados as $campo => $cambio) {
            // Omitir timestamps y campos irrelevantes
            if (in_array($campo, ['updated_at', 'created_at', 'deleted_at'])) {
                continue;
            }

            $detalles[] = [
                'campo_clave' => $campo,
                'campo_nombre' => static::traducirCampo($campo),
                'valor_anterior' => static::formatearValor($cambio['old'] ?? null),
                'valor_nuevo' => static::formatearValor($cambio['new'] ?? null),
            ];
        }

        return $detalles;
    }

    /**
     * Obtiene los valores anteriores o nuevos completos formateados (para creación o eliminación).
     */
    public function getAtributosFormateados(string $tipo = 'new'): array
    {
        $fuente = ($tipo === 'old') ? $this->old_values : $this->new_values;
        if (!is_array($fuente)) {
            return [];
        }

        $resultado = [];
        foreach ($fuente as $campo => $valor) {
            if (in_array($campo, ['id', 'created_at', 'updated_at', 'deleted_at', 'password', 'remember_token', 'respuesta_seguridad'])) {
                continue;
            }

            $resultado[] = [
                'campo_clave' => $campo,
                'campo_nombre' => static::traducirCampo($campo),
                'valor' => static::formatearValor($valor),
            ];
        }

        return $resultado;
    }
}
