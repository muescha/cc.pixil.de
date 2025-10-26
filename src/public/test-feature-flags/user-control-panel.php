<?php
session_start();

// User-Liste definieren
$users = [
    123 => 'muescha',
    234 => 'admin',
    452 => 'maurice',
    653 => 'marc',
    456 => 'fehlermeldungUser',
    454 => 'normalerUser',
];

// Logout verarbeiten
if (isset($_GET['logout'])) {
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    header('Location: user-control-panel.php');
    exit;
}

// User-Wechsel verarbeiten
if (isset($_GET['user_id'])) {
    $userId = (int)$_GET['user_id'];
    
    if (isset($users[$userId])) {
        $_SESSION['id'] = $userId;
        $_SESSION['username'] = $users[$userId];
        header('Location: user-control-panel.php');
        exit;
    }
}

// Aktueller User
$currentUserId = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$currentUsername = isset($_SESSION['username']) ? $_SESSION['username'] : 'Kein User ausgewählt';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Control</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .user-list { list-style: none; padding: 0; }
        .user-list li { 
            padding: 10px; 
            margin: 5px 0; 
            background: #f0f0f0; 
            cursor: pointer;
            border-radius: 5px;
        }
        .user-list li:hover { background: #e0e0e0; }
        .user-list li.active { background: #4CAF50; color: white; font-weight: bold; }
        .current-user { 
            padding: 15px; 
            background: #2196F3; 
            color: white; 
            border-radius: 5px; 
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logout-btn {
            background: #f44336;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 14px;
        }
        .logout-btn:hover {
            background: #d32f2f;
        }
    </style>
</head>
<body>
    <?php include 'includes/navigation_bar_component.php'; ?>
    
    <h1>User Control Panel</h1>
    
    <div class="current-user">
        <div>
            <strong>Aktueller User:</strong> <?php echo htmlspecialchars($currentUsername); ?>
            <?php if ($currentUserId): ?>
                (ID: <?php echo $currentUserId; ?>)
            <?php endif; ?>
        </div>
        <?php if ($currentUserId): ?>
            <a href="?logout=1" class="logout-btn">Session löschen</a>
        <?php endif; ?>
    </div>

    <h2>User auswählen:</h2>
    <ul class="user-list">
        <?php foreach ($users as $userId => $username): ?>
            <li class="<?php echo ($userId === $currentUserId) ? 'active' : ''; ?>">
                <a href="?user_id=<?php echo $userId; ?>" style="text-decoration: none; color: inherit; display: block;">
                    <?php echo htmlspecialchars($username); ?> (ID: <?php echo $userId; ?>)
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
