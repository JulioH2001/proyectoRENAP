<?php
namespace App\Models;
use CodeIgniter\Model;

class SolicitudModel extends Model
{
    protected $table      = 'solicitudes_dpi';
    protected $primaryKey = 'id_solicitud';
    protected $allowedFields = ['id_usuario', 'fecha_solicitud', 'estado'];

    // Traer solicitudes con datos del usuario (tolerante a faltantes)
    public function getSolicitudesConUsuario()
    {
        return $this->select('
                    solicitudes_dpi.id_solicitud,
                    TRIM(CONCAT(
                        COALESCE(usuarios.nombre, ""),
                        " ",
                        COALESCE(usuarios.primer_apellido, ""),
                        " ",
                        COALESCE(usuarios.segundo_apellido, "")
                    )) AS nombre,
                    usuarios.cui,
                    solicitudes_dpi.fecha_solicitud,
                    solicitudes_dpi.estado
                ')
                ->join('usuarios', 'usuarios.id_usuario = solicitudes_dpi.id_usuario', 'left') // ← clave
                ->findAll();
    }
}
