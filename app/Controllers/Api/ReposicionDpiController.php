<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ReposicionDpiModel;
use App\Models\SolicitudModel;

class ReposicionDpiController extends BaseController
{
    // Guardar solicitud (sin PDF)
    public function solicitar()
    {
        $data = $this->request->getJSON(true);
        if (!$data) {
            return $this->response->setJSON(['status'=>'error','message'=>'Datos inválidos']);
        }

        $idUsuario = session()->get('id_usuario');

        $model = new ReposicionDpiModel();

        // Insertar solicitud en reposiciones_dpi con estado pendiente
        $id = $model->insert([
            'id_usuario'      => $idUsuario,
            'motivo'          => $data['motivo'],
            'tipo_entrega'    => $data['entrega'],
            'departamento'    => $data['departamento'],
            'numero_recibo'   => $data['recibo'],
            'numero_nota'     => $data['nota'],
            'total'           => 100.00,
            'estado'          => 'pendiente',
            'fecha_solicitud' => date('Y-m-d H:i:s')
        ]);

        if (!$id) {
            return $this->response->setJSON(['status'=>'error','message'=>'No se pudo guardar la solicitud']);
        }

        // 👇 Insertar también en la tabla solicitudes_dpi para que el admin lo vea
        $solicitudModel = new SolicitudModel();
        $solicitudModel->insert([
            'id_usuario'      => $idUsuario,
            'fecha_solicitud' => date('Y-m-d H:i:s'),
            'estado'          => 'Pendiente'
        ]);

        // Respuesta simple sin PDF
        return $this->response->setJSON([
            'status' => 'ok',
            'id'     => $id
        ]);
    }
}
