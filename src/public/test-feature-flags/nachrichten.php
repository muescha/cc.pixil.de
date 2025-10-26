<?php
session_start(); // Session starten (falls noch nicht geschehen)

// ===== Setup Mockdata =====
// Mock: Session-Daten (falls nicht vorhanden)
if (!isset($_SESSION['id'])) {
    $_SESSION['id'] = 0;
}
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'guest';
}

// Mock-Nachricht erstellen
$nachricht = [
    'sendfrom' => $_SESSION['username'],
    'attachments' => '1-2-3-4-5-6-7-8-9-10'
];

// Mock: Andere Benutzer-Daten
$userid = 123; // ID des anderen Users

$bildnamen = [
    'bild1name' => 'mountain',
    'bild2name' => 'portrait',
    'bild3name' => 'city',
    'bild4name' => 'coffee',
    'bild5name' => 'car',
    'bild6name' => 'forest',
    'bild7name' => 'desk',
    'bild8name' => 'street',
    'bild9name' => 'beach',
    'bild10name' => 'cat'
];

// Mock: Eigene Bildnamen
$eig_bildnamen = [
    'bild1name' => 'mountain',
    'bild2name' => 'portrait',
    'bild3name' => 'city',
    'bild4name' => 'coffee',
    'bild5name' => 'car',
    'bild6name' => 'forest',
    'bild7name' => 'desk',
    'bild8name' => 'street',
    'bild9name' => 'beach',
    'bild10name' => 'cat'
];

// Mock: Profilbild-Pfad
$profilbildpfad = '/var/www/html/test-feature-flags/images/';

// Mock: FSK-Einstellung
if (!isset($_SESSION['nopic'])) {
    $_SESSION['nopic'] = 0; // 0 = Bilder sichtbar, 1 = Bilder verborgen
}
// ===== Ende Setup Mockdata =====

// ===== FeatureFlag =====
// --- nix ---
// ===== FeatureFlag =====

function canUserSeeFskPics(): bool {
    return empty($_SESSION['nopic']) || $_SESSION['nopic'] != 1;
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nachrichten (CanDo)</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; }
        .info-box {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #2196F3;
        }
    </style>
    <script src="scripts/bildpopup.js"></script>
</head>
<body>
<?php include 'includes/navigation_bar_component.php'; ?>

<h1>Nachrichten mit Feature Flag (CanDo)</h1>

<div class="info-box">
    <strong>Aktueller User:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?><br>
    <strong>User ID:</strong> <?php echo htmlspecialchars((string)$_SESSION['id']); ?><br>
    <strong>Feature-Status:</strong>
    <!--    // ===== FeatureFlag =====-->
    <span style="color: red;">✗ Keine Feature Flag abfrage implementiert</span>
    <!--    // ===== FeatureFlag =====-->
</div>

<table>
    <?php

    // --- End mockup code
    // --- Start normal code

    // --- Determine sender/receiver image data ---
    if ($nachricht['sendfrom'] === $_SESSION['username']) {
        $userbildid    = $_SESSION['id'];
        $userbildnamen = $eig_bildnamen;
    } else {
        $userbildid    = $userid;
        $userbildnamen = $bildnamen;
    }

    // --- Prepare attachments ---
    $attachments = explode('-', $nachricht['attachments']);
    $bildzahl    = count($attachments);

    echo '<tr><td colspan="3">';

    // ===== FeatureFlag =====
    // --- nix ---
    // ===== FeatureFlag =====

    echo '<table align="center" cellspacing="5" cellpadding="0" border="0">';
    echo '<tr><td colspan="5"><b>Bildanhang:</b></td></tr>';

    $bildzaehler = 0;
    $bilderGefunden = false;

    // mock
    $userbildid = 123; // ID des anderen Users
    // mock

    echo '<tr>';

    foreach ($attachments as $attachment_id) {
        $bildname = $userbildnamen['bild' . $attachment_id . 'name'] ?? '';

        // --- Centralized filenames ---
        $imageFileName = "{$userbildid}{$bildname}{$attachment_id}.jpg";
        $thumbFileName = "{$userbildid}{$bildname}_{$attachment_id}_t2.jpg";

        // --- Paths & URLs ---
        $imageUploadPath = $profilbildpfad . $imageFileName;
        $imageUrl        = "/test-feature-flags/images/{$imageFileName}";
        $thumbUrl        = "/test-feature-flags/images/{$thumbFileName}";

        echo 'image path: ' . $imageUploadPath . '<br>';
        if (!file_exists($imageUploadPath)) {
            // Mock: Zeige Platzhalter, da keine echten Bilder vorhanden
            echo '<td>';
            echo '<div style="width: 50px; height: 50px; background: #ddd; border: 1px solid #999; display: flex; align-items: center; justify-content: center; font-size: 10px;">';
            echo 'Mock<br>Bild ' . htmlspecialchars($attachment_id);
            echo '</div>';
            echo '</td>';

            $bildzaehler++;
            if ($bildzaehler % 5 === 0) {
                echo '</tr><tr>';
            }
            continue;
        }

        $bilderGefunden = true;
        $bildzaehler++;

        [$breite, $hoehe] = getimagesize($imageUploadPath);

        // --- OnClick handler as variable ---
        $onClickCode = "BildPopup(this.href, 'Bild', '{$breite}', '{$hoehe}'); return false;";

        // ===== FeatureFlag =====
        // --- nix ---
        // ===== FeatureFlag =====

        echo '<td>';

        if (canUserSeeFskPics()) {
            echo '<a id="a2" href="' . $imageUrl . '" onClick="' . $onClickCode . '">';
            echo '<img src="' . $thumbUrl . '" width="50" height="50" border="1">';
            echo '</a>';
        } else {
            echo '<img src="images/eye_small.jpg" border="1">';
        }

        echo '</td>';

        // New table row after every 5 images
        if ($bildzaehler % 5 === 0) {
            echo '</tr><tr>';
        }
    }

    // --- If no images found ---
    if (!$bilderGefunden && $bildzaehler === 0) {
        echo '<td>Leider hat ' . htmlspecialchars($nachricht['sendfrom']) . ' seine Bilder gelöscht.</td>';
    }

    echo '</tr>';
    echo '</table>';
    echo '</td></tr>';
    ?>
</table>
</body>
</html>
