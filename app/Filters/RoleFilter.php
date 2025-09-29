<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si hay sesión
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('iniciarsesion'))
                             ->with('error', 'Debes iniciar sesión primero');
        }

        // Verificar rol
        $rolUsuario = session()->get('rol');
        $rolesPermitidos = $arguments ?? [];

        if (! in_array($rolUsuario, $rolesPermitidos)) {
            return redirect()->to(site_url('iniciarsesion'))
                             ->with('error', 'No tienes permiso para acceder a esta sección');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada que hacer después
    }
}
