<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CertificadoModel;
use App\Models\UsuarioModel;
use Dompdf\Dompdf;
use Throwable;

class CertificadosController extends BaseController
{
    public function solicitar()
    {
        try {
            $data = $this->request->getJSON(true);
            if (!$data) return $this->response->setJSON(['status'=>'error','message'=>'JSON inválido o vacío']);

            // Campos requeridos del payload
            $req = ['nombreSolicitante','cuiSolicitante','correo','telefono','nombreCertificado','cuiCertificado','tipoCertificacion'];
            foreach ($req as $k) {
                if (!isset($data[$k]) || trim($data[$k]) === '') {
                    return $this->response->setJSON(['status'=>'error','message'=>"Falta el campo: $k"]);
                }
            }

            // Tomar id_solicitante desde la sesión (ajusta la clave si usas otro nombre)
            $idSolicitante = session()->get('id_usuario');
            if (!$idSolicitante) {
                return $this->response->setJSON(['status'=>'error','message'=>'Usuario no autenticado (id_solicitante ausente en sesión)']);
            }

            // Validar que el usuario existe
            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->find($idSolicitante);
            if (!$usuario) {
                return $this->response->setJSON(['status'=>'error','message'=>"El id_solicitante=$idSolicitante no existe en usuarios"]);
            }

            // Insertar certificado
            $certModel = new CertificadoModel();
            $id = $certModel->insert([
                'id_solicitante'     => $idSolicitante,
                'nombre_solicitante' => $data['nombreSolicitante'],
                'cui_solicitante'    => $data['cuiSolicitante'],
                'correo'             => $data['correo'],
                'telefono'           => $data['telefono'],
                'nombre_certificado' => $data['nombreCertificado'],
                'cui_certificado'    => $data['cuiCertificado'],
                'tipo'               => $data['tipoCertificacion'],
                'costo'              => 15.00,
                'estado_pago'        => 'pendiente',
                'pdf_path'           => null
            ]);

            if (!$id) {
                return $this->response->setJSON(['status'=>'error','message'=>'No se pudo guardar en la BD','errors'=>$certModel->errors()]);
            }

            // Simular pago
            $certModel->update($id, ['estado_pago' => 'pagado']);

            // Verificar vista PDF
            if (!is_file(APPPATH.'Views/pdf/certificado.php')) {
                return $this->response->setJSON(['status'=>'error','message'=>'Vista PDF no encontrada en app/Views/pdf/certificado.php']);
            }

            // Render PDF
            $html = view('pdf/certificado', [
                'nombreSolicitante'  => $data['nombreSolicitante'],
                'cuiSolicitante'     => $data['cuiSolicitante'],
                'correo'             => $data['correo'],
                'telefono'           => $data['telefono'],
                'nombreCertificado'  => $data['nombreCertificado'],
                'cuiCertificado'     => $data['cuiCertificado'],
                'tipoCertificacion'  => $data['tipoCertificacion'],
                'costo'              => 'Q. 15.00'
            ]);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->render();
            $pdfOutput = $dompdf->output();

            // Guardar archivo
            $pdfDir = WRITEPATH . 'certificados/';
            if (!is_dir($pdfDir) && !mkdir($pdfDir, 0777, true)) {
                return $this->response->setJSON(['status'=>'error','message'=>'No se pudo crear writable/certificados']);
            }

            $pdfPath = $pdfDir . "certificado_{$id}.pdf";
            if (file_put_contents($pdfPath, $pdfOutput) === false) {
                return $this->response->setJSON(['status'=>'error','message'=>'No se pudo escribir el PDF en writable/certificados']);
            }

            // Guardar path en BD
            $certModel->update($id, ['pdf_path' => $pdfPath]);

            // Respuesta OK
            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'Certificado generado y pagado',
                'pdf_url' => base_url("api/certificados/descargar/{$id}"),
                'id'      => $id
            ]);
        } catch (Throwable $e) {
            log_message('error', 'Error en solicitar(): '.$e->getMessage());
            return $this->response->setJSON(['status'=>'error','message'=>'Excepción en servidor: '.$e->getMessage()]);
        }
    }

    public function descargar($id)
    {
        $certModel = new CertificadoModel();
        $row = $certModel->find($id);

        if (!$row) return $this->response->setJSON(['status'=>'error','message'=>'Registro no encontrado']);
        if (($row['estado_pago'] ?? '') !== 'pagado') return $this->response->setJSON(['status'=>'error','message'=>'Certificado no disponible (pago no confirmado)']);
        if (!is_file($row['pdf_path'])) return $this->response->setJSON(['status'=>'error','message'=>'Archivo PDF no existe en el servidor']);

        return $this->response->download($row['pdf_path'], null);
    }
}
