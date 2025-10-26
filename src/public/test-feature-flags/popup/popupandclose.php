<?php
declare(strict_types=1);

// popupandclose.php
// Generiert das HTML für das Bild-Popup
// Hintergrundfarbe wird über GET-Parameter 'bgcolor' übergeben

// Eingehende Parameter absichern
$mypic   = isset($_GET['mypic'])   ? htmlspecialchars((string)$_GET['mypic'], ENT_QUOTES)   : '';
$myname  = isset($_GET['myname'])  ? htmlspecialchars((string)$_GET['myname'], ENT_QUOTES)  : '';
$breite  = isset($_GET['breite'])  ? (int)$_GET['breite']  : 800;
$hoehe   = isset($_GET['hoehe'])   ? (int)$_GET['hoehe']   : 600;
$bgcolor = isset($_GET['bgcolor']) ? htmlspecialchars((string)$_GET['bgcolor'], ENT_QUOTES) : '6699cc';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title><?= $myname ?></title>
    <style>
        * {
            overflow: hidden;
            text-align: center;
            background-color: #<?= $bgcolor ?>;
            margin: 0;
            padding: 0;
        }
        .imgbox {
            display: grid;
            height: 100%;
        }
        .center-fit {
            cursor: pointer;
            width: auto;
            height: 100vh;
            display: block;
            margin: 0 auto;
            border: none;
        }
    </style>
</head>
<body>
<div class="imgbox">
    <a href="#" title="schliessen/close" onclick="self.close();">
        <img class="center-fit"
             src="<?= $mypic ?>"
             width="<?= $breite ?>"
             height="<?= $hoehe ?>"
             alt="<?= $myname ?>">
    </a>
</div>
</body>
</html>
