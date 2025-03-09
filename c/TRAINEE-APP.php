<?php
$sessionID = isset($_GET['sessionID']) ? $_GET['sessionID'] : 'ABCD';
$appMode = true; // Flag für App-Modus ohne Header/Footer
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
            font-family: Arial, sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        #deviceSelection {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
            box-sizing: border-box;
            width: 100%;
            overflow-y: auto;
            flex: 1;
        }

        .control-panel {
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 15px;
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
            padding: 0;
            box-sizing: border-box;
            height: 100%;
            width: 100%;
        }

        .button {
            background-color: #4a4a4a;
            color: white;
            border: none;
            padding: 15px 30px;
            margin: 8px;
            border-radius: 5px;
            font-size: 16px;
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
            padding: 8px 16px;
            margin: 8px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            align-self: flex-start;
        }

        #deviceFrame {
            border: none;
            overflow: hidden;
            width: 100%;
            max-width: 340px;
            margin: 0 auto;
            flex: 1;
        }
    </style>
</head>
<body>
    <div id="deviceSelection">
        <div class="control-panel">
            <button class="button" onclick="selectDevice('co-warngerät')">CO Warngerät (PAC 6500)</button>
            <button class="button" onclick="selectDevice('multiwarngerät')">Gas Multiwarngerät (X-AM 8000)</button>
            <button class="button" onclick="selectDevice('a-messgeraete')">A-Einsatz Messgeräte</button>
            <button class="button" onclick="selectDevice('dosisleistungsmessgerät')">Dosisleistungsmessgerät (Automess)</button>
            <button class="button" onclick="selectDevice('dosiswarngerät')">Dosiswarngerät (Automess)</button>
            <button class="button" onclick="selectDevice('dl-warner')">DL-Warner (Automess)</button>
        </div>
    </div>

    <div id="deviceView">
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
    </script>
</body>
</html> 