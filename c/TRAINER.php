<?php
session_start();
$sessionID = isset($_GET['sessionID']) ? $_GET['sessionID'] : null;

if (!$sessionID) {
    header('Location: index.php');
    exit;
}

// Lese Werte aus der JSON-Datei
$sessionFile = "sessions/{$sessionID}.json";
$initialValues = [];

if (file_exists($sessionFile)) {
    $initialValues = json_decode(file_get_contents($sessionFile), true);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>CBRN-TRAINER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        .header {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }

        .session-info {
            color: #666;
            margin-top: 10px;
        }

        .control-panel {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .trainer-button {
            display: inline-block;
            padding: 15px 30px;
            margin: 10px;
            font-size: 18px;
            background-color: #4a4a4a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .trainer-button:hover {
            background-color: #666;
        }

        .trainer-button.a-trainer {
            background-color: #d9534f;
        }

        .trainer-button.c-trainer {
            background-color: #5bc0de;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 0.9em;
        }

        footer a {
            color: #666;
            text-decoration: none;
        }

        .back-button {
            background-color: #666;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .back-button:hover {
            background-color: #555;
        }

        .icon-button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0 5px;
            vertical-align: middle;
        }
        
        .icon-button img {
            width: 24px;
            height: 24px;
            vertical-align: middle;
        }

        .qr-container {
            margin: 20px auto;
            padding: 10px;
            background: white;
            border-radius: 5px;
            width: fit-content;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CBRN-TRAINER</h1>
        <div class="session-info">
            Session-ID: <?php echo htmlspecialchars($sessionID); ?>
            <button id="qrButton" class="icon-button" onclick="toggleQR()">
                <img src="icons/qr-code.svg" alt="QR Code">
            </button>
        </div>
        <button onclick="confirmReturn()" class="back-button">
            ← Zurück zur Startseite
        </button>
        <div id="qrcode" class="qr-container" style="display: none;"></div>
    </div>

    <div class="control-panel">
        <h2>Wählen Sie die Art des Einsatzes</h2>
        <a href="TRAINER-A.php?sessionID=<?php echo urlencode($sessionID); ?>" class="trainer-button a-trainer">
            A (RN)-Einsatz
        </a>
        <a href="TRAINER-C.php?sessionID=<?php echo urlencode($sessionID); ?>" class="trainer-button c-trainer">
            C-Einsatz
        </a>
    </div>

    <footer>
        <p>Privates Projekt im Alpha-Status. Keine Gewährleistung.</p>
        <p>Kontakt: <a href="mailto:info@cbrn-trainer.de">info@cbrn-trainer.de</a></p>
        <p>&copy; 2015</p>
    </footer>

    <script src="js/qrcode.min.js"></script>
    <script>
        const sessionID = "<?php echo $sessionID; ?>";

        function confirmReturn() {
            if (confirm('Möchten Sie wirklich zur Startseite zurückkehren?\n\nSie können jederzeit mit der Session-ID "<?php echo $sessionID; ?>" wieder in diese Session zurückkehren.')) {
                window.location.href = 'index.php';
            }
        }

        function toggleQR() {
            const qrDiv = document.getElementById('qrcode');
            if (qrDiv.style.display === 'none') {
                qrDiv.style.display = 'block';
                qrDiv.innerHTML = '';
                
                const traineeUrl = window.location.origin + 
                    '/c/TRAINEE.php?sessionID=' + sessionID;
                
                var qr = new QRCode(qrDiv, {
                    text: traineeUrl,
                    width: 128,
                    height: 128,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            } else {
                qrDiv.style.display = 'none';
            }
        }
    </script>
</body>
</html> 