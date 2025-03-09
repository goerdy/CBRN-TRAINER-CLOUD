<?php
$sessionID = isset($_GET['sessionID']) ? $_GET['sessionID'] : 'ABCD';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>CO-Messgerät Test</title>
    <style>
        canvas {
            border: 1px solid black;
            width: 300px;
            height: 400px;
        }
    </style>
</head>
<body>
    <canvas id="coCanvas" width="300" height="400"></canvas>

    <script>
        const canvas = document.getElementById('coCanvas');
        const ctx = canvas.getContext('2d');
        const sessionID = "<?php echo htmlspecialchars($sessionID); ?>";
        let lastValue = null;
        let isAlarming = false;
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
            // Bestehenden Alarm aufräumen
            if (beepInterval) {
                clearInterval(beepInterval);
                beepInterval = null;
            }
            stopBeep();
            
            if (type === 'A2') {
                // Dauerton für A2
                startBeep(800);
                isAlarming = true;
            } else if (type === 'A1') {
                // Unterbrochener Ton für A1
                beepInterval = setInterval(() => {
                    startBeep(800);
                    setTimeout(stopBeep, 500);
                }, 1000);
                isAlarming = true;
            } else {
                isAlarming = false;
            }
        }

        function fetchValue(callback) {
            var xhr = new XMLHttpRequest();
            const url = "update.php?device=co&sessionID=" + encodeURIComponent(sessionID);
            console.log("Fetching from URL:", url);
            xhr.open("GET", url, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        console.log("Raw response:", xhr.responseText);
                        try {
                            const response = JSON.parse(xhr.responseText);
                            console.log("Parsed response:", response);
                            callback(response.value);
                        } catch (e) {
                            console.error("Error parsing response:", e);
                            console.error("Response that failed to parse:", xhr.responseText);
                            callback('0');
                        }
                    } else {
                        console.error("Error fetching value:", xhr.status, xhr.statusText);
                        console.error("Error response:", xhr.responseText);
                    }
                }
            };
            xhr.onerror = function() {
                console.error("Network error occurred");
                console.error("Network error details:", xhr);
            };
            xhr.send();
        }

        function drawDevice(value) {
            // Canvas löschen
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Grundform des Geräts (schwarz mit silbernem Clip)
            ctx.fillStyle = '#333333';
            roundRect(ctx, 20, 20, 160, 235, 15, true);

            // graue display umrandung und hintergrund der butons
            ctx.fillStyle = '#CCCCCC';
            roundRect(ctx, 30, 30, 140, 200, 15, true);

            ctx.fillStyle = '#F5F5F5';
            roundRect(ctx, 40, 125, 120, 75, 5, true);


            

            // LCD Display (grünlich hinterleuchtet)
            ctx.fillStyle = '#90EE90';
            roundRect(ctx, 40, 50, 120, 80, 5, true);

           

            // Batterie-Symbol höher positionieren
            drawBatteryIcon(ctx, 135, 55);

            // Messwert
            ctx.fillStyle = '#000000';
            ctx.font = 'bold 36px monospace';
            ctx.textAlign = 'right';
            const displayValue = parseInt(value);
            ctx.fillText(displayValue, 140, 100);
            
            // PPM Einheit
            ctx.font = '14px Arial';
            ctx.fillText('PPM', 140, 120);

            // Buttons
            const buttons = [
                { x: 65, y: 155, label: '▼', color: '#0066CC' },
                { x: 135, y: 155, label: 'OK', color: '#00CC00' }
            ];

            buttons.forEach(btn => {
                // Button Hintergrund
                ctx.fillStyle = btn.color;
                ctx.beginPath();
                ctx.arc(btn.x, btn.y, 20, 0, Math.PI * 2);
                ctx.fill();

                // Button Beschriftung
                ctx.fillStyle = '#FFFFFF';
                ctx.font = '16px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(btn.label, btn.x, btn.y + 5);
            });

            // CBRN-TRAINER Logo
            ctx.fillStyle = '#0066CC';
            ctx.font = 'bold 14px Arial';
            ctx.textAlign = 'center';
            ctx.fillText('CBRN-TRAINER', 100, 190);
            ctx.fillStyle = '#000000';
            ctx.font = '12px Arial';
            ctx.fillText('Pac 8500', 100, 220);

            // Status LED und Alarme
            const numericValue = parseInt(value);
            stopBeep(); // Bestehenden Ton stoppen
            if (beepInterval) {
                clearInterval(beepInterval);
                beepInterval = null;
            }
            if (numericValue >= 60) {
                // A2-Alarm (rot + Dauerton)
                ctx.fillStyle = '#FF0000';
                drawCircle(ctx, 45, 35, 8);
                handleAlarm('A2');
            } else if (numericValue >= 30) {
                // A1-Alarm (orange + unterbrochener Ton)
                ctx.fillStyle = '#FFA500';
                drawCircle(ctx, 45, 35, 8);
                handleAlarm('A1');
            } else {
                // Normal (grün, kein Ton)
                ctx.fillStyle = '#00FF00';
                drawCircle(ctx, 45, 35, 8);
                handleAlarm(null);
            }
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

        function drawBatteryIcon(ctx, x, y) {
            ctx.fillStyle = '#000000';
            ctx.fillRect(x, y, 20, 10);
            ctx.fillRect(x + 2, y + 2, 14, 6);
            ctx.fillRect(x + 20, y + 3, 2, 4);
        }

        function updateDisplay() {
            fetchValue(function(value) {
                if (value !== lastValue) {
                    lastValue = value;
                    drawDevice(value);
                }
            });
        }

        function drawCircle(ctx, x, y, radius) {
            ctx.beginPath();
            ctx.arc(x, y, radius, 0, Math.PI * 2);
            ctx.fill();
        }

        // Initiale Anzeige und Update alle 1000ms
        updateDisplay();
        setInterval(updateDisplay, 1000);
    </script>
</body>
</html> 