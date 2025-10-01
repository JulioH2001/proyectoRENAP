<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; font-size: 14px; }
    h2 { text-align: center; }
    .field { margin-bottom: 10px; }
    .label { font-weight: bold; }
  </style>
</head>
<body>
  <h2>Certificación RENAP</h2>
  <p class="field"><span class="label">Nombre:</span> <?= esc($nombreCertificado) ?></p>
  <p class="field"><span class="label">CUI:</span> <?= esc($cuiCertificado) ?></p>
  <p class="field"><span class="label">Tipo de Certificación:</span> <?= esc($tipoCertificacion) ?></p>
  <p class="field"><span class="label">Costo:</span> <?= esc($costo) ?></p>
  <p class="field"><span class="label">Fecha de emisión:</span> <?= date('d/m/Y H:i') ?></p>
</body>
</html>
