<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// -------------------------
// RUTAS ADMINISTRADOR
// -------------------------
$routes->get('/empleados', 'EmpleadoController::index');
$routes->post('/empleados/crear', 'EmpleadoController::crear');
$routes->get('/empleados/eliminar/(:num)', 'EmpleadoController::eliminar/$1');
$routes->get('/ciudadanos', 'UsuarioController::index');
$routes->get('/ciudadanos/(:num)', 'UsuarioController::detalle/$1');
$routes->get('/solicitudes', 'SolicitudController::index');
$routes->post('/solicitudes/aprobar/(:num)', 'SolicitudController::aprobar/$1');
$routes->post('/solicitudes/rechazar/(:num)', 'SolicitudController::rechazar/$1');

// -------------------------
// RUTAS OPERADOR
// -------------------------
$routes->get('/operador/ciudadanos', 'CiudadanoController::buscar');
$routes->get('/operador/tramites', 'TramiteController::index');
$routes->get('/operador/certificados', 'CertificadoController::index');

// -------------------------
// RUTAS CIUDADANO
// -------------------------
$routes->get('/mi/certificados', 'CertificadoController::misCertificados');
$routes->get('/mi/reposicion', 'ReposicionController::index');
$routes->get('/mi/notificaciones', 'NotificacionController::misNotificaciones');
$routes->get('/mi/datos', 'UsuarioController::misDatos');
$routes->get('/mi/historial', 'TramiteController::historial');
$routes->get('/mi/estado-dpi', 'EstadoDpiController::index');

// -------------------------
// RUTAS AUTENTICACIÓN
// -------------------------
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::login');
$routes->get('/registro', 'AuthController::registro');
$routes->post('/registro', 'AuthController::registro');
$routes->get('/recuperar', 'AuthController::recuperar');
$routes->post('/recuperar', 'AuthController::recuperar');
$routes->get('/logout', 'AuthController::logout');

// -------------------------
// RUTAS API
// -------------------------
$routes->group('api', function($routes) {
    $routes->get('solicitudes', 'Api\SolicitudController::index');
    $routes->post('solicitudes/aprobar/(:num)', 'Api\SolicitudController::aprobar/$1');
    $routes->post('solicitudes/rechazar/(:num)', 'Api\SolicitudController::rechazar/$1');
    $routes->delete('solicitudes/eliminar/(:num)', 'Api\SolicitudController::eliminar/$1');
});

// -------------------------
// RUTAS PARA ARCHIVOS HTML (opción 3)
// -------------------------
// Esto permite servir tus vistas .html sin renombrarlas a .php
$routes->get('(:any).html', function($page) {
    return view($page . '.html');
});

// Bloqueo DPI
$routes->get('api/bloqueos', 'Api\BloqueoController::index');
$routes->post('api/bloqueos/crear', 'Api\BloqueoController::crear');
$routes->delete('api/bloqueos/eliminar/(:num)', 'Api\BloqueoController::eliminar/$1');
$routes->get('api/usuarios', 'Api\UsuarioController::index');
$routes->get('api/reportes', 'Api\ReporteController::index');
$routes->put('api/usuarios/actualizar/(:num)', 'Api\UsuarioController::actualizar/$1');
$routes->get('api/usuarios', 'Api\UsuarioController::index');
$routes->put('api/usuarios/actualizar/(:num)', 'Api\UsuarioController::actualizar/$1');



// Pantalla de login

$routes->get('/iniciarsesion', 'AuthController::loginForm');
$routes->post('/iniciarsesion', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');


//filtro de protección
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/inicio-admin', function() {
        return view('inicio.html'); // admin
    });
    $routes->get('/inicio-operador', function() {
        return view('Index.html'); // operador
    });
    $routes->get('/inicio-ciudadano', function() {
        return view('inicio_ciudadano.html'); // ciudadano
    });
});

//FILTRO PARA CADA ROL
// Solo admin
$routes->get('/inicio-admin', function() {
    return view('inicio.html');
}, ['filter' => 'role:admin']);

// Solo operador
$routes->get('/inicio-operador', function() {
    return view('Index.html');
}, ['filter' => 'role:operador']);

// Solo ciudadano
$routes->get('/inicio-ciudadano', function() {
    return view('inicio_ciudadano.html');
}, ['filter' => 'role:ciudadano']);


// Registro de usuarios
$routes->get('/registrarse', 'RegistroController::form');
$routes->post('/registrarse', 'RegistroController::guardar');

// Verificación de CUI vía AJAX
$routes->post('/verificar-cui', 'RegistroController::verificarCui');



//RUTAS DE EMPLEADOS
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->get('empleados', 'EmpleadoController::index');              // listar
    $routes->post('empleados', 'EmpleadoController::create');            // crear
    $routes->put('empleados/(:num)', 'EmpleadoController::actualizar/$1'); // actualizar
    $routes->delete('empleados/(:num)', 'EmpleadoController::eliminar/$1'); // eliminar
});



//RUTA DE RECUPERACIÓN CONTRASEÑA
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->post('recuperar', 'AuthController::recuperar');   // solicitar reset
    $routes->post('restablecer', 'AuthController::restablecer'); // guardar nueva clave
});

// Rutas para certificados
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->post('certificados/solicitar', 'CertificadosController::solicitar');
    $routes->get('certificados/descargar/(:num)', 'CertificadosController::descargar/$1');
});

//solicitudes dpi ciudadno
$routes->group('api', function($routes) {
    $routes->post('reposicion/solicitar', 'Api\ReposicionDpiController::solicitar');
    $routes->get('reposicion/descargar/(:num)', 'Api\ReposicionDpiController::descargar/$1');
});


//perfil de ciudadano
$routes->group('api/perfil', function($routes) {
    $routes->get('datos', 'Api\PerfilController::datos');
    $routes->post('actualizar', 'Api\PerfilController::actualizar');
});



//historial tramites y lo de dpi
$routes->group('api', function($routes) {
    $routes->get('tramites/historial', 'Api\TramitesController::historial');
    $routes->get('perfil/estado-dpi', 'Api\PerfilController::estadoDpi');
});
