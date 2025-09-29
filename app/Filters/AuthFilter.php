<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Si no hay sesión iniciada, redirigir al login
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('iniciarsesion'))
                             ->with('error', 'Debes iniciar sesión primero');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada que hacer después
    }
}
