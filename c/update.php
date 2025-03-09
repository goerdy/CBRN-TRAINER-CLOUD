<?php
$device = isset($_GET['device']) ? $_GET['device'] : null;
$value = isset($_GET['value']) ? $_GET['value'] : null;
$sessionID = isset($_GET['sessionID']) ? $_GET['sessionID'] : null;

if ($device && $sessionID) {
    $sessionFile = "sessions/{$sessionID}.json";
    
    // Lese existierende Daten oder erstelle neue
    if (file_exists($sessionFile)) {
        $data = json_decode(file_get_contents($sessionFile), true);
    } else {
        $data = [
            'dosisleistung' => 0,
            'dosis' => 0,
            'co' => 0,
            'ch4' => 0,
            'co2' => 0.004,
            'o2' => 20.9,
            'ibut' => 0,
            'nona' => 0,
            'h2s' => 0,
            'nh3' => 0
        ];
        // Neue Session-Datei mit Standardwerten erstellen
        file_put_contents($sessionFile, json_encode($data));
    }
    
    // Wenn ein Wert gesetzt werden soll
    if (isset($value)) {
        $data[$device] = floatval($value);
        file_put_contents($sessionFile, json_encode($data));
    }
    
    // Gebe den aktuellen Wert zurück
    echo json_encode(['value' => isset($data[$device]) ? $data[$device] : '0']);
} else {
    echo json_encode(['value' => '0']);
}
?>
