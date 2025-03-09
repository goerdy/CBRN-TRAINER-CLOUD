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
    <title>CBRN-TRAINER (Trainer)</title>
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

        label {
            margin-bottom: 5px;
        }

        /* NH3 Slider (0-300 PPM) */
        #nh3.slider {
            background: linear-gradient(to right,
                #4CAF50 0%,     /* Grün bis 20 PPM (A1) */
                #4CAF50 6.67%,  /* 20/300 = 6.67% */
                #FFA500 6.67%,  /* Orange startet bei 20 PPM */
                #FFA500 13.33%, /* Orange bis 40 PPM */
                #FF0000 13.33%, /* Rot startet bei 40 PPM */
                #FF0000 100%
            );
        }

        /* CO Slider (0-500 PPM) */
        #co.slider {
            background: linear-gradient(to right,
                #4CAF50 0%,     /* Grün bis 30 PPM (A1) */
                #4CAF50 6%,     /* 30/500 = 6% */
                #FFA500 6%,     /* Orange von 30-60 PPM */
                #FFA500 12%,    /* 60/500 = 12% */
                #FF0000 12%,    /* Rot ab 60 PPM (A2) */
                #FF0000 100%
            );
        }

        /* H2S Slider (0-100 PPM) */
        #h2s.slider {
            background: linear-gradient(to right,
                #4CAF50 0%,     /* Grün bis 5 PPM (A1) */
                #4CAF50 5%,
                #FFA500 5%,     /* Orange von 5-10 PPM */
                #FFA500 10%,
                #FF0000 10%,    /* Rot ab 10 PPM (A2) */
                #FF0000 100%
            );
        }

        /* NONA Slider (0-1 %UEG) */
        #nona.slider {
            background: linear-gradient(to right,
                #4CAF50 0%,     /* Grün bis 0.2 (20%) */
                #4CAF50 20%,
                #FFA500 20%,    /* Orange von 20-40% */
                #FFA500 40%,
                #FF0000 40%,    /* Rot ab 40% */
                #FF0000 100%
            );
        }

        /* iBut Slider (0-2000 PPM) */
        #ibut.slider {
            background: linear-gradient(to right,
                #4CAF50 0%,     /* Grün bis 50 PPM (A1) */
                #4CAF50 2.5%,   /* 50/2000 = 2.5% */
                #FFA500 2.5%,   /* Orange von 50-100 PPM */
                #FFA500 5%,     /* 100/2000 = 5% */
                #FF0000 5%,     /* Rot ab 100 PPM (A2) */
                #FF0000 100%
            );
        }

        /* O2 Slider (0-25 VOL%) */
        #o2.slider {
            background: linear-gradient(to right,
                #FF0000 0%,     /* Rot unter 17% (A2) */
                #FF0000 56.7%,  /* 17/30 = 56.7% */
                #FFA500 56.7%,  /* Orange 17-19% (A1) */
                #FFA500 63.3%,  /* 19/30 = 63.3% */
                #4CAF50 63.3%,  /* Grün 19-23% */
                #4CAF50 76.7%,  /* 23/30 = 76.7% */
                #FFA500 76.7%,  /* Orange 23-24% (A1) */
                #FFA500 80%,    /* 24/30 = 80% */
                #FF0000 80%,    /* Rot über 24% (A2) */
                #FF0000 100%
            );
        }

        /* Dosisleistung Slider (0-1 Sv/h) */
        #dosisleistung.slider {
            background: linear-gradient(to right,
                #4CAF50 0%,      /* Grün bis 25 µSv/h */
                #4CAF50 0.0025%, /* 25µSv = 0.000025 Sv = 0.0025% von 1 Sv */
                #FFA500 0.0025%, /* Orange bis 1 mSv/h */
                #FFA500 0.1%,    /* 1mSv = 0.001 Sv = 0.1% von 1 Sv */
                #FF0000 0.1%,    /* Rot bis 10 mSv/h */
                #FF0000 1%,      /* 10mSv = 0.01 Sv = 1% von 1 Sv */
                #800000 1%,      /* Dunkelrot darüber */
                #800000 100%
            );
        }

        /* Dosis Slider (0-0.6 Sv) */
        #dosis.slider {
            background: linear-gradient(to right,
                #4CAF50 0%,      /* Grün bis 20 mSv */
                #4CAF50 3.33%,   /* 20mSv = 0.02 Sv = 3.33% von 0.6 Sv */
                #90EE90 3.33%,   /* Hellgrün bis 100 mSv */
                #90EE90 16.67%,  /* 100mSv = 0.1 Sv = 16.67% von 0.6 Sv */
                #FFA500 16.67%,  /* Orange bis 250 mSv */
                #FFA500 41.67%,  /* 250mSv = 0.25 Sv = 41.67% von 0.6 Sv */
                #FF0000 41.67%,  /* Rot bis 500 mSv */
                #FF0000 83.33%,  /* 500mSv = 0.5 Sv = 83.33% von 0.6 Sv */
                #800000 83.33%,  /* Dunkelrot darüber */
                #800000 100%
            );
        }

        /* CH4 Slider (0-100 %UEG) */
        #ch4.slider {
            background: linear-gradient(to right,
                #4CAF50 0%,     /* Grün bis 20 %UEG (A1) */
                #4CAF50 20%,
                #FFA500 20%,    /* Orange von 20-40 %UEG */
                #FFA500 40%,
                #FF0000 40%,    /* Rot ab 40 %UEG (A2) */
                #FF0000 100%
            );
        }

        /* CO2 Slider (0-5 VOL%) */
        #co2.slider {
            background: linear-gradient(to right,
                #4CAF50 0%,     /* Grün bis 1.5 VOL% (A1) */
                #4CAF50 30%,    /* 1.5/5 = 30% */
                #FFA500 30%,    /* Orange von 1.5-3 VOL% */
                #FFA500 60%,    /* 3/5 = 60% */
                #FF0000 60%,    /* Rot ab 3 VOL% (A2) */
                #FF0000 100%
            );
        }

        .slider {
            flex-grow: 1;
            height: 25px;
            -webkit-appearance: none;
            appearance: none;
            border-radius: 12.5px;
            outline: none;
            width: 300px;
        }

        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 4px;
            height: 30px;
            background: #fff;
            cursor: pointer;
            border: 2px solid #333;
            border-radius: 1px;
        }

        .slider::-moz-range-thumb {
            width: 8px;
            height: 35px;
            background: #333333;
            cursor: pointer;
            border-radius: 2px;
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
            border: none;
        }

        .value-display {
            min-width: 100px;
            padding: 5px 10px;
            background: #4a4a4a;
            color: white;
            border-radius: 5px;
            text-align: center;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4a4a4a;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #666;
        }

        button.reset {
            background-color: #d9534f;
        }

        button.reset:hover {
            background-color: #c9302c;
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

        .slider-with-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .value-button {
            background-color: #4a4a4a;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 5px;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .value-button:hover {
            background-color: #666;
        }

        .qr-container {
            margin: 20px auto;
            padding: 10px;
            background: white;
            border-radius: 5px;
            width: fit-content;
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

        /* Ausblenden des Radioaktivitäts-Bereichs */
        .radioactivity-group {
            display: none;
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
        <div class="control-group radioactivity-group">
            <h3>Radioaktivität</h3>
            <div class="form-group">
                <label for="dosisleistung">Dosisleistung:</label>
                <div class="slider-with-buttons">
                    <button class="value-button" 
                            onmousedown="startDecrement('dosisleistung')" 
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">-</button>
                    <input type="range" class="slider" id="dosisleistung" 
                           min="0" max="1" step="0.0000001">
                    <button class="value-button"
                            onmousedown="startIncrement('dosisleistung')"
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">+</button>
                    <div class="value-display" id="dosisleistung-value"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="dosis">Dosis:</label>
                <div class="slider-with-buttons">
                    <button class="value-button" 
                            onmousedown="startDecrement('dosis')" 
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">-</button>
                    <input type="range" class="slider" id="dosis" 
                           min="0" max="0.6" step="0.0001">
                    <button class="value-button"
                            onmousedown="startIncrement('dosis')"
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">+</button>
                    <div class="value-display" id="dosis-value"></div>
                </div>
            </div>
        </div>

        <div class="control-group">
            <h3>Gase</h3>
            <div class="form-group">
                <label for="co">CO:</label>
                <div class="slider-with-buttons">
                    <button class="value-button" 
                            onmousedown="startDecrement('co')" 
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">-</button>
                    <input type="range" class="slider" id="co" 
                           min="0" max="500" step="1">
                    <button class="value-button"
                            onmousedown="startIncrement('co')"
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">+</button>
                    <div class="value-display" id="co-value"></div>
                </div>
            </div>
            <div class="form-group">
                <label>CH₄:</label>
                <div class="slider-with-buttons">
                    <button class="value-button" 
                            onmousedown="startDecrement('ch4')" 
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">-</button>
                    <input type="range" class="slider" id="ch4" 
                           min="0" max="1" step="0.001">
                    <button class="value-button"
                            onmousedown="startIncrement('ch4')"
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">+</button>
                    <div class="value-display" id="ch4-value"></div>
                </div>
            </div>
            <div class="form-group">
                <label>CO₂:</label>
                <div class="slider-with-buttons">
                    <button class="value-button" 
                            onmousedown="startDecrement('co2')" 
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">-</button>
                    <input type="range" class="slider" id="co2" 
                           min="0" max="5" step="0.001">
                    <button class="value-button"
                            onmousedown="startIncrement('co2')"
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">+</button>
                    <div class="value-display" id="co2-value"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="o2">O₂:</label>
                <div class="slider-with-buttons">
                    <button class="value-button" 
                            onmousedown="startDecrement('o2')" 
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">-</button>
                    <input type="range" class="slider" id="o2" 
                           min="0" max="30" step="0.1">
                    <button class="value-button"
                            onmousedown="startIncrement('o2')"
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">+</button>
                    <div class="value-display" id="o2-value"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="ibut">iBut:</label>
                <div class="slider-with-buttons">
                    <button class="value-button" 
                            onmousedown="startDecrement('ibut')" 
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">-</button>
                    <input type="range" class="slider" id="ibut" 
                           min="0" max="2000" step="1">
                    <button class="value-button"
                            onmousedown="startIncrement('ibut')"
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">+</button>
                    <div class="value-display" id="ibut-value"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="nona">Nona:</label>
                <div class="slider-with-buttons">
                    <button class="value-button" 
                            onmousedown="startDecrement('nona')" 
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">-</button>
                    <input type="range" class="slider" id="nona" 
                           min="0" max="1" step="0.001">
                    <button class="value-button"
                            onmousedown="startIncrement('nona')"
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">+</button>
                    <div class="value-display" id="nona-value"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="h2s">H₂S:</label>
                <div class="slider-with-buttons">
                    <button class="value-button" 
                            onmousedown="startDecrement('h2s')" 
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">-</button>
                    <input type="range" class="slider" id="h2s" 
                           min="0" max="100" step="1">
                    <button class="value-button"
                            onmousedown="startIncrement('h2s')"
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">+</button>
                    <div class="value-display" id="h2s-value"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="nh3">NH₃:</label>
                <div class="slider-with-buttons">
                    <button class="value-button" 
                            onmousedown="startDecrement('nh3')" 
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">-</button>
                    <input type="range" class="slider" id="nh3" 
                           min="0" max="300" step="1">
                    <button class="value-button"
                            onmousedown="startIncrement('nh3')"
                            onmouseup="stopIncrement()"
                            onmouseleave="stopIncrement()">+</button>
                    <div class="value-display" id="nh3-value"></div>
                </div>
            </div>
        </div>

        <div class="button-group">
            <button onclick="resetAll()" class="reset">Alles zurücksetzen</button>
            <button onclick="setScenario('brand')">Brandszenario</button>
            <button onclick="setScenario('gas_raum')">Stadtgas (Raum)</button>
            <button onclick="setScenario('gas_leck')">Stadtgas (Leck)</button>
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
        
        // Initialisiere die Werte aus der Session
        const initialValues = {
            dosisleistung: <?php echo isset($initialValues['dosisleistung']) ? floatval($initialValues['dosisleistung']) : 0; ?>,
            dosis: <?php echo isset($initialValues['dosis']) ? floatval($initialValues['dosis']) : 0; ?>,
            co: <?php echo isset($initialValues['co']) ? floatval($initialValues['co']) : 0; ?>,
            ch4: <?php echo isset($initialValues['ch4']) ? floatval($initialValues['ch4']) : 0; ?>,
            co2: <?php echo isset($initialValues['co2']) ? floatval($initialValues['co2']) : 0.004; ?>,
            o2: <?php echo isset($initialValues['o2']) ? floatval($initialValues['o2']) : 20.9; ?>,
            ibut: <?php echo isset($initialValues['ibut']) ? floatval($initialValues['ibut']) : 0; ?>,
            nona: <?php echo isset($initialValues['nona']) ? floatval($initialValues['nona']) : 0; ?>,
            h2s: <?php echo isset($initialValues['h2s']) ? floatval($initialValues['h2s']) : 0; ?>,
            nh3: <?php echo isset($initialValues['nh3']) ? floatval($initialValues['nh3']) : 0; ?>
        };
        
        // Konfiguration für die Slider
        const sliderConfig = {
            'dosisleistung': { 
                ranges: [
                    { max: 0.0000001, unit: 'µSv/h', multiplier: 1000000 },
                    { max: 0.001, unit: 'mSv/h', multiplier: 1000 },
                    { max: 1, unit: 'Sv/h', multiplier: 1 }
                ]
            },
            'dosis': { 
                ranges: [
                    { max: 0.001, unit: 'mSv', multiplier: 1000 },
                    { max: 0.6, unit: 'Sv', multiplier: 1 }
                ]
            },
            'co': { unit: 'PPM', multiplier: 1 },
            'ch4': { unit: '%UEG', multiplier: 100 },
            'co2': { unit: 'VOL%', multiplier: 100 },
            'o2': { unit: 'VOL%', multiplier: 1 },
            'ibut': { unit: 'PPM', multiplier: 1 },
            'nona': { unit: '%UEG', multiplier: 100 },
            'h2s': { unit: 'PPM', multiplier: 1 },
            'nh3': { unit: 'PPM', multiplier: 1 }
        };

        // Initialisiere alle Slider
        Object.keys(sliderConfig).forEach(id => {
            const slider = document.getElementById(id);
            const display = document.getElementById(id + '-value');
            
            // Setze den Slider auf den Wert aus der Session
            slider.value = initialValues[id];
            updateDisplay(slider, display, sliderConfig[id]);
            
            slider.oninput = function() {
                updateDisplay(this, display, sliderConfig[id]);
                updateValue(id, this.value);
            };
        });

        function updateDisplay(slider, display, config) {
            let value;
            if (slider.id === 'dosisleistung' || slider.id === 'dosis') {
                // Automatische Einheitenwahl für Strahlungswerte
                const range = config.ranges.find(r => slider.value <= r.max) || config.ranges[config.ranges.length - 1];
                value = (slider.value * range.multiplier).toFixed(2);
                display.textContent = `${value} ${range.unit}`;
            } else {
                // Für Gase: Angepasste Logik
                if (slider.id === 'co2') {
                    value = parseFloat(slider.value).toFixed(3);
                } else {
                    value = (slider.value * config.multiplier).toFixed(
                        slider.id === 'o2' || config.multiplier !== 1 ? 1 : 0
                    );
                }
                display.textContent = `${value} ${config.unit}`;
            }
        }

        function updateValue(key, value) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `update.php?device=${key}&value=${value}&sessionID=${sessionID}`, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log(key + ' aktualisiert mit Wert: ' + response.value);
                    } catch (e) {
                        console.error("Error parsing response:", e);
                    }
                }
            };
            xhr.send();
        }

        function resetAll() {
            document.getElementById('dosisleistung').value = 0;
            document.getElementById('dosis').value = 0;
            document.getElementById('co').value = 0;
            document.getElementById('ch4').value = 0;
            document.getElementById('co2').value = 0.004;
            document.getElementById('o2').value = 20.9;
            document.getElementById('ibut').value = 0;
            document.getElementById('nona').value = 0;
            document.getElementById('h2s').value = 0;
            document.getElementById('nh3').value = 0;

            Object.keys(sliderConfig).forEach(id => {
                const slider = document.getElementById(id);
                const display = document.getElementById(id + '-value');
                updateDisplay(slider, display, sliderConfig[id]);
                updateValue(id, slider.value);
            });
        }

        function setScenario(type) {
            const scenarios = {
                'brand': {
                    'co': 150,
                    'co2': 0.05,
                    'o2': 17.0,
                    'ch4': 0,
                    'ibut': 0,
                    'nona': 0.05,
                    'h2s': 15,
                    'nh3': 0,
                    'dosisleistung': 0,
                    'dosis': 0
                },
                'gas_raum': {
                    'co': 0,
                    'co2': 0.5,    // Leicht erhöht durch Gasverdrängung
                    'o2': 20.5,    // Leicht reduziert durch Gasverdrängung
                    'ch4': 0.15,   // 15% UEG Methan
                    'ibut': 50,    // Geringe Beimischung
                    'nona': 0.08,
                    'h2s': 2,      // Typischer Odorierungsstoff
                    'nh3': 0,
                    'dosisleistung': 0,
                    'dosis': 0
                },
                'gas_leck': {
                    'co': 0,
                    'co2': 0.8,    // Stärker erhöht
                    'o2': 19.5,    // Deutlich reduziert
                    'ch4': 0.45,   // 45% UEG Methan - kritischer Bereich
                    'ibut': 150,   // Stärkere Beimischung
                    'nona': 0.25,
                    'h2s': 5,      // Deutlich wahrnehmbar
                    'nh3': 0,
                    'dosisleistung': 0,
                    'dosis': 0
                }
            };

            const scenario = scenarios[type];
            if (scenario) {
                // Alle Werte auf einmal an den Server senden
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "set_value.php", true);
                xhr.setRequestHeader("Content-Type", "application/json");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log("Szenario erfolgreich aktualisiert");
                    }
                };
                
                // Sende alle Werte und die Session-ID
                const data = {
                    sessionID: sessionID,
                    values: scenario
                };
                
                xhr.send(JSON.stringify(data));

                Object.keys(scenario).forEach(id => {
                    const slider = document.getElementById(id);
                    const display = document.getElementById(id + '-value');
                    slider.value = scenario[id];
                    updateDisplay(slider, display, sliderConfig[id]);
                });
            }
        }

        function incrementValue(id) {
            const slider = document.getElementById(id);
            const range = parseFloat(slider.max) - parseFloat(slider.min);
            const step = range / 100;
            const newValue = Math.min(parseFloat(slider.value) + step, parseFloat(slider.max));
            slider.value = newValue;
            const event = new Event('input');
            slider.dispatchEvent(event);
        }

        function decrementValue(id) {
            const slider = document.getElementById(id);
            const range = parseFloat(slider.max) - parseFloat(slider.min);
            const step = range / 100;
            const newValue = Math.max(parseFloat(slider.value) - step, parseFloat(slider.min));
            slider.value = newValue;
            const event = new Event('input');
            slider.dispatchEvent(event);
        }

        // Variablen für das kontinuierliche Ändern
        let intervalId = null;
        let startDelay = null;

        function startIncrement(id) {
            if (intervalId) return;
            incrementValue(id);
            startDelay = setTimeout(() => {
                intervalId = setInterval(() => incrementValue(id), 50);
            }, 500);
        }

        function startDecrement(id) {
            if (intervalId) return;
            decrementValue(id);
            startDelay = setTimeout(() => {
                intervalId = setInterval(() => decrementValue(id), 50);
            }, 500);
        }

        function stopIncrement() {
            clearInterval(intervalId);
            clearTimeout(startDelay);
            intervalId = null;
            startDelay = null;
        }

        function toggleQR() {
            const qrDiv = document.getElementById('qrcode');
            if (qrDiv.style.display === 'none') {
                qrDiv.style.display = 'block';
                qrDiv.innerHTML = ''; // Clear previous QR code
                
                // Generate QR code for trainee URL
                const traineeUrl = window.location.origin + 
                    '/CBRN-TRAINER/TRAINEE.php?sessionID=' + sessionID;
                
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

        function confirmReturn() {
            window.location.href = 'TRAINER.php?sessionID=<?php echo $sessionID; ?>';
        }
    </script>
</body>
</html> 