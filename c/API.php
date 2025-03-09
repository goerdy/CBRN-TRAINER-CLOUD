<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Fehlerbehandlung aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Funktion zum sicheren Ausgeben der Fehlermeldung als JSON
function sendError($message) {
    echo json_encode(['error' => $message]);
    exit;
}

// Debug-Informationen
$debug = [
    'received_session_id' => $_GET['session_id'] ?? 'nicht gesetzt'
];

// Prüfen ob eine Session-ID übergeben wurde
if (!isset($_GET['session_id'])) {
    sendError('Keine Session-ID angegeben');
}

$session_id = $_GET['session_id'];

// Pfad zur Session-Datei
$session_file = __DIR__ . '/sessions/' . $session_id . '.json';

// Debug-Information zum Dateipfad
$debug['session_file'] = $session_file;
$debug['file_exists'] = file_exists($session_file) ? 'ja' : 'nein';

// Prüfen ob die Session-Datei existiert
if (!file_exists($session_file)) {
    $debug['error'] = 'Session-Datei nicht gefunden';
    echo json_encode($debug);
    exit;
}

// Session-Daten auslesen
$session_data = file_get_contents($session_file);

// JSON dekodieren
$session_array = json_decode($session_data, true);

if ($session_array !== null) {
    // Session-Daten als JSON ausgeben
    echo json_encode([
        'success' => true,
        'session_id' => $session_id,
        'data' => $session_array,
        'debug' => $debug
    ]);
} else {
    $debug['error'] = 'Fehler beim Dekodieren der JSON-Daten';
    echo json_encode($debug);
} 