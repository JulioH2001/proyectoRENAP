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

    // Si viene contraseña en el JSON
    if (!empty($datos['password'])) {
        $datos['password_hash'] = password_hash($datos['password'], PASSWORD_DEFAULT);
        unset($datos['password']); // eliminamos el campo plano
    } else {
        // Si no viene, no modificar la contraseña
        unset($datos['password']);
    }


    if ($model->update($id, $datos)) {
        return $this->response->setJSON(['status' => 'ok']);
    }

    return $this->response->setStatusCode(400)->setJSON(['status' => 'error']);


}

    public function ciudadanos()
{
    $usuarioModel = new \App\Models\UsuarioModel();
    $bloqueoModel = new \App\Models\BloqueoModel();

    // Solo rol ciudadano
    $usuarios = $usuarioModel->where('rol', 'ciudadano')->findAll();

    $data = [];
    foreach ($usuarios as $u) {
        $bloqueado = $bloqueoModel->where('id_usuario', $u['id_usuario'])->first() ? true : false;
        $data[] = [
            'nombre'           => $u['nombre'],
            'primer_apellido'  => $u['primer_apellido'],
            'segundo_apellido' => $u['segundo_apellido'],
            'cui'              => $u['cui'],
            'correo'           => $u['correo'],
            'fecha_nacimiento' => $u['fecha_nacimiento'],
            'direccion'        => $u['direccion'],
            'genero'           => $u['genero'],
            'departamento'     => $u['departamento'],
            'telefono'         => $u['telefono'],
            'lugar_nacimiento' => $u['lugar_nacimiento'],
            'bloqueado'        => $bloqueado
        ];
    }

    return $this->response->setJSON(['data' => $data]);
}


}
