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
    <title>CBRN-TRAINER (A-Einsatz)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
        }

        .control-group {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f8f8f8;
            border-radius: 8px;
        }

        .control-group h3 {
            margin: 0 0 15px 0;
            color: #444;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 5px 0;
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .checkbox-group label {
            cursor: pointer;
            user-select: none;
        }

        /* Zusätzliche Styles für Timer */
        .timer-display {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
            padding: 10px;
            background-color: #4a4a4a;
            color: white;
            border-radius: 5px;
            text-align: center;
        }

        .value-display-large {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
            padding: 10px;
            background-color: #4a4a4a;
            color: white;
            border-radius: 5px;
            text-align: center;
        }

        .timer-controls {
            display: flex;
            gap: 10px;
            margin: 10px 0;
        }

        .timer-button {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .timer-button.start {
            background-color: #4CAF50;
            color: white;
        }

        .timer-button.stop {
            background-color: #FFA500; /* Orange für Pause */
            color: white;
        }

        .timer-button.reset {
            background-color: #d9534f; /* Rot für Reset */
            color: white;
        }

        .distance-slider-container {
            background-color: #f8f8f8;
            border-radius: 8px;
            width: 100%;
            padding: 15px;
            box-sizing: border-box;
            position: relative;
            margin-bottom: 25px;
        }

        .slider-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 30px;  /* Platz für die Skala */
        }

        .distance-slider {
            width: 100% !important;
            margin: 10px 0;
            box-sizing: border-box;
        }

        .distance-scale {
            position: absolute;
            width: 100%;
            height: 20px;
            bottom: 0;
            left: 0;
            right: 0;
            box-sizing: border-box;
        }

        .scale-mark {
            position: absolute;
            transform: translateX(-50%);
            text-align: center;
            font-size: 12px;
        }

        .scale-mark::before {
            content: '';
            position: absolute;
            left: 50%;
            top: -15px;
            height: 10px;
            width: 1px;
            background: #666;
        }

        /* Logarithmischer Slider Hintergrund */
        #distance.slider {
            background: linear-gradient(to right,
                #4CAF50 0%,
                #FFA500 33%,
                #FF0000 66%,
                #800000 100%
            );
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CBRN-TRAINER</h1>
        <button onclick="confirmReturn()" class="back-button">
            ← Zurück zur Einsatzauswahl
        </button>
        <div class="session-info">
            Session-ID: <?php echo htmlspecialchars($sessionID); ?>
            <button id="qrButton" class="icon-button" onclick="toggleQR()">
                <img src="icons/qr-code.svg" alt="QR Code">
            </button>
        </div>
        <div id="qrcode" class="qr-container" style="display: none;"></div>
    </div>

    <div class="control-panel">
        <div class="control-group">
            <h3>Einsatzdaten</h3>
            <div class="form-group">
                <label for="source_strength">Dosisleistung der Quelle:</label>
                <div class="slider-with-buttons">
                    
                    <input type="range" class="slider" id="source_strength" 
                           min="0.0005" max="1" step="0.0001">
                    
                    <div class="value-display" id="source_strength-value"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="teletector" onchange="calculateDosisleistung()">
                    <label for="teletector">Messung mit Teletector</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="cover" onchange="calculateDosisleistung()">
                    <label for="cover">Hinter Deckung</label>
                </div>
            </div>
        </div>

        <div class="control-group distance-slider-container">
            <h3>Abstand zur Quelle</h3>
            <label for="distance">Abstand zur Quelle:</label>
            <div class="slider-wrapper">
                <input type="range" class="slider distance-slider" id="distance" 
                       min="0.1" max="100" step="0.1">
                <div class="distance-scale" id="distance-scale"></div>
            </div>
            <div class="value-display" id="distance-value"></div>
        </div>

        <div class="control-group">
            <h3>Messwerte</h3>
            <div class="timer-display"><i class="fas fa-hourglass-half"></i> <span id="timer">00:00:00</span></div>
            <div class="value-display-large" id="dosisleistung-value"><i class="fas fa-tachometer-alt"></i> 0 µSv/h</div>
            <div class="value-display-large"><i class="fas fa-radiation"></i> <span id="dosis-value">0 µSv</span></div>
            <div class="timer-controls">
                <button class="timer-button start" id="startTimer">Start</button>
                <button class="timer-button stop" id="stopTimer">Pause</button>
                <button class="timer-button reset" id="resetTimer">Reset</button>
            </div>
        </div>
    </div>

    <footer>
        <p>Privates Projekt im Alpha-Status. Keine Gewährleistung.</p>
        <p>Kontakt: <a href="mailto:info@cbrn-trainer.de">info@cbrn-trainer.de</a></p>
        <p>&copy; 2015</p>
    </footer>

    <script src="js/qrcode.min.js"></script>
    <script>
        const sessionID = "<?php echo $sessionID; ?>";
        let timerInterval = null;
        let startTime = 0;
        let elapsedTime = 0;
        let currentDosisleistung = 0;
        let accumulatedDosis = 0;  // Gesamte akkumulierte Dosis
        let lastUpdateTime = 0;    // Zeitpunkt der letzten Aktualisierung
        let currentBodyDosisleistung = 0;

        // Initialisiere die Werte
        const initialValues = {
            source_strength: <?php echo isset($initialValues['source_strength']) ? floatval($initialValues['source_strength']) : 0.5; ?>,
            distance: <?php echo isset($initialValues['distance']) ? floatval($initialValues['distance']) : 1; ?>,
        };

        // Konfiguration für die Slider
        const sliderConfig = {
            'source_strength': { 
                ranges: [
                    { max: 0.001, unit: 'µSv/h', multiplier: 1000000 },
                    { max: 0.1, unit: 'mSv/h', multiplier: 1000 },
                    { max: 1, unit: 'Sv/h', multiplier: 1 }
                ]
            },
            'distance': { unit: 'm', multiplier: 1 }
        };

        function createDistanceScale() {
            const scaleContainer = document.getElementById('distance-scale');
            const marks = [
                { value: 0.01, label: '1cm' },
                { value: 0.1, label: '10cm' },
                { value: 0.5, label: '50cm' },
                { value: 1, label: '1m' },
                { value: 2, label: '2m' },
                { value: 5, label: '5m' },
                { value: 10, label: '10m' },
                { value: 20, label: '20m' },
                { value: 30, label: '30m' },
                { value: 50, label: '50m' }
            ];

            marks.forEach(mark => {
                // Logarithmische Transformation
                const percent = (Math.log10(mark.value) - Math.log10(0.01)) / (Math.log10(50) - Math.log10(0.01)) * 100;
                const markElement = document.createElement('div');
                markElement.className = 'scale-mark';
                markElement.style.left = `${percent}%`;
                markElement.textContent = mark.label;
                scaleContainer.appendChild(markElement);
            });
        }

        // Funktion zur logarithmischen Transformation der Slider-Werte
        function logSliderToDistance(sliderValue) {
            // Transformiere den linearen Slider-Wert (0-100) in einen logarithmischen Abstandswert
            const minLog = Math.log10(0.01);
            const maxLog = Math.log10(50);
            const scale = (maxLog - minLog) / 100;
            // Stelle sicher, dass der Abstand nie kleiner als 1cm ist
            return Math.max(0.01, Math.pow(10, minLog + scale * sliderValue));
        }

        function distanceToLogSlider(distance) {
            // Transformiere den Abstandswert zurück in einen Slider-Wert
            const minLog = Math.log10(0.01);
            const maxLog = Math.log10(50);
            return (Math.log10(distance) - minLog) / (maxLog - minLog) * 100;
        }

        // Initialisiere Slider
        Object.keys(sliderConfig).forEach(id => {
            const slider = document.getElementById(id);
            const display = document.getElementById(id + '-value');
            
            if (id === 'distance') {
                slider.min = 0;
                slider.max = 100;
                slider.step = 0.1;
                slider.value = distanceToLogSlider(initialValues[id]);
            } else {
                slider.value = initialValues[id];
            }
            updateDisplay(slider, display, sliderConfig[id]);
            
            slider.oninput = function() {
                let value = this.value;
                if (id === 'distance') {
                    value = logSliderToDistance(this.value);
                }
                updateDisplay(this, display, sliderConfig[id]);
                updateValue(id, value);
                calculateDosisleistung();
            };
        });

        function updateDisplay(slider, display, config) {
            if (slider.id === 'distance') {
                // Formatiere Abstand je nach Größe
                const dist = logSliderToDistance(parseFloat(slider.value));
                if (dist < 0.1) {
                    display.textContent = `${(dist * 100).toFixed(0)} cm`;
                } else if (dist < 1) {
                    display.textContent = `${(dist * 100).toFixed(0)} cm`;
                } else {
                    display.textContent = `${dist.toFixed(2)} m`;
                }
            } else if (slider.id === 'source_strength') {
                // Automatische Einheitenwahl für Strahlungswerte
                const value = parseFloat(slider.value);
                const range = config.ranges.find(r => value <= r.max) || config.ranges[config.ranges.length - 1];
                display.textContent = `${(value * range.multiplier).toFixed(1)} ${range.unit}`;
            } else {
                const value = (slider.value * config.multiplier).toFixed(1);
                display.textContent = `${value} ${config.unit}`;
            }
        }

        function calculateDosisleistung() {
            const sourceStrength = parseFloat(document.getElementById('source_strength').value);
            const distance = parseFloat(document.getElementById('distance').value);
            const useTeletector = document.getElementById('teletector').checked;
            const behindCover = document.getElementById('cover').checked;
            
            // Berechne effektiven Abstand für Dosisleistung
            let effectiveDistance = Math.max(0.01, logSliderToDistance(distance));
            
            // Berechne Dosisleistung am Messpunkt
            let relativeDistance = effectiveDistance / 0.01;
            let dosisleistung = sourceStrength / (relativeDistance * relativeDistance);
            dosisleistung = dosisleistung * 1000000; // Umrechnung in µSv/h
            
            // Berechne Dosisleistung am Körper (für Dosiswarngerät)
            let bodyDistance = effectiveDistance;
            if (useTeletector) {
                bodyDistance += 4; // 4m Abstand bei Teletector
            }
            let relativeBodyDistance = bodyDistance / 0.01;
            let bodyDosisleistung = sourceStrength / (relativeBodyDistance * relativeBodyDistance);
            bodyDosisleistung = bodyDosisleistung * 1000000; // Umrechnung in µSv/h

            // Reduziere beide Dosisleistungen wenn hinter Deckung
            if (behindCover) {
                dosisleistung *= 0.1;
                bodyDosisleistung *= 0.1;
            }

            currentDosisleistung = dosisleistung;
            currentBodyDosisleistung = bodyDosisleistung;
            
            const display = document.getElementById('dosisleistung-value');
            display.innerHTML = `<i class="fas fa-tachometer-alt"></i> ${formatDosisleistung(currentDosisleistung)} | <i class="fas fa-user"></i> ${formatDosisleistung(currentBodyDosisleistung)}`;

            updateValue('dosisleistung', currentDosisleistung / 1000000); // Konvertiere zu Sv/h für Server
        }

        function formatDosisleistung(value) {
            if (value < 1000) {
                return `${value.toFixed(2)} µSv/h`;
            } else if (value < 1000000) {
                return `${(value / 1000).toFixed(2)} mSv/h`;
            } else {
                return `${(value / 1000000).toFixed(2)} Sv/h`;
            }
        }

        function updateValue(key, value) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `update.php?device=${key}&value=${value}&sessionID=${sessionID}`, true);
            xhr.send();
        }

        // Timer Funktionen
        document.getElementById('startTimer').addEventListener('click', startTimer);
        document.getElementById('stopTimer').addEventListener('click', stopTimer);
        document.getElementById('resetTimer').addEventListener('click', resetTimer);

        function startTimer() {
            if (timerInterval) return;
            startTime = Date.now() - elapsedTime;
            lastUpdateTime = Date.now();
            timerInterval = setInterval(updateTimer, 1000);
            document.getElementById('startTimer').disabled = true;
            document.getElementById('stopTimer').disabled = false;
        }

        function stopTimer() {
            clearInterval(timerInterval);
            timerInterval = null;
            document.getElementById('startTimer').disabled = false;
            document.getElementById('stopTimer').disabled = true;
        }

        function resetTimer() {
            stopTimer();
            elapsedTime = 0;
            accumulatedDosis = 0;
            updateTimer();
            document.getElementById('dosis-value').textContent = '0 µSv';
            updateValue('dosis', 0);
        }

        function updateTimer() {
            if (timerInterval) {
                const currentTime = Date.now();
                const deltaTime = (currentTime - lastUpdateTime) / 3600000; // Zeitdifferenz in Stunden
                
                // Berechne effektiven Abstand für Dosis
                const distance = parseFloat(document.getElementById('distance').value);
                const useTeletector = document.getElementById('teletector').checked;
                let effectiveDistance = Math.max(0.01, logSliderToDistance(distance));
                if (useTeletector) {
                    effectiveDistance += 4; // Addiere 4m wenn Teletector verwendet wird
                }
                
                // Berechne Dosis mit effektivem Abstand
                const sourceStrength = parseFloat(document.getElementById('source_strength').value);
                let relativeDistance = effectiveDistance / 0.01;
                let dosis = (sourceStrength / (relativeDistance * relativeDistance)) * 1000000 * deltaTime;
                
                // Reduziere Dosis wenn hinter Deckung
                if (document.getElementById('cover').checked) {
                    dosis *= 0.1; // Reduziere auf 10%
                }
                
                // Berechne die Dosis für das aktuelle Zeitintervall und addiere sie zur Gesamtdosis
                accumulatedDosis += dosis;
                
                lastUpdateTime = currentTime;
                elapsedTime = currentTime - startTime;
            }
            
            const seconds = Math.floor(elapsedTime / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            
            const displayTime = 
                String(hours).padStart(2, '0') + ':' +
                String(minutes % 60).padStart(2, '0') + ':' +
                String(seconds % 60).padStart(2, '0');
            
            document.getElementById('timer').textContent = displayTime;

            const dosisDisplay = document.getElementById('dosis-value');
            
            if (accumulatedDosis < 1000) {
                dosisDisplay.textContent = `${accumulatedDosis.toFixed(2)} µSv`;
            } else if (accumulatedDosis < 1000000) {
                dosisDisplay.textContent = `${(accumulatedDosis / 1000).toFixed(2)} mSv`;
            } else {
                dosisDisplay.textContent = `${(accumulatedDosis / 1000000).toFixed(2)} Sv`;
            }

            updateValue('dosis', accumulatedDosis / 1000000); // Konvertiere zu Sv für Server
        }

        // QR Code Funktionalität
        function toggleQR() {
            const qrDiv = document.getElementById('qrcode');
            if (qrDiv.style.display === 'none') {
                qrDiv.style.display = 'block';
                qrDiv.innerHTML = '';
                
                const traineeUrl = window.location.origin + 
                    '/CBRN-TRAINER/TRAINEE.php?sessionID=' + sessionID;
                
                new QRCode(qrDiv, {
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

        function confirmReturn() {
            window.location.href = 'TRAINER.php?sessionID=<?php echo $sessionID; ?>';
        }

        // Rufe die Funktion nach dem Laden der Seite auf
        window.addEventListener('load', createDistanceScale);

        // Initiale Berechnung
        calculateDosisleistung();
    </script>
</body>
</html> 