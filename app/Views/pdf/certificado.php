<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; margin: 40px; font-size: 12pt; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
    .header img { height: 80px; }
    .titulo { font-size: 18pt; font-weight: bold; margin-top: 10px; }
    .subtitulo { font-size: 14pt; margin-top: 5px; }
    .campo { margin-bottom: 10px; }
    .campo strong { display: inline-block; width: 200px; }
    .footer { position: fixed; bottom: 20px; left: 0; right: 0; text-align: center; font-size: 10pt; border-top: 1px solid #000; padding-top: 5px; }
  </style>
</head>
<body>
  <div class="header">
    <img src="<?= base_url('img/renaplogo.png') ?>" alt="RENAP Logo">

    <div class="titulo">REPÚBLICA DE GUATEMALA</div>
    <div class="subtitulo">Registro Nacional de las Personas - RENAP</div>
    <div class="subtitulo">Certificación Electrónica</div>
  </div>

  <div>
    <div class="campo"><strong>Nombre Solicitante:</strong> <?= esc($nombreSolicitante) ?></div>
    <div class="campo"><strong>CUI Solicitante:</strong> <?= esc($cuiSolicitante) ?></div>
    <div class="campo"><strong>Correo:</strong> <?= esc($correo) ?></div>
    <div class="campo"><strong>Teléfono:</strong> <?= esc($telefono) ?></div>
    <hr>
    <div class="campo"><strong>Nombre Certificado:</strong> <?= esc($nombreCertificado) ?></div>
    <div class="campo"><strong>CUI Certificado:</strong> <?= esc($cuiCertificado) ?></div>
    <div class="campo"><strong>Tipo de Certificación:</strong> <?= ucfirst(esc($tipoCertificacion)) ?></div>
    <div class="campo"><strong>Costo:</strong> <?= esc($costo) ?></div>
    <p style="margin-top:30px;">Esta certificación se emite electrónicamente y tiene plena validez legal conforme a la Ley del RENAP.</p>
  </div>

  <div class="footer">
    © <?= date('Y') ?> RENAP Guatemala — Certificación Electrónica Generada Automáticamente
  </div>
</body>
</html>
