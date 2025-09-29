<?php
namespace App\Models;
use CodeIgniter\Model;

class BloqueoModel extends Model
{
    protected $table      = 'bloqueo_dpi';
    protected $primaryKey = 'id_bloqueo';
    protected $allowedFields = ['id_usuario', 'motivo', 'fecha_bloqueo'];

    // Traer bloqueos con datos del usuario
    public function getBloqueosConUsuario()
    {
        return $this->select('
                    bloqueo_dpi.id_bloqueo,
                    bloqueo_dpi.motivo,
                    bloqueo_dpi.fecha_bloqueo,
                    CONCAT(usuarios.nombre, " ", usuarios.primer_apellido, " ", IFNULL(usuarios.segundo_apellido,"")) as nombre,
                    usuarios.cui
                ')
                ->join('usuarios', 'usuarios.id_usuario = bloqueo_dpi.id_usuario', 'left')
                ->findAll();
    }
}
