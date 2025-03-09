<?php
$sessionID = isset($_GET['sessionID']) ? $_GET['sessionID'] : 'ABCD';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>CBRN-TRAINER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        #deviceSelection {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
        }

        .control-panel {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #deviceView {
            display: none;
            flex-direction: column;
            align-items: center;
            padding: 10px;
            box-sizing: border-box;
        }

        .header {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            box-sizing: border-box;
            text-align: center;
            width: 100%;
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

        .button {
            background-color: #4a4a4a;
            color: white;
            border: none;
            padding: 15px 30px;
            margin: 10px;
            border-radius: 5px;
            font-size: 18px;
            width: 100%;
            max-width: 300px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.2s;
            text-transform: none;
            font-weight: normal;
        }

        .button:hover {
            background-color: #5a5a5a;
        }

        .back-button {
            background-color: #666;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            align-self: flex-start;
        }

        iframe {
            border: none;
            width: 300px;
            height: 500px;
            margin: 0 auto;
        }

        footer {
            margin-top: auto;
            padding: 20px;
            text-align: center;
            color: #666;
            
            
            width: 100%;
            box-sizing: border-box;
        }

        footer a {
            color: #666;
            text-decoration: none;
            font-weight: normal;
        }

        footer a:hover {
            text-decoration: underline;
            color: #333;
        }

        footer p {
            margin: 5px 0;
            font-size: 0.9em;
        }

        #deviceFrame {
            border: none;
            overflow: hidden;
            width: 100%;
            max-width: 340px;  /* Breite der Messgeräte */
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div id="deviceSelection">
        <div class="header">
            <h1>CBRN-TRAINER</h1>
            <button onclick="confirmReturn()" class="back-button">
                ← Zurück zur Startseite
            </button>
            <div class="session-info">Session-ID: <?php echo htmlspecialchars($sessionID); ?></div>
        </div>

        <div class="control-panel">
            <button class="button" onclick="selectDevice('co-warngerät')">CO Warngerät (PAC 6500)</button>
            <button class="button" onclick="selectDevice('multiwarngerät')">Gas Multiwarngerät (X-AM 8000)</button>
            <button class="button" onclick="selectDevice('a-messgeraete')">A-Einsatz Messgeräte</button>
            <button class="button" onclick="selectDevice('dosisleistungsmessgerät')">Dosisleistungsmessgerät (Automess)</button>
            <button class="button" onclick="selectDevice('dosiswarngerät')">Dosiswarngerät (Automess)</button>
            <button class="button" onclick="selectDevice('dl-warner')">DL-Warner (Automess)</button>
        </div>

        <footer>
            <p>Privates Projekt im Alpha-Status. Keine Gewährleistung.</p>
            <p>Kontakt: <a href="mailto:cbrn-trainer@philipp-guerth.de">cbrn-trainer@philipp-guerth.de</a></p>
            <p>&copy; 2015</p>
        </footer>
    </div>

    <div id="deviceView">
        <button class="back-button" onclick="showSelection()">← Zurück zur Auswahl</button>
        <iframe id="deviceFrame" src="" scrolling="no"></iframe>
    </div>

    <script>
        function selectDevice(device) {
            document.getElementById('deviceSelection').style.display = 'none';
            document.getElementById('deviceView').style.display = 'flex';
            
            let url;
            switch(device) {
                case 'co-warngerät':
                    url = 'CO1.php';
                    break;
                case 'multiwarngerät':
                    url = 'MultiWarngeraet1.php';
                    break;
                case 'a-messgeraete':
                    url = 'A-Messgeraete.php';
                    document.getElementById('deviceFrame').style.height = '700px';
                    break;
                case 'dosisleistungsmessgerät':
                    url = 'DosisLeistung1.php';
                    break;
                case 'dosiswarngerät':
                    url = 'DosisWarngeraet1.php';
                    break;
                case 'dl-warner':
                    url = 'DL-Warner1.php';
                    break;
            }
            
            if (url) {
                document.getElementById('deviceFrame').src = url + '?sessionID=<?php echo urlencode($sessionID); ?>';
            }
        }

        function showSelection() {
            document.getElementById('deviceView').style.display = 'none';
            document.getElementById('deviceSelection').style.display = 'flex';
            document.getElementById('deviceFrame').src = '';
        }

        function confirmReturn() {
            if (confirm('Möchten Sie wirklich zur Startseite zurückkehren?\n\nSie können jederzeit mit der Session-ID "<?php echo $sessionID; ?>" wieder in diese Session zurückkehren.')) {
                window.location.href = 'index.php';
            }
        }
    </script>
</body>
</html> 