<?php
$sessionID = isset($_GET['sessionID']) ? $_GET['sessionID'] : 'ABCD';
$standalone = false; // Flag für eigenständige/eingebundene Anzeige
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>A-Einsatz Messgeräte</title>
    <style>
        .devices-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
            padding: 20px;
        }
        .device-label {
            font-family: Arial, sans-serif;
            font-size: 1.2em;
            color: #333;
            margin-bottom: -10px;
        }
        iframe {
            border: none;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="devices-container">
        <div>
            
            <iframe src="DosisLeistung1.php?sessionID=<?php echo urlencode($sessionID); ?>" 
                    width="340" height="530"></iframe>
        </div>
        <div>
            
            <iframe src="DosisWarngeraet1.php?sessionID=<?php echo urlencode($sessionID); ?>" 
                    width="340" height="130"></iframe>
        </div>
    </div>
</body>
</html> 