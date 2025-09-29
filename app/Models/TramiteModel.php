<?php
namespace App\Models;

use CodeIgniter\Model;

class TramiteModel extends Model
{
    protected $table      = 'tramites';          // 👈 nombre de la tabla
    protected $primaryKey = 'id_tramite';        // 👈 clave primaria

    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'id_usuario',
        'tipo',
        'fecha_solicitud',
        'estado'
    ];
}
