<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    h2 { text-align:center; }
    .box { border:1px solid #000; padding:10px; margin-bottom:10px; }
    .field { margin-bottom:6px; }
  </style>
</head>
<body>
  <h2>RENAP - Boleta de Reposición de DPI</h2>
  <p><strong>Fecha de solicitud:</strong> <?= esc($fecha) ?></p>
  <div class="box">
    <div class="field"><strong>Nombre:</strong> <?= esc($nombre) ?></div>
    <div class="field"><strong>CUI:</strong> <?= esc($cui) ?></div>
    <div class="field"><strong>Correo:</strong> <?= esc($correo) ?></div>
    <div class="field"><strong>Teléfono:</strong> <?= esc($telefono) ?></div>
    <div class="field"><strong>Motivo:</strong> <?= esc($motivo) ?></div>
    <div class="field"><strong>Departamento:</strong> <?= esc($departamento) ?></div>
    <div class="field"><strong>Sede:</strong> <?= esc($sede) ?></div>
    <div class="field"><strong>Fecha estimada de entrega:</strong> <?= esc($fechaEntrega) ?></div>
  </div>
  <p>⚠️ Este comprobante debe imprimirse o presentarse en la sede indicada al momento de recoger su DPI.</p>
</body>
</html>
