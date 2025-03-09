<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>CBRN-TRAINER - Android App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px 20px;
            margin: 0;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #333;
        }

        h2 {
            font-size: 24px;
            margin: 30px 0 15px;
            color: #444;
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #666;
            line-height: 1.6;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .device-icons {
            display: flex;
            flex-direction: row;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .device-icons img {
            height: 50px;
            object-fit: contain;
        }

        .app-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto 40px;
        }

        .app-info {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 30px;
            align-items: center;
            justify-content: center;
        }

        .app-icon {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .app-details {
            flex: 1;
            min-width: 300px;
            text-align: left;
        }

        .app-details h3 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }

        .app-details p {
            margin: 10px 0;
            font-size: 16px;
        }

        .status-badge {
            display: inline-block;
            background-color: #FFC107;
            color: #333;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .features-list {
            text-align: left;
            max-width: 800px;
            margin: 0 auto 40px;
            padding: 0 20px;
        }

        .features-list li {
            margin-bottom: 10px;
            font-size: 16px;
            color: #555;
        }

        .cta-button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #3ddc84;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .cta-button:hover {
            background-color: #32b36c;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #666;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #555;
        }

        hr {
            margin: 40px 0;
            border: 0;
            border-top: 1px solid #ccc;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #f4f4f4;
            color: #666;
            font-size: 0.9em;
            margin-top: 40px;
        }

        footer a {
            color: #666;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
            }
            
            .app-info {
                flex-direction: column;
                text-align: center;
            }
            
            .app-details {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="device-icons">
            <img src="../c/icons/co_meter.php" alt="CO Warngerät">
            <img src="../c/icons/multi_meter.php" alt="Multiwarngerät">
        </div>
        <h1>CBRN-TRAINER</h1>
        <div class="device-icons">
            <img src="../c/icons/dosisleistung_meter.php" alt="Dosisleistungsmessgerät">
            <img src="../c/icons/dl_warner.php" alt="DL-Warner">
            <img src="../c/icons/dosis_warner.php" alt="Dosiswarngerät">
        </div>
    </div>

    <div class="status-badge">Beta-Phase</div>
    
    <p>Die Android-App des CBRN-TRAINERS befindet sich derzeit in der Entwicklung.</p>
    
    <div class="app-container">
        <div class="app-info">
            <h2>Android-App für den CBRN-TRAINER</h2>
            <p>
                Die Android-App des CBRN-TRAINERs bietet Zugriff auf die Cloud-Version und zusätzliche Features für realistische Übungen im Feld.
            </p>
            
            <h3>Status der App</h3>
            <p>
                Der Anmeldeprozess im Google Play Store ist noch nicht abgeschlossen. Wir arbeiten daran, die App so bald wie möglich offiziell verfügbar zu machen.
            </p>
            
            <h3>Beta-Tester gesucht!</h3>
            <p>
                Möchten Sie die App schon jetzt testen? Wir suchen engagierte Beta-Tester, die uns helfen, die App zu verbessern, bevor sie offiziell veröffentlicht wird.
            </p>
            <p>
                Schreiben Sie einfach eine E-Mail an <a href="mailto:info@cbrn-trainer.de">info@cbrn-trainer.de</a> mit dem Betreff "Beta-Test" und wir senden Ihnen die APK-Datei zum Testen zu.
            </p>
            
            <a href="mailto:info@cbrn-trainer.de?subject=Beta-Test%20CBRN-TRAINER%20App" class="cta-button">Als Beta-Tester bewerben</a>
        </div>

        <div class="features-list">
            <h3>Features der App:</h3>
            <ul>
                <li>Zugriff auf die Cloud-Version des CBRN-TRAINERs</li>
                <li>Optimiert für Android-Smartphones und -Tablets</li>
                <li>Dosisleistungssimulation mit Bluetooth Beacons</li>
                <li>Kontaminationsnachweis mit Magneten</li>
            </ul>
        </div>
    </div>

    <a href="../index.php" class="back-button">← Zurück zur Startseite</a>

    <hr>

    <footer>
        <p>Privates Projekt im Alpha-Status. Keine Gewährleistung.</p>
        <p>Keine Rechte an den verwendeten Bildern und Marken der Messgeräte.</p>
        <p>Kontakt: <a href="mailto:info@cbrn-trainer.de">info@cbrn-trainer.de</a></p>
        <p>&copy; 2015-2023</p>
    </footer>
</body>
</html> 