<?php
// navigation.php
// Gemeinsame Navigation für alle Test-Seiten
?>
<style>
    .nav-bar {
        background: #333;
        padding: 0;
        margin: -20px -20px 20px -20px;
        border-radius: 5px;
        overflow: hidden;
    }
    .nav-bar ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
    }
    .nav-bar li {
        margin: 0;
    }
    .nav-bar a {
        display: block;
        color: white;
        text-decoration: none;
        padding: 15px 20px;
        transition: background 0.3s;
    }
    .nav-bar a:hover {
        background: #555;
    }
    .nav-bar a.active {
        background: #2196F3;
    }
</style>

<nav class="nav-bar">
    <ul>
        <li><a href="/test-feature-flags/user-control-panel.php"
               class="<?php echo basename($_SERVER['PHP_SELF']) === 'user-control-panel.php' ? 'active' : ''; ?>">
            User Control
        </a></li>
        <li><a href="/test-feature-flags/beispiel.php"
               class="<?php echo basename($_SERVER['PHP_SELF']) === 'beispiel.php' ? 'active' : ''; ?>">
            Beispiel
        </a></li>
        <li><a href="/test-feature-flags/nachrichten.php"
               class="<?php echo basename($_SERVER['PHP_SELF']) === 'nachrichten.php' ? 'active' : ''; ?>">
            Nachrichten
        </a></li>
        <li><a href="/test-feature-flags/nachrichten_cando.php"
               class="<?php echo basename($_SERVER['PHP_SELF']) === 'nachrichten_cando.php' ? 'active' : ''; ?>">
            Nachrichten (CanDo)
        </a></li>
    </ul>
</nav>
