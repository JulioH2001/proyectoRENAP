<?php
namespace App\Controllers\Api;
use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class OperadorController extends BaseController
{
    public function perfil()
    {
        $session = session();
        $id  = $session->get('id_usuario');
        $rol = $session->get('rol');

        if (!$id || strtolower($rol) !== 'operador') {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'No autorizado']);
        }

        $model = new UsuarioModel();
        $operador = $model->find($id);

        if (!$operador) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'No encontrado']);
        }

        unset($operador['password_hash']);
        return $this->response->setJSON(['data' => $operador]);
    }

    public function actualizarPerfil()
    {
        $session = session();
        $id  = $session->get('id_usuario');
        $rol = $session->get('rol');

        if (!$id || strtolower($rol) !== 'operador') {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'No autorizado']);
        }

        $model  = new UsuarioModel();
        $actual = $model->find($id);

        if (!$actual) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'No encontrado']);
        }

        $json = $this->request->getJSON(true);

        if (empty($json['actualPassword']) || !password_verify($json['actualPassword'], $actual['password_hash'])) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Contraseña actual incorrecta']);
        }

        $datos = [];
        foreach (['nombre','correo'] as $campo) {
            if (array_key_exists($campo, $json)) {
                $datos[$campo] = $json[$campo];
            }
        }

        if (!empty($json['nuevaPassword'])) {
            $datos['password_hash'] = password_hash($json['nuevaPassword'], PASSWORD_DEFAULT);
        }

        if ($model->update($id, $datos)) {
            return $this->response->setJSON(['status' => 'ok']);
        }

        return $this->response->setStatusCode(400)->setJSON(['error' => 'Error al actualizar']);
    }
}
