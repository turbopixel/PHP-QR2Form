<?php

use Zxing\QrReader;

ini_set("display_errors", true);
error_reporting(E_ALL);

require __DIR__ . "/vendor/autoload.php";

if (isset($_POST["send"])) {


  if ($_FILES["datei"]["type"] !== "image/png" && $_FILES["datei"]["type"] !== "image/jpg") {
    header("Location: ./index.php");
    exit;
  }

  $dateiname = $_FILES["datei"]["name"] ?? "";
  $dateipfad = $_FILES["datei"]["tmp_name"] ?? "";

  if (empty($dateiname)) {
    header("Location: ./index.php");
    exit;
  }

  $qrcode    = new QrReader($dateipfad);
  $qrcontent = $qrcode->text();
  $parseQr   = explode(";", $qrcontent);
  $formData  = [];

  //  var_dump($parseQr);

  foreach ($parseQr as $field) {
    $split                 = explode(":", $field);
    $formData[ $split[0] ] = $split[1];
  }

  //  var_dump($formData);
}


?>
<html lang="de">
<head lang="de">
  <title>QRForm</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>

<div class="container">
  <h1>QR Formular Beispiel</h1>

  <form action="./index.php" method="post" enctype="multipart/form-data">

    <input type="file" name="datei" placeholder="QR Code auswählen">

    <input type="submit" name="send" value="Hochladen">

  </form>

  <?php
  if (!empty($formData)) {
    ?>

    <hr>
    <h2>QR Inhalt:</h2>

    <pre><?= $qrcontent ?? "" ?></pre>

    <small>Vorname</small>
    <br/>
    <input type="text" value="<?= $formData["VORNAME"] ?>"><br/>
    <small>Nachname</small>
    <br/>
    <input type="text" value="<?= $formData["NAME"] ?>"><br/>
    <small>Alter</small>
    <br/>
    <input type="text" value="<?= $formData["ALTER"] ?>"><br/>
    <small>E-Mail</small>
    <br/>
    <input type="text" value="<?= $formData["EMAIL"] ?>"><br/>

    <?php
  }
  ?>

</div>

</body>
</html>