<?php
// PHP-Code zur Verarbeitung von Session-ID-Generierung und -Überprüfung

function generateRandomID() {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $sessionID = '';
    for ($i = 0; $i < 4; $i++) {
        $sessionID .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $sessionID;
}

// Prüfen, ob eine Aktion gesendet wurde
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'generate') {
        // Neue Session-ID generieren
        do {
            $sessionID = generateRandomID();
            $filename = "sessions/$sessionID.json";
        } while (file_exists($filename));

        // Session-Datei erstellen
        if (!file_exists('sessions')) {
            mkdir('sessions', 0777, true);
        }
        file_put_contents($filename, json_encode(["sessionID" => $sessionID]));
        file_put_contents($filename, json_encode([
            "sessionID" => $sessionID,
            "dosisleistung" => 0,
            "dosis" => 0,
            "co" => 0,
            "ch4" => 0,
            "co2" => 0.004,    // Normaler atmosphärischer Wert
            "o2" => 20.9,      // Normaler atmosphärischer Wert
            "ibut" => 0,
            "nona" => 0,
            "h2s" => 0,
            "nh3" => 0
        ]));


        // JSON-Antwort zurückgeben
        echo json_encode(["success" => true, "sessionID" => $sessionID]);
        exit;
    } elseif ($action === 'check') {
        // Vorhandene Session-ID überprüfen
        $sessionID = $_GET['sessionID'];
        $filename = "sessions/$sessionID.json";

        if (file_exists($filename)) {
            echo json_encode(["exists" => true]);
        } else {
            echo json_encode(["exists" => false]);
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>CBRN-TRAINER Simulation von Messgeräten</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #333;
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #666;
        }

        .session-container {
            margin-bottom: 30px;
        }

        .session-container input {
            font-size: 24px;
            padding: 10px;
            width: 200px;
            text-transform: uppercase;
            letter-spacing: 5px;
            text-align: center;
        }

        .session-container button {
            font-size: 20px;
            padding: 10px 20px;
            margin-left: 10px;
            color: #fff;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .session-container button:hover {
            background-color: #218838;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .button-container a {
            text-decoration: none;
            padding: 15px 30px;
            font-size: 20px;
            color: #fff;
            background-color: #007BFF;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button-container a:hover {
            background-color: #0056b3;
        }

        /* Spezifische Stile für den Trainee- und Trainerbereich */
        .trainee-section, .trainer-section {
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .trainee-section {
            background-color: #f0f8ff;
        }

        .trainer-section {
            background-color: #f8f0ff;
        }

        hr {
            margin: 40px 0;
            border: 0;
            border-top: 1px solid #ccc;
        }

        .faq-button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 20px 0;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
            max-width: 300px;
        }

        .faq-button:hover {
            background-color: #45a049;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .device-icons {
            display: flex;
            flex-direction: row;
            gap: 10px;
        }
        
        .device-icons img {
            height: 50px;
            object-fit: contain;
        }
    </style>
    <script>
        // Function to generate a new session ID
        async function generateSessionID() {
            const response = await fetch('index.php?action=generate');
            const data = await response.json();

            if (data.success) {
                window.location.href = 'TRAINER.php?sessionID=' + data.sessionID;
            } else {
                alert('Fehler beim Generieren einer neuen Session-ID.');
            }
        }

        // Function to log in to an existing session
        async function loginToSession(isTrainer) {
            const sessionIDField = isTrainer ? document.getElementById('trainerSessionID') : document.getElementById('traineeSessionID');
            const sessionID = sessionIDField.value.toUpperCase();

            if (sessionID.length !== 4 || !/^[A-Z0-9]{4}$/.test(sessionID)) {
                alert('Bitte geben Sie eine gültige 4-stellige Session-ID ein.');
                return;
            }

            const response = await fetch('index.php?action=check&sessionID=' + sessionID);
            const data = await response.json();

            if (data.exists) {
                if (isTrainer) {
                    window.location.href = 'TRAINER.php?sessionID=' + sessionID;
                } else {
                    window.location.href = 'TRAINEE.php?sessionID=' + sessionID;
                }
            } else {
                alert('Diese Session-ID existiert nicht.');
            }
        }

        function createSession() {
            const sessionID = generateSessionID();
            // Erstelle die JSON-Datei für die neue Session
            fetch(`update.php?sessionID=${sessionID}&device=init`)
                .then(response => response.json())
                .then(data => {
                    window.location.href = `TRAINEE.php?sessionID=${sessionID}`;
                });
        }
    </script>
</head>
<body>
    <div class="header-container">
        <div class="device-icons">
            <img src="icons/co_meter.php" alt="CO Warngerät">
            <img src="icons/multi_meter.php" alt="Multiwarngerät">
        </div>
        <h1>CBRN-TRAINER</h1>
        <div class="device-icons">
            <img src="icons/dosisleistung_meter.php" alt="Dosisleistungsmessgerät">
            <img src="icons/dl_warner.php" alt="DL-Warner">
            <img src="icons/dosis_warner.php" alt="Dosiswarngerät">
        </div>
    </div>
    <p>Simulation von diversen Gefahrstoffmessgeräten für Einsatzkräfte im A und C Einsatz.</p>

    <div class="trainee-section">
        <h2>Trainee Bereich</h2>
        <div class="session-container">
            <input type="text" id="traineeSessionID" maxlength="4" placeholder="Session-ID">
            <button onclick="loginToSession(false)">In vorhandene Session einloggen</button>
        </div>
        <p>Hier kommst du zur Messgeräteansicht.<br>Die Session-ID bekommst du von deinem Trainer.</p>
    </div>

    <hr>

    <div class="trainer-section">
        <h2>Trainer Bereich</h2>

        <div class="button-container">
            <a href="#" onclick="generateSessionID()">Neue Session erstellen</a>
        </div><br>
        <div class="session-container">
            <input type="text" id="trainerSessionID" maxlength="4" placeholder="Session-ID">
            <button onclick="loginToSession(true)">In vorhandene Session einloggen</button>
        </div>
        <p>Hier kannst du die Anzeigewerte der virtuellen Messgeräte deiner Trainees manipulieren.</p>
    </div>
    <hr style="border: 1px solid #ccc; margin-top: 40px;">

    <footer style="text-align: center; padding: 20px; background-color: #f4f4f4; color: #666; font-size: 0.9em;">
        <button onclick="window.location.href='faq.html'" class="faq-button">Häufig gestellte Fragen</button>
        <p style="margin: 0;">Privates Projekt im Alpha-Status. Keine Gewährleistung.</p>
        <p style="margin: 0;">Keine Rechte an den verwendeten Bildern.</p>
        <p style="margin: 0;">Kontakt: <a href="mailto:info@cbrn-trainer.de" style="color: #666; text-decoration: none;">info@cbrn-trainer.de</a></p>
        <p style="margin: 0;">&copy; 2015</p>
    </footer>


</body>
</html>
