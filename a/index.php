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
        
        .logo {
            height: 80px;
            object-fit: contain;
            border-radius: 15px;
        }

        .header-link {
            display: flex;
            align-items: center;
            gap: 20px;
            text-decoration: none;
            color: inherit;
            transition: opacity 0.3s;
        }

        .header-link:hover {
            opacity: 0.8;
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
            background-color: #4CAF50;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .download-section {
            background-color: #f8f8f8;
            border-radius: 10px;
            padding: 30px;
            margin: 30px 0;
        }

        .download-options {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            align-items: center;
            margin: 30px 0;
        }

        .qr-code-container {
            text-align: center;
        }

        .qr-code-container img {
            max-width: 200px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .play-store-button {
            text-align: center;
        }

        .play-store-button img {
            max-width: 250px;
            height: auto;
            border-radius: 5px;
            transition: transform 0.2s;
        }

        .play-store-button img:hover {
            transform: scale(1.05);
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

            .download-options {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="header-container">
        <a href="../index.php" class="header-link">
            <img src="../content/images/cbrn_trainer_logo.png" alt="CBRN-TRAINER Logo" class="logo">
            <h1>CBRN-TRAINER</h1>
        </a>
    </div>

    <div class="status-badge">Jetzt verfügbar im Play Store!</div>
    
    <p>Die Android-App des CBRN-TRAINERS ist jetzt offiziell im Google Play Store verfügbar!</p>
    
    <div class="app-container">
        <div class="app-info">
            <h2>Android-App für den CBRN-TRAINER</h2>
            <p>
                Die Android-App des CBRN-TRAINERs bietet Zugriff auf die Cloud-Version und zusätzliche Features für realistische Übungen im Feld.
            </p>
        </div>

        <div class="download-section">
            <h3>App herunterladen</h3>
            <p>Scannen Sie den QR-Code oder klicken Sie auf den Play Store-Button, um die App zu installieren:</p>
            
            <div class="download-options">
                <div class="qr-code-container">
                    <img src="qr_code_google_play_final.png" alt="QR-Code für Google Play Store">
                    <p>QR-Code scannen</p>
                </div>
                
                <div class="play-store-button">
                    <a href="https://play.google.com/store/apps/details?id=de.cbrntrainer" target="_blank">
                        <img src="getitongoogleplay.png" alt="Jetzt im Google Play Store">
                    </a>
                </div>
            </div>
        </div>

        <div class="features-list">
            <h3>Features der App:</h3>
            <ul>
                <li>Zugriff auf die Cloud-Version des CBRN-TRAINERs</li>
                <li>Optimiert für Android-Smartphones und -Tablets</li>
                <li>Dosisleistungssimulation mit Bluetooth Beacons</li>
                <li>Kontaminationsnachweis mit Magneten</li>
                <li>Offline-fähige Funktionen</li>
            </ul>
        </div>

        <div class="app-info">
            <h3>Systemanforderungen</h3>
            <p>
                <strong>Android-Version:</strong> Android 7.0 (API Level 24) oder höher<br>
                <strong>Empfohlene Geräte:</strong> Smartphone oder Tablet mit Android 7.0+<br>
                <strong>Bluetooth Low Energy (BLE):</strong> Für Beacon-Modus erforderlich<br>
                <strong>Magnetometer/Kompass:</strong> Für Kontaminationsnachweis (nicht in allen Geräten verfügbar)<br>
                <strong>RAM:</strong> Mindestens 2 GB<br>
                <strong>Speicherplatz:</strong> 50 MB freier Speicherplatz
            </p>
            
            <h4>Funktionen je nach Geräteausstattung:</h4>
            <ul style="text-align: left; max-width: 600px; margin: 0 auto;">
                <li><strong>Cloud-Modus:</strong> Funktioniert auf allen kompatiblen Geräten</li>
                <li><strong>Bluetooth-Modus:</strong> Benötigt BLE-fähiges Gerät</li>
                <li><strong>Kontaminationsnachweis:</strong> Benötigt Magnetometer (nicht in jedem Smartphone verbaut)</li>
                <li><strong>Internetverbindung:</strong> Nur für Cloud-Modus erforderlich</li>
            </ul>
            
            <p style="font-style: italic; margin-top: 20px;">
                <strong>Hinweis:</strong> Die App ist optimiert für Vollbildmodus und verhindert automatisch Display-Timeouts während der Nutzung.
            </p>
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