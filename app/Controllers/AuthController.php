<?php
namespace App\Controllers;

use App\Models\UsuarioModel;
use Config\Database;

class AuthController extends BaseController
{
    public function loginForm()
    {
        // Muestra tu vista iniciarsesion.html
        return view('iniciarsesion.html');
    }

    public function login()
    {
        $cui   = $this->request->getPost('cui');
        $clave = $this->request->getPost('clave');
        $rolSeleccionado = $this->request->getPost('rol');

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('cui', $cui)->first();

        $ip = $this->request->getIPAddress();
        $db = Database::connect();

        if ($usuario) {
            // ⚠️ Comparación en texto plano (no usa password_verify)
            if (password_verify($clave, $usuario['password_hash'])) {
                // Validar rol
                if ($usuario['rol'] !== $rolSeleccionado) {
                    $db->table('log_sesiones')->insert([
                        'id_usuario' => $usuario['id_usuario'],
                        'ip' => $ip,
                        'exitoso' => false
                    ]);
                    return redirect()->back()->with('error', 'El rol seleccionado no coincide con su cuenta');
                }

                // Guardar sesión
                // Guardar sesión con más datos
session()->set([
    'id_usuario'       => $usuario['id_usuario'],
    'nombre'           => $usuario['nombre'],
    'primer_apellido'  => $usuario['primer_apellido'],
    'segundo_apellido' => $usuario['segundo_apellido'],
    'cui'              => $usuario['cui'],
    'correo'           => $usuario['correo'],
    'telefono'         => $usuario['telefono'],
    'rol'              => $usuario['rol'],
    'logged_in'        => true
]);


                // Log de sesión exitosa
                $db->table('log_sesiones')->insert([
                    'id_usuario' => $usuario['id_usuario'],
                    'ip' => $ip,
                    'exitoso' => true
                ]);

                // Redirigir según rol
                switch ($usuario['rol']) {
                    case 'admin':
                        return view('inicio.html'); // Vista administrador
                    case 'operador':
                        return view('Index.html'); // Vista operador
                    case 'ciudadano':
                        return view('ciudadano_inicio.html'); // Vista ciudadano
                    default:
                        return redirect()->to('/iniciarsesion.html')->with('error', 'Rol no válido');
                }
            } else {
                // Contraseña incorrecta
                $db->table('log_sesiones')->insert([
                    'id_usuario' => $usuario['id_usuario'],
                    'ip' => $ip,
                    'exitoso' => false
                ]);
                return redirect()->back()->with('error', 'Clave incorrecta');
            }
        } else {
            return redirect()->back()->with('error', 'CUI no encontrado');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/iniciarsesion')->with('success', 'Sesión cerrada correctamente');
    }
}
