<?php
namespace App\Models;
use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table      = 'usuarios';          // 👈 Ajusta al nombre real de tu tabla
    protected $primaryKey = 'id_usuario';        // 👈 Ajusta al nombre de tu PK

    // Campos que se pueden actualizar
    protected $allowedFields = [
        'nombre',
        'correo',
        'password_hash'
    ];

    // Opcional: devolver resultados como array
    protected $returnType = 'array';
}
