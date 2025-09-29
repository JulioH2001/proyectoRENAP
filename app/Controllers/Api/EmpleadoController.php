<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\PermisoModel;

class EmpleadoController extends BaseController
{
    public function index()
    {
        $usuarios = (new UsuarioModel())->getEmpleados();
        $permisosModel = new PermisoModel();

        foreach ($usuarios as &$u) {
            $p = $permisosModel->where('id_usuario', $u['id_usuario'])->first();
            if ($p) {
                // Castear a enteros para que frontend reciba 0/1 reales
                $u['permisos'] = [
                    'gestionar_usuarios'   => (int) $p['gestionar_usuarios'],
                    'gestionar_registros'  => (int) $p['gestionar_registros'],
                    'generar_certificados' => (int) $p['generar_certificados'],
                    'generar_reportes'     => (int) $p['generar_reportes'],
                    'exportar_reportes'    => (int) $p['exportar_reportes'],
                    'configurar_sistema'   => (int) $p['configurar_sistema'],
                    'realizar_busqueda'    => (int) $p['realizar_busqueda'],
                ];
            } else {
                $u['permisos'] = null;
            }
        }

        return $this->response->setJSON(['data' => $usuarios]);
    }

    public function actualizar($id)
    {
        $json = $this->request->getJSON(true);

        // 1. Actualizar rol
        $usuarioModel = new UsuarioModel();
        $usuarioModel->update($id, ['rol' => $json['rol']]);

        // 2. Convertir true/false a 0/1 de forma estricta
        $permisos = [
            'gestionar_usuarios'   => (isset($json['permisos']['gestionar_usuarios'])   && $json['permisos']['gestionar_usuarios'])   ? 1 : 0,
            'gestionar_registros'  => (isset($json['permisos']['gestionar_registros'])  && $json['permisos']['gestionar_registros'])  ? 1 : 0,
            'generar_certificados' => (isset($json['permisos']['generar_certificados']) && $json['permisos']['generar_certificados']) ? 1 : 0,
            'generar_reportes'     => (isset($json['permisos']['generar_reportes'])     && $json['permisos']['generar_reportes'])     ? 1 : 0,
            'exportar_reportes'    => (isset($json['permisos']['exportar_reportes'])    && $json['permisos']['exportar_reportes'])    ? 1 : 0,
            'configurar_sistema'   => (isset($json['permisos']['configurar_sistema'])   && $json['permisos']['configurar_sistema'])   ? 1 : 0,
            'realizar_busqueda'    => (isset($json['permisos']['realizar_busqueda'])    && $json['permisos']['realizar_busqueda'])    ? 1 : 0,
        ];

        // 3. Insertar o actualizar fila de permisos
        $permisoModel = new PermisoModel();
        $fila = $permisoModel->where('id_usuario', $id)->first();

        if ($fila) {
            $permisoModel->update($fila['id_permiso'], $permisos);
        } else {
            $permisos['id_usuario'] = $id;
            $permisoModel->insert($permisos);
        }

        return $this->response->setJSON(['status' => 'ok']);
    }

    public function eliminar($id)
    {
        $usuarioModel = new UsuarioModel();
        $usuarioModel->delete($id);

        // También eliminamos permisos asociados
        $permisoModel = new PermisoModel();
        $permisoModel->where('id_usuario', $id)->delete();

        return $this->response->setJSON(['status' => 'ok']);
    }

    public function create()
    {
        $data = $this->request->getJSON(true); // recibe JSON del fetch

        // Validaciones básicas
        if (empty($data['cui']) || empty($data['nombre']) || empty($data['primer_apellido']) ||
            empty($data['correo']) || empty($data['clave']) || empty($data['rol'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Faltan campos obligatorios'
            ]);
        }

        $usuarioModel = new UsuarioModel();

        try {
            $usuarioModel->insert([
                'cui'              => $data['cui'],
                'nombre'           => $data['nombre'],
                'primer_apellido'  => $data['primer_apellido'],
                'segundo_apellido' => $data['segundo_apellido'] ?? null,
                'correo'           => $data['correo'],
                'telefono'         => $data['telefono'] ?? null,
                'direccion'        => $data['direccion'] ?? null,
                'fecha_nacimiento' => $data['fecha_nacimiento'],
                'genero'           => $data['genero'],
                'lugar_nacimiento' => $data['lugar_nacimiento'],
                'departamento'     => $data['departamento'],
                'rol'              => $data['rol'], // admin u operador
                'password_hash'    => password_hash($data['clave'], PASSWORD_DEFAULT)
            ]);

            return $this->response->setJSON(['status' => 'ok']);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}



