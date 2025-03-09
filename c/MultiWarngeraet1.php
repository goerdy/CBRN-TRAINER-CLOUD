<?php
$sessionID = isset($_GET['sessionID']) ? $_GET['sessionID'] : 'ABCD';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Multiwarngerät Test</title>
    <style>
        canvas {
            border: 1px solid black;
            width: 200px;
            height: 400px;
        }
    </style>
</head>
<body>
    <canvas id="mwCanvas" width="200" height="400"></canvas>

    <script>
        const canvas = document.getElementById('mwCanvas');
        const ctx = canvas.getContext('2d');
        const sessionID = "<?php echo htmlspecialchars($sessionID); ?>";
        let lastValues = {};
        let isAlarming = false;
        let alarmType = null;
        let beepInterval = null;
        
        // Audio Context für Piepton
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        let oscillator = null;
        
        function startBeep(frequency = 800) {
            if (oscillator) return;
            oscillator = audioContext.createOscillator();
            oscillator.type = 'square';
            oscillator.frequency.setValueAtTime(frequency, audioContext.currentTime);
            oscillator.connect(audioContext.destination);
            oscillator.start();
        }
        
        function stopBeep() {
            if (oscillator) {
                oscillator.stop();
                oscillator.disconnect();
                oscillator = null;
            }
        }
        
        function handleAlarm(type) {
            if (type === alarmType) return;
            
            // Bestehenden Alarm aufräumen
            if (beepInterval) {
                clearInterval(beepInterval);
                beepInterval = null;
            }
            stopBeep();
            
            alarmType = type;
            
            if (type === 'A2') {
                // Dauerton für A2
                startBeep(800);
            } else if (type === 'A1') {
                // Unterbrochener Ton für A1
                beepInterval = setInterval(() => {
                    startBeep(800);
                    setTimeout(stopBeep, 500);
                }, 1000);
            }
        }

        function fetchValues(callback) {
            const gases = ['ibut', 'nona', 'o2', 'h2s', 'co', 'nh3'];
            let results = {};
            let completed = 0;

            gases.forEach(gas => {
                var xhr = new XMLHttpRequest();
                const url = `update.php?device=${gas}&sessionID=${encodeURIComponent(sessionID)}`;
                xhr.open("GET", url, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                results[gas] = response.value;
                                completed++;
                                if (completed === gases.length) {
                                    callback(results);
                                }
                            } catch (e) {
                                console.error("Error parsing response:", e);
                                results[gas] = '0';
                                completed++;
                                if (completed === gases.length) {
                                    callback(results);
                                }
                            }
                        }
                    }
                };
                xhr.send();
            });
        }

        function drawDevice(values) {
            // Canvas löschen
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Grundform des Geräts (schwarz mit silbernen Akzenten)
            ctx.fillStyle = '#000000';
            roundRect(ctx, 20, 20, 160, 320, 15, true);

            // Silberne Seitenstreifen
            ctx.fillStyle = '#C0C0C0';
            roundRect(ctx, 20, 50, 10, 250, 0, true);
            roundRect(ctx, 170, 50, 10, 250, 0, true);

            // LCD Display (blau hinterleuchtet)
            ctx.fillStyle = '#1E90FF';
            roundRect(ctx, 40, 60, 120, 180, 5, true);

            // Messwerte anzeigen
            const measurements = [
                { label: 'iBut', value: values.ibut, unit: 'PPM', format: v => parseInt(v) },
                { label: 'Nona', value: values.nona, unit: '%UEG', format: v => (v * 100).toFixed(1) },
                { label: 'O₂', value: values.o2, unit: 'VOL%', format: v => parseFloat(v).toFixed(1) },
                { label: 'H₂S', value: values.h2s, unit: 'PPM', format: v => parseInt(v) },
                { label: 'CO', value: values.co, unit: 'PPM', format: v => parseInt(v) },
                { label: 'NH₃', value: values.nh3, unit: 'PPM', format: v => parseInt(v) }
            ];

            ctx.font = '14px monospace';
            measurements.forEach((m, i) => {
                const y = 90 + i * 25;
                
                // Horizontale Trennlinie (außer für die letzte Zeile)
                if (i > 0) {
                    ctx.strokeStyle = '#FFFFFF';
                    ctx.lineWidth = 0.5;
                    ctx.beginPath();
                    ctx.moveTo(45, y - 12);
                    ctx.lineTo(155, y - 12);
                    ctx.stroke();
                }
                
                // Werte in weiß
                ctx.fillStyle = '#FFFFFF';
                ctx.textAlign = 'left';
                ctx.fillText(m.label, 50, y);
                
                ctx.textAlign = 'right';
                const displayValue = m.format(m.value);
                ctx.fillText(`${displayValue} ${m.unit}`, 150, y);

                // Farbige Statusanzeige
                let statusColor;
                if (m.label === 'O₂') {
                    const o2Value = parseFloat(m.value);
                    if (o2Value < 17 || o2Value > 23) {
                        statusColor = '#FF0000';
                    } else if (o2Value < 19.5 || o2Value > 21.5) {
                        statusColor = '#FFFF00';
                    } else {
                        statusColor = '#00FF00';
                    }
                } else {
                    statusColor = '#00FF00';
                }
                ctx.fillStyle = statusColor;
                drawCircle(ctx, 45, y - 4, 2);
            });

            // Bedientasten
            const buttons = [
                { x: 60, y: 260, color: '#0066CC', label: '▼' },
                { x: 100, y: 260, color: '#00CC00', label: 'OK' },
                { x: 140, y: 260, color: '#0066CC', label: '▲' }
            ];

            buttons.forEach(btn => {
                // Runde Buttons
                ctx.fillStyle = btn.color;
                ctx.beginPath();
                ctx.arc(btn.x, btn.y, 15, 0, Math.PI * 2);
                ctx.fill();
                
                // Tastenbeschriftung
                ctx.fillStyle = '#FFFFFF';
                ctx.font = '16px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(btn.label, btn.x, btn.y + 6);
            });

            // Logo
            ctx.fillStyle = '#FFFFFF';
            ctx.font = 'bold 14px Arial';
            ctx.textAlign = 'center';
            ctx.fillText('CBRN-TRAINER', 100, 300);
            ctx.font = '12px Arial';
            ctx.fillText('X-AM 8000', 100, 315);
        }

        function roundRect(ctx, x, y, width, height, radius, fill) {
            ctx.beginPath();
            ctx.moveTo(x + radius, y);
            ctx.lineTo(x + width - radius, y);
            ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
            ctx.lineTo(x + width, y + height - radius);
            ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
            ctx.lineTo(x + radius, y + height);
            ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
            ctx.lineTo(x, y + radius);
            ctx.quadraticCurveTo(x, y, x + radius, y);
            ctx.closePath();
            if (fill) {
                ctx.fill();
            } else {
                ctx.stroke();
            }
        }

        function drawCircle(ctx, x, y, radius) {
            ctx.beginPath();
            ctx.arc(x, y, radius, 0, Math.PI * 2);
            ctx.fill();
        }

        function updateDisplay() {
            fetchValues(function(values) {
                if (JSON.stringify(values) !== JSON.stringify(lastValues)) {
                    lastValues = values;
                    drawDevice(values);
                }
            });
        }

        function checkAlarms(values) {
            let highestAlarm = null;
            
            // Prüfe jeden Stoff auf Alarmschwellen
            for (let gas in values) {
                const value = values[gas];
                if (value >= getA2Threshold(gas)) {
                    highestAlarm = 'A2';
                    break;
                } else if (value >= getA1Threshold(gas) && highestAlarm !== 'A2') {
                    highestAlarm = 'A1';
                }
            }
            
            handleAlarm(highestAlarm);
            return highestAlarm;
        }

        // Initiale Anzeige und Update alle 1000ms
        updateDisplay();
        setInterval(updateDisplay, 1000);
    </script>
</body>
</html> 