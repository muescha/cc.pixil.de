<?php
// feature-flags.php
// -----------------------------------------------------------
// Diese Datei regelt, welche Benutzer welche Features sehen dürfen.
// Idee: Feature Flags – für gezieltes Testen neuer Funktionen
// -----------------------------------------------------------

/**
 * Prüft, ob ein bestimmter Benutzer ein bestimmtes Feature nutzen darf.
 *
 * @param string $feature     Name des Features (z. B. "Feature-BildPopup-Fix")
 * @param string $currentUser Aktuell angemeldeter Benutzername
 * @return bool               true, wenn Benutzer Zugriff hat
 */
function canUserDo($feature, $currentUser)
{
    // --- 1. Globales Test-Feature: für alle sichtbar
    if ($feature === "test-feature-fuer-alle") {
        return true;
    }

    // --- 2. Beta-Tester: Benutzer, die alles sehen dürfen
    $betaTester = array("maurice", "marc");
    if (in_array($currentUser, $betaTester, true)) {
        return true;
    }

    // --- 3. Einzelne Features nur für bestimmte User
    $BildPopupTester = array("muescha", "fehlermeldungUser");
    if (in_array($currentUser, $BildPopupTester, true) && $feature === "Feature-BildPopup-Fix") {
        return true;
    }

    // --- 4. Standardfall: kein Zugriff ---
    return false;
}
