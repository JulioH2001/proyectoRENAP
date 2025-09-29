<?php
namespace App\Controllers\Api;
use App\Controllers\BaseController;
use App\Models\ReporteModel;

class ReporteController extends BaseController
{
    public function index()
    {
        $model = new ReporteModel();
        $data = $model->orderBy('fecha', 'DESC')->findAll();
        return $this->response->setJSON(['data' => $data]);
    }
}
