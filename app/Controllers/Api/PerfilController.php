<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class PerfilController extends BaseController
{
    // Obtener datos del usuario logueado
    public function datos()
    {
        $idUsuario = session()->get('id_usuario');
        if (!$idUsuario) {
            return $this->response->setJSON(['status'=>'error','message'=>'No hay sesión activa']);
        }

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->find($idUsuario);

        if (!$usuario) {
            return $this->response->setJSON(['status'=>'error','message'=>'Usuario no encontrado']);
        }

        return $this->response->setJSON([
            'status' => 'ok',
            'data' => [
                'nombre_completo' => $usuario['nombre'].' '.$usuario['primer_apellido'].' '.$usuario['segundo_apellido'],
                'direccion'       => $usuario['direccion'],
                'correo'          => $usuario['correo'],
                'cui'             => $usuario['cui'],
                'telefono'        => $usuario['telefono']
            ]
        ]);
    }

    // Actualizar correo y teléfono
    public function actualizar()
    {
        $idUsuario = session()->get('id_usuario');
        $data = $this->request->getJSON(true);

        if (!$idUsuario || !$data) {
            return $this->response->setJSON(['status'=>'error','message'=>'Datos inválidos']);
        }

        $usuarioModel = new UsuarioModel();
        $ok = $usuarioModel->update($idUsuario, [
            'correo'   => $data['correo'],
            'telefono' => $data['telefono']
        ]);

        if ($ok) {
            // Actualizar sesión
            session()->set('correo', $data['correo']);
            session()->set('telefono', $data['telefono']);
            return $this->response->setJSON(['status'=>'ok']);
        } else {
            return $this->response->setJSON(['status'=>'error','message'=>'No se pudo actualizar']);
        }
    }
    public function estadoDpi()
    {
        $idUsuario = session()->get('id_usuario');
        if (!$idUsuario) {
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>'No hay sesión activa'
            ]);
        }

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->find($idUsuario);

        // Calcular fecha de vencimiento (+10 años desde fecha_creacion)
        $fechaCreacion = new \DateTime($usuario['fecha_creacion']);
        $fechaVenc = (clone $fechaCreacion)->modify('+10 years');

        // Verificar si está bloqueado
        $db = \Config\Database::connect();
        $bloqueo = $db->table('bloqueo_dpi')
                      ->where('id_usuario',$idUsuario)
                      ->get()
                      ->getRow();
        $bloqueado = $bloqueo ? 'Sí' : 'No';

        return $this->response->setJSON([
            'status' => 'ok',
            'data' => [
                'nombre_completo'   => $usuario['nombre'].' '.$usuario['primer_apellido'].' '.$usuario['segundo_apellido'],
                'cui'               => $usuario['cui'],
                'fecha_vencimiento' => $fechaVenc->format('Y-m-d'),
                'bloqueado'         => $bloqueado
            ]
        ]);
    }
}


