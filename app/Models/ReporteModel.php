<?php
namespace App\Models;
use CodeIgniter\Model;

class ReporteModel extends Model
{
    protected $table = 'reportes';
    protected $primaryKey = 'id_reporte';
    protected $allowedFields = ['nacimientos', 'dpi_emitidos', 'fecha'];
}
