<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    // Paso 1: Solicitar recuperación
    public function recuperar()
    {
        $data = $this->request->getJSON(true);
        $cui = $data['cui'] ?? null;
        $email = $data['email'] ?? null;

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('cui', $cui)
                                ->where('correo', $email)
                                ->first();

        if (!$usuario) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'CUI o correo no encontrados'
            ]);
        }

        // Generar token único
        $token = bin2hex(random_bytes(16));

        // Guardar en tabla recuperar_clave
        $db = db_connect();
        $db->table('recuperar_clave')->insert([
            'id_usuario' => $usuario['id_usuario'],
            'token' => $token
        ]);

        // Cargar plantilla HTML
        $htmlMessage = file_get_contents(APPPATH . 'Views/emails/recuperar_password.html');
        $htmlMessage = str_replace('{NOMBRE}', $usuario['nombre'], $htmlMessage);
        $htmlMessage = str_replace('{ENLACE}', base_url("restablecer.html?token=$token"), $htmlMessage);

        // Enviar correo
        $emailService = \Config\Services::email();
        $emailService->setFrom('tu_correo@gmail.com', 'RENAP Guatemala');
        $emailService->setTo($usuario['correo']);
        $emailService->setSubject('Recuperar contraseña');
        $emailService->setMessage($htmlMessage);
        $emailService->setMailType('html');

        if ($emailService->send()) {
            return $this->response->setJSON([
                'status' => 'ok',
                'message' => 'Se envió un enlace de recuperación al correo registrado'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se pudo enviar el correo. Verifica la configuración SMTP.'
            ]);
        }
    }

    // Paso 2: Restablecer contraseña
    public function restablecer()
    {
        $data = $this->request->getJSON(true);
        $token = $data['token'] ?? null;
        $nuevaClave = $data['clave'] ?? null;

        if (!$token || !$nuevaClave) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Datos incompletos'
            ]);
        }

        $db = db_connect();
        $row = $db->table('recuperar_clave')->where('token', $token)->get()->getRowArray();

        if (!$row) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Token inválido o expirado'
            ]);
        }

        // Actualizar contraseña en usuarios
        $usuarioModel = new UsuarioModel();
        $usuarioModel->update($row['id_usuario'], [
            'password_hash' => password_hash($nuevaClave, PASSWORD_DEFAULT)
        ]);

        // Eliminar token usado
        $db->table('recuperar_clave')->where('token', $token)->delete();

        return $this->response->setJSON([
            'status' => 'ok',
            'message' => 'Contraseña restablecida correctamente'
        ]);
    }
}
