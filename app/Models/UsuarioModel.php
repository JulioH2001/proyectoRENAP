<?php
namespace App\Models;
use CodeIgniter\Model;

class UsuarioModel extends Model {
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $allowedFields = [
        'cui','nombre','primer_apellido','segundo_apellido',
        'correo','telefono','direccion','fecha_nacimiento',
        'genero','lugar_nacimiento','departamento','rol','password_hash'
    ];

    public function getEmpleados()
{
    return $this->whereIn('rol', ['admin','operador'])->findAll();
}
}
