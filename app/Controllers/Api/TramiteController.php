<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\TramiteModel;
use App\Models\UsuarioModel;

class TramitesController extends BaseController
{
    public function historial()
    {
        $idUsuario = session()->get('id_usuario');
        if (!$idUsuario) {
            return $this->response->setJSON(['status'=>'error','message'=>'No hay sesión activa']);
        }

        $tramiteModel = new TramiteModel();
        $usuarioModel = new UsuarioModel();

        $usuario = $usuarioModel->find($idUsuario);
        $tramites = $tramiteModel->where('id_usuario', $idUsuario)
                                 ->orderBy('fecha_solicitud','DESC')
                                 ->findAll();

        $data = [];
        foreach ($tramites as $t) {
            $data[] = [
                'cui'   => $usuario['cui'],
                'fecha' => $t['fecha_solicitud'],
                'tipo'  => $t['tipo']
            ];
        }

        return $this->response->setJSON($data);
    }
}
