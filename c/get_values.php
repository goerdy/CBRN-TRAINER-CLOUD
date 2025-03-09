<?php
session_start();
$sessionID = $_GET['sessionID'] ?? null;

if ($sessionID && isset($_SESSION[$sessionID])) {
    header('Content-Type: application/json');
    echo json_encode($_SESSION[$sessionID]);
} else {
    http_response_code(400);
    echo "Session not found";
}
?> 