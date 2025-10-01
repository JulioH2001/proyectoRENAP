<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CertificacionModel;
use Dompdf\Dompdf;

class CertificacionController extends BaseController
{
    public function generar()
    {
        $data = $this->request->getJSON(true);
        if (!$data) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Datos inválidos'
            ]);
        }

        $model = new CertificacionModel();
        $id = $model->insert([
            'nombre_certificado' => $data['nombreCertificado'],
            'cui_certificado'    => $data['cuiCertificado'],
            'tipo'               => $data['tipoCertificacion'],
            'costo'              => 15.00,
            'fecha_emision'      => date('Y-m-d H:i:s')
        ]);

        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se pudo guardar en la BD'
            ]);
        }

        // Renderizar PDF
        $html = view('pdf/certificado_simple', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $pdfOutput = $dompdf->output();

        $pdfDir = WRITEPATH . 'certificados/';
        if (!is_dir($pdfDir)) mkdir($pdfDir, 0777, true);

        $pdfPath = $pdfDir . "certificado_{$id}.pdf";
        file_put_contents($pdfPath, $pdfOutput);

        $model->update($id, ['pdf_path' => $pdfPath]);

        return $this->response->setJSON([
            'status'  => 'ok',
            'message' => 'Certificación generada',
            'pdf_url' => base_url("api/certificacion/descargar/$id")
        ]);
    }

    public function descargar($id)
    {
        $model = new CertificacionModel();
        $row = $model->find($id);

        if (!$row || !is_file($row['pdf_path'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Archivo no encontrado'
            ]);
        }

        return $this->response->download($row['pdf_path'], null);
    }
}
