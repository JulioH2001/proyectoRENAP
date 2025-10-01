<?php
namespace App\Controllers\Api;
use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class AdminController extends BaseController
{
    public function perfil()
    {
        $session = session();
        $id   = $session->get('id_usuario');   // 👈 debe coincidir con lo que guardas en login
        $rol  = $session->get('rol');

        // Validar sesión y rol
        if (!$id || strtolower($rol) !== 'admin') {
            return $this->response->setStatusCode(403)
                                  ->setJSON(['error' => 'No autorizado']);
        }

        $model = new UsuarioModel();
        $admin = $model->find($id);

        if (!$admin) {
            return $this->response->setStatusCode(404)
                                  ->setJSON(['error' => 'No encontrado']);
        }

        unset($admin['password_hash']); // nunca enviar hash
        return $this->response->setJSON(['data' => $admin]);
    }

    public function actualizarPerfil()
    {
        $session = session();
        $id   = $session->get('id_usuario');
        $rol  = $session->get('rol');

        if (!$id || strtolower($rol) !== 'admin') {
            return $this->response->setStatusCode(403)
                                  ->setJSON(['error' => 'No autorizado']);
        }

        $model  = new UsuarioModel();
        $actual = $model->find($id);

        if (!$actual) {
            return $this->response->setStatusCode(404)
                                  ->setJSON(['error' => 'No encontrado']);
        }

        $json = $this->request->getJSON(true);

        // Validar contraseña actual
        if (empty($json['actualPassword']) || 
            !password_verify($json['actualPassword'], $actual['password_hash'])) {
            return $this->response->setStatusCode(400)
                                  ->setJSON(['error' => 'Contraseña actual incorrecta']);
        }

        // Construir datos a actualizar
        $datos = [];
        foreach ([
            'nombre','primer_apellido','segundo_apellido',
            'correo','telefono','direccion',
            'fecha_nacimiento','genero','lugar_nacimiento',
            'departamento'
        ] as $campo) {
            if (array_key_exists($campo, $json)) {
                $datos[$campo] = $json[$campo];
            }
        }

        // Nueva contraseña opcional
        if (!empty($json['nuevaPassword'])) {
            $datos['password_hash'] = password_hash($json['nuevaPassword'], PASSWORD_DEFAULT);
        }

        if (empty($datos)) {
            return $this->response->setStatusCode(400)
                                  ->setJSON(['error' => 'Sin cambios']);
        }

        if ($model->update($id, $datos)) {
            return $this->response->setJSON([
                'status' => 'ok',
                'data'   => $model->find($id) // devolver datos actualizados
            ]);
        }

        return $this->response->setStatusCode(400)
                              ->setJSON(['error' => 'Error al actualizar']);
    }
}
