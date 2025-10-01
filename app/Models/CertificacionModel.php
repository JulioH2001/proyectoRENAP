<?php
namespace App\Models;
use CodeIgniter\Model;

class CertificacionModel extends Model
   

{
    protected $table      = 'certificaciones_proceso';
    protected $primaryKey = 'id_certificacion';

    protected $allowedFields = [
        'nombre_certificado',
        'cui_certificado',
        'tipo',
        'costo',
        'fecha_emision',
        'pdf_path'
    ];

    protected $useTimestamps = false;
}
