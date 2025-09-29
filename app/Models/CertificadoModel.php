<?php
namespace App\Models;

use CodeIgniter\Model;

class CertificadoModel extends Model
{
    protected $table      = 'certificados';
    protected $primaryKey = 'id_certificado';

    protected $allowedFields = [
        'id_solicitante',
        'nombre_solicitante',
        'cui_solicitante',
        'correo',
        'telefono',
        'nombre_certificado',
        'cui_certificado',
        'tipo',
        'costo',
        'estado_pago',
        'pdf_path'
    ];
}
