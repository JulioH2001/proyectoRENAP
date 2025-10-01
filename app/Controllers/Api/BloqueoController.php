<?php
namespace App\Controllers\Api;
use App\Controllers\BaseController;
use App\Models\BloqueoModel;

class BloqueoController extends BaseController
{
    public function index()
    {
        $model = new BloqueoModel();
        $data = $model->getBloqueosConUsuario();
        return $this->response->setJSON(['data' => $data]);
    }

    public function crear()
    {
        $model = new BloqueoModel();
        $json = $this->request->getJSON(true);

        if ($model->insert($json)) {
            return $this->response->setJSON(['status' => 'ok']);
        }
        return $this->response->setStatusCode(400)->setJSON(['status' => 'error']);
    }

    public function eliminar($id)
    {
        $model = new BloqueoModel();
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'ok']);
        }
        return $this->response->setStatusCode(400)->setJSON(['status' => 'error']);
    }

    public function ids()
{
    $model = new BloqueoModel();
    $rows = $model->select('id_usuario')->findAll();
    $ids = array_values(array_unique(array_map('intval', array_column($rows, 'id_usuario'))));
    return $this->response->setJSON(['data' => $ids]);
}


    public function desbloquear($idUsuario)
{
    $model = new BloqueoModel();
    // Elimina todos los bloqueos de ese usuario
    if ($model->where('id_usuario', $idUsuario)->delete()) {
        return $this->response->setJSON(['status' => 'ok']);
    }
    return $this->response->setStatusCode(400)->setJSON(['status' => 'error']);

    
}

}
