<?php
namespace App\Models;
use CodeIgniter\Model;

class ReposicionDpiModel extends Model
{
    protected $table      = 'reposicion_dpi';
    protected $primaryKey = 'id_reposicion';
    protected $allowedFields = [
        'id_usuario','motivo','tipo_entrega','departamento',
        'numero_recibo','numero_nota','total',
        'estado','boleta_path','fecha_solicitud' // 👈 agregar aquí
    ];
}

