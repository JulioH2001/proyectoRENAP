<?php
namespace App\Controllers;

use App\Models\UsuarioModel;

class RegistroController extends BaseController
{
    public function form()
    {
        return view('inicio_registrarse.html'); // tu vista de registro
    }

    // Verificar si el CUI ya existe (AJAX)
    public function verificarCui()
    {
        $cui = $this->request->getPost('cui');
        $usuarioModel = new UsuarioModel();
        $existe = $usuarioModel->where('cui', $cui)->first();

        return $this->response->setJSON(['existe' => $existe ? true : false]);
    }

    // Guardar nuevo usuario
    public function guardar()
    {
        $usuarioModel = new UsuarioModel();

        $cui        = $this->request->getPost('cui');
        $nombres    = $this->request->getPost('nombres');
        $apellido1  = $this->request->getPost('apellido1');
        $apellido2  = $this->request->getPost('apellido2');
        $fecha      = $this->request->getPost('fecha');
        $email      = $this->request->getPost('email');
        $departamento = $this->request->getPost('departamento');
        $telefono   = $this->request->getPost('telefono');
        $direccion  = $this->request->getPost('direccion');
        $clave      = $this->request->getPost('clave');
        $genero           = $this->request->getPost('genero');
        $lugarNacimiento  = $this->request->getPost('lugar_nacimiento');


        // Verificar si ya existe el CUI
        if ($usuarioModel->where('cui', $cui)->first()) {
            return redirect()->back()->with('error', 'El CUI ya está registrado');
        }

        // Guardar con hash seguro
        $usuarioModel->insert([
    'cui'              => $cui,
    'nombre'           => $nombres,          // solo nombres
    'primer_apellido'  => $apellido1,        // primer apellido
    'segundo_apellido' => $apellido2,        // segundo apellido
    'correo'           => $email,
    'telefono'         => $telefono,
    'direccion'        => $direccion,
    'fecha_nacimiento' => $fecha,
    'genero'           => $genero,
    'lugar_nacimiento' => $lugarNacimiento,
    'departamento'     => $departamento,
    'rol'              => 'ciudadano',
    'password_hash'    => password_hash($clave, PASSWORD_DEFAULT)
]);



        return redirect()->to('/iniciarsesion')->with('success', '✅ Usuario registrado con éxito. Ahora puede iniciar sesión');

        if (!in_array($genero, ['M', 'F', 'Otro'])) {
    return redirect()->back()->with('error', 'Género inválido');
}

    }
}
