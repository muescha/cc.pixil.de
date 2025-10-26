<?php
session_start(); // Session starten (falls noch nicht geschehen)

include_once 'includes/feature-flags.php';

// Beispiel: Aktuell eingeloggter Benutzer (z. B. aus Session)
$currentUser = isset($_SESSION['username']) ? $_SESSION['username'] : 'guest';
$currentUserId = isset($_SESSION['id']) ? $_SESSION['id'] : null;
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feature Flag Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .info-box {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #2196F3;
        }
        .feature-result {
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .feature-active {
            background: #c8e6c9;
            border-left: 4px solid #4CAF50;
        }
        .feature-inactive {
            background: #ffcdd2;
            border-left: 4px solid #f44336;
        }
    </style>
</head>
<body>
    <?php include 'includes/navigation_bar_component.php'; ?>
    
    <h1>Feature Flag Test</h1>
    
    <div class="info-box">
        <strong>Aktueller User:</strong> <?php echo htmlspecialchars($currentUser); ?><br>
        <strong>User ID:</strong> <?php echo $currentUserId ? htmlspecialchars((string)$currentUserId) : 'Keine'; ?>
    </div>

    <?php
    // Allgemein
    if (canUserDo("Test-Beta", $currentUser)) {
        echo '<div class="feature-result feature-active">';
        echo '<strong>✓ Beta Test aktiviert:</strong> User sieht alle Funktionen des Beta Tests';
        echo '</div>';
        echo '<script src="scripts/bildpopuptest.js"></script>';
    } else {
        echo '<div class="feature-result feature-inactive">';
        echo '<strong>✗ Beta Test deaktiviert:</strong> User sieht den Standard (keine Funktionen des Beta Tests)';
        echo '</div>';
        echo '<script src="scripts/bildpopup.js"></script>';
    }

    // Feature prüfen
    if (canUserDo("Feature-BildPopup-Fix", $currentUser)) {
        echo '<div class="feature-result feature-active">';
        echo '<strong>✓ Feature aktiviert:</strong> User sieht den Test (BildPopup-Fix)';
        echo '</div>';
        echo '<script src="scripts/bildpopuptest.js"></script>';
    } else {
        echo '<div class="feature-result feature-inactive">';
        echo '<strong>✗ Feature deaktiviert:</strong> User sieht den Standard (BildPopup)';
        echo '</div>';
        echo '<script src="scripts/bildpopup.js"></script>';
    }
    ?>

</body>
</html>
