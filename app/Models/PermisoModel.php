<?php
namespace App\Models;

use CodeIgniter\Model;

class PermisoModel extends Model
{
    protected $table      = 'permisos';
    protected $primaryKey = 'id_permiso';

    protected $allowedFields = [
        'id_usuario',
        'gestionar_usuarios',
        'gestionar_registros',
        'generar_certificados',
        'generar_reportes',
        'exportar_reportes',
        'configurar_sistema',
        'realizar_busqueda'
    ];
}
