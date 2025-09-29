<?php
namespace App\Controllers\Api;
use App\Controllers\BaseController;
use App\Models\SolicitudModel;

class SolicitudController extends BaseController
{
   public function index()
{
    $model = new \App\Models\SolicitudModel();
    $data = $model->getSolicitudesConUsuario();
    return $this->response->setJSON($data);
}

public function aprobar($id)
{
    $model = new \App\Models\SolicitudModel();
    if ($model->update($id, ['estado' => 'Aprobado'])) {
        return $this->response->setJSON(['status' => 'ok', 'id' => $id]);
    }
    return $this->response->setStatusCode(400)->setJSON(['status' => 'error']);
}

public function rechazar($id)
{
    $model = new \App\Models\SolicitudModel();
    if ($model->update($id, ['estado' => 'Rechazado'])) {
        return $this->response->setJSON(['status' => 'ok', 'id' => $id]);
    }
    return $this->response->setStatusCode(400)->setJSON(['status' => 'error']);
}


    public function eliminar($id)
{
    $model = new SolicitudModel();

    // Verificar si existe la solicitud
    $solicitud = $model->find($id);
    if (!$solicitud) {
        return $this->response->setStatusCode(404)
                              ->setJSON(['status' => 'error', 'message' => 'Solicitud no encontrada']);
    }

    // Intentar eliminar
    if ($model->delete($id)) {
        return $this->response->setStatusCode(200)
                              ->setJSON(['status' => 'ok', 'message' => 'Solicitud eliminada']);
    } else {
        return $this->response->setStatusCode(400)
                              ->setJSON(['status' => 'error', 'message' => 'No se pudo eliminar']);
    }
}

}
