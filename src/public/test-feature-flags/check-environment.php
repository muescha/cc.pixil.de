<?php
// -----------------------------------------------------------
// check-environment.php
// -----------------------------------------------------------
// Prüft, ob der Server für das Feature-Flag-System bereit ist.
// Überprüft PHP-Version, Erweiterungen, Schreibrechte, JSON,
// Sessions, und Dateizugriff.
// -----------------------------------------------------------

$requirements = [
    'php_version' => [
        'required' => '7.4',
        'current'  => PHP_VERSION,
        'check'    => version_compare(PHP_VERSION, '7.4', '>='),
        'message'  => 'PHP-Version 7.4 oder höher erforderlich.'
    ],
    'json' => [
        'required' => true,
        'current'  => extension_loaded('json'),
        'check'    => extension_loaded('json'),
        'message'  => 'JSON-Erweiterung muss aktiviert sein.'
    ],
    'session' => [
        'required' => true,
        'current'  => extension_loaded('session'),
        'check'    => extension_loaded('session'),
        'message'  => 'Session-Erweiterung muss aktiviert sein.'
    ],
    'file_access' => [
        'required' => true,
        'current'  => is_readable(__DIR__) && is_writable(__DIR__),
        'check'    => is_readable(__DIR__) && is_writable(__DIR__),
        'message'  => 'Verzeichnis muss les- und schreibbar sein.'
    ],
    'feature_flags_json' => [
        'required' => true,
        'current'  => file_exists(__DIR__ . '/feature-flags.json') ? 'exists' : 'missing',
        'check'    => file_exists(__DIR__ . '/feature-flags.json'),
        'message'  => 'Datei feature-flags.json fehlt.'
    ]
];

// Hilfsfunktion für Anzeige
function statusIcon(bool $ok): string {
    return $ok
        ? '<span style="color:green;">✅</span>'
        : '<span style="color:red;">❌</span>';
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Systemcheck – Feature Flags</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            margin: 2em auto;
            max-width: 700px;
            background: #f6f8fa;
            padding: 1em 2em;
        }
        h1 { text-align: center; }
        table {
            border-collapse: collapse;
            width: 100%;
            background: white;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 0.6em;
        }
        th {
            background: #eee;
            text-align: left;
        }
        .ok { color: green; font-weight: bold; }
        .fail { color: red; font-weight: bold; }
        .hint {
            font-size: 0.9em;
            color: #555;
        }
        .footer {
            margin-top: 2em;
            font-size: 0.85em;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
<h1>🔍 Systemcheck: Feature-Flag in JSON-Files Umgebung</h1>

<table>
    <tr>
        <th>Komponente</th>
        <th>Erwartet</th>
        <th>Aktuell</th>
        <th>Status</th>
    </tr>
    <?php foreach ($requirements as $key => $req): ?>
        <tr>
            <td><?= htmlspecialchars($key) ?></td>
            <td><?= htmlspecialchars($req['required']) ?></td>
            <td><?= htmlspecialchars(is_bool($req['current']) ? ($req['current'] ? 'aktiv' : 'inaktiv') : $req['current']) ?></td>
            <td>
                <?= statusIcon($req['check']) ?>
                <?php if (!$req['check']): ?>
                    <div class="hint"><?= htmlspecialchars($req['message']) ?></div>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>🧪 Test: Dateioperationen</h2>
<?php
$testFile = __DIR__ . '/_test-write.txt';
$writeOk = @file_put_contents($testFile, "Test " . date('Y-m-d H:i:s'));
$readOk = $writeOk !== false && is_readable($testFile);
if ($readOk) {
    echo '<p class="ok">✅ Schreib- und Lesezugriff auf das Verzeichnis funktioniert.</p>';
    unlink($testFile);
} else {
    echo '<p class="fail">❌ Schreib- oder Lesezugriff auf dieses Verzeichnis ist eingeschränkt.</p>';
    echo '<p class="hint">Prüfe Dateiberechtigungen (z. B. chmod 664 oder chown www-data).</p>';
}
?>

<div class="footer">
    <p>💡 Wenn alle Punkte grün sind, ist dein Server bereit.</p>
    <p><small>Stand: <?= date('Y-m-d H:i:s') ?></small></p>
</div>
</body>
</html>
