<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ReposicionDpiModel;
use App\Models\CertificadoModel;
use App\Models\UsuarioModel;

class TramiteController extends BaseController
{
    public function index()
    {
        $tramites = [];

        // Reposiciones DPI
        $reposModel = new ReposicionDpiModel();
        $usuarioModel = new UsuarioModel();

        $repos = $reposModel->findAll();
        foreach ($repos as $r) {
            $u = $usuarioModel->find($r['id_usuario']);
            $tramites[] = [
                'id'              => $r['id_reposicion'],
                'ciudadano'       => $u ? $u['nombre'].' '.$u['primer_apellido'].' '.$u['segundo_apellido'] : 'N/A',
                'tipo'            => 'Reposición DPI',
                'fecha_solicitud' => $r['fecha_solicitud'],
                'estado'          => $r['estado']
            ];
        }

        // Certificados
        $certModel = new CertificadoModel();
        $certs = $certModel->findAll();
        foreach ($certs as $c) {
            $u = $usuarioModel->find($c['id_solicitante']);
            $tramites[] = [
                'id'              => $c['id_certificado'],
                'ciudadano'       => $u ? $u['nombre'].' '.$u['primer_apellido'].' '.$u['segundo_apellido'] : 'N/A',
                'tipo'            => 'Certificación',
                'fecha_solicitud' => $c['fecha_emision'],
                'estado'          => $c['estado_pago']
            ];
        }

        return $this->response->setJSON(['data' => $tramites]);
    }

    // 👇 Nuevo método para eliminar
    public function eliminar($id = null)
    {
        if (!$id) {
            return $this->response->setStatusCode(400)
                                  ->setJSON(['error' => 'ID requerido']);
        }

        // Primero intentamos eliminar en reposiciones
        $reposModel = new ReposicionDpiModel();
        if ($reposModel->find($id)) {
            if ($reposModel->delete($id)) {
                return $this->response->setJSON(['status' => 'ok']);
            }
            return $this->response->setStatusCode(500)->setJSON(['error' => 'No se pudo eliminar reposición']);
        }

        // Si no está en reposiciones, intentamos en certificados
        $certModel = new CertificadoModel();
        if ($certModel->find($id)) {
            if ($certModel->delete($id)) {
                return $this->response->setJSON(['status' => 'ok']);
            }
            return $this->response->setStatusCode(500)->setJSON(['error' => 'No se pudo eliminar certificado']);
        }

        return $this->response->setStatusCode(404)->setJSON(['error' => 'Trámite no encontrado']);
    }
}
