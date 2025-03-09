<?php
header('Content-Type: application/json');

// Empfange JSON-Daten
$input = json_decode(file_get_contents('php://input'), true);
$sessionID = $input['sessionID'] ?? null;
$values = $input['values'] ?? null;

if (!$sessionID || !$values) {
    http_response_code(400);
    echo json_encode(['error' => 'Fehlende Parameter']);
    exit;
}

// Pfad zur Session-Datei
$sessionFile = "sessions/{$sessionID}.json";

// Aktuelle Werte laden
$currentValues = [];
if (file_exists($sessionFile)) {
    $currentValues = json_decode(file_get_contents($sessionFile), true);
}

// Neue Werte aktualisieren
foreach ($values as $key => $value) {
    $currentValues[$key] = $value;
}

// In Datei speichern
file_put_contents($sessionFile, json_encode($currentValues));

echo json_encode(['success' => true]);
?> 