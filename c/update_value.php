<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sessionID = $_POST['sessionID'] ?? null;
    $key = $_POST['key'] ?? null;
    $value = $_POST['value'] ?? null;

    if ($sessionID && $key && $value !== null) {
        if (!isset($_SESSION[$sessionID])) {
            $_SESSION[$sessionID] = [
                'dosisleistung' => 0,
                'dosis' => 0,
                'co' => 0,
                'ch4' => 0,
                'co2' => 0,
                'o2' => 21
            ];
        }
        
        $_SESSION[$sessionID][$key] = floatval($value);
        echo "OK";
    } else {
        http_response_code(400);
        echo "Missing parameters";
    }
} else {
    http_response_code(405);
    echo "Method not allowed";
}
?> 