<?php
namespace App\Controllers\Api;
use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class UsuarioController extends BaseController {
    public function index() {
        $model = new UsuarioModel();
        $usuarios = $model->findAll();
        return $this->response->setJSON(['data' => $usuarios]);
    }
    public function actualizar($id)
{
    $model = new UsuarioModel();
    $datos = $this->request->getJSON(true);

    if ($model->update($id, $datos)) {
        return $this->response->setJSON(['status' => 'ok']);
    }

    return $this->response->setStatusCode(400)->setJSON(['status' => 'error']);
}

}
