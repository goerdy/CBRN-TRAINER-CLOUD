<?php
$sessionID = isset($_GET['sessionID']) ? $_GET['sessionID'] : 'ABCD';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dosiswarngerät Test</title>
    <style>
        canvas {
            border: 1px solid black;
            width: 300px;
            height: 100px;
        }
    </style>
</head>
<body>
    <canvas id="dwCanvas" width="300" height="100"></canvas>

    <script>
        const canvas = document.getElementById('dwCanvas');
        const ctx = canvas.getContext('2d');
        const sessionID = "<?php echo htmlspecialchars($sessionID); ?>";
        let lastValue = null;
        let isOn = false;
        const thresholds = [15, 100, 250, 1];
        let currentThresholdIndex = 0;
        let buttonPressTimer = null;
        let isButtonPressed = false;
        let alarmActive = false;
        let blinkState = true;
        let blinkInterval = null;
        let audioContext = null;
        let oscillator = null;
        let touchStartTime = 0;

        // Initialisiere Audio Context beim ersten Klick
        function initAudio() {
            if (!audioContext) {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
            }
        }

        function startAlarm() {
            if (!alarmActive) {
                alarmActive = true;
                
                // Starte den Alarmton
                if (!oscillator) {
                    oscillator = audioContext.createOscillator();
                    oscillator.type = 'square';
                    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                    oscillator.connect(audioContext.destination);
                    oscillator.start();
                }

                blinkInterval = setInterval(() => {
                    blinkState = !blinkState;
                    drawDevice(lastValue);
                }, 500);
            }
        }

        function stopAlarm() {
            if (alarmActive) {
                alarmActive = false;
                
                // Stoppe den Alarmton
                if (oscillator) {
                    oscillator.stop();
                    oscillator.disconnect();
                    oscillator = null;
                }

                if (blinkInterval) {
                    clearInterval(blinkInterval);
                    blinkInterval = null;
                }
                blinkState = true;
                drawDevice(lastValue);
            }
        }

        // Mousedown-Handler für den Button
        canvas.addEventListener('mousedown', function(e) {
            handleButtonPress(e);
        });

        // Mouseup-Handler für den Button
        canvas.addEventListener('mouseup', function() {
            handleButtonRelease();
        });

        // Mouseleave-Handler für den Button
        canvas.addEventListener('mouseleave', function() {
            handleButtonRelease();
        });

        // Touch Events
        canvas.addEventListener('touchstart', function(e) {
            e.preventDefault(); // Verhindert Maus-Events auf Touch-Geräten
            touchStartTime = Date.now();
            handleButtonPress(e.touches[0]);
        });

        canvas.addEventListener('touchend', function(e) {
            e.preventDefault();
            handleButtonRelease();
        });

        canvas.addEventListener('touchcancel', function(e) {
            e.preventDefault();
            handleButtonRelease();
        });

        function handleButtonPress(e) {
            const rect = canvas.getBoundingClientRect();
            const x = (e.clientX || e.pageX) - rect.left;
            const y = (e.clientY || e.pageY) - rect.top;
            
            // Prüfe ob der Klick im Bereich des Buttons war
            const distance = Math.sqrt(Math.pow(x - 240, 2) + Math.pow(y - 40, 2));
            if (distance <= 22) {
                initAudio(); // Initialisiere Audio beim ersten Klick
                isButtonPressed = true;
                buttonPressTimer = setTimeout(() => {
                    if (isButtonPressed) {
                        if (!isOn) {
                            isOn = true;
                        } else {
                            currentThresholdIndex = (currentThresholdIndex + 1) % thresholds.length;
                        }
                        drawDevice(lastValue);
                    }
                }, 1000);
            }
        }

        function handleButtonRelease() {
            isButtonPressed = false;
            if (buttonPressTimer) {
                clearTimeout(buttonPressTimer);
                buttonPressTimer = null;
            }
        }

        function fetchValue(callback) {
            var xhr = new XMLHttpRequest();
            const url = "update.php?device=dosis&sessionID=" + encodeURIComponent(sessionID);
            xhr.open("GET", url, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        // Prüfe Alarmschwelle
                        if (isOn && response.value * 1000 >= thresholds[currentThresholdIndex]) {
                            startAlarm();
                        } else {
                            stopAlarm();
                        }
                        callback(response.value);
                    } catch (e) {
                        console.error("Error parsing response:", e);
                        callback('0');
                    }
                }
            };
            xhr.send();
        }

        function drawDevice(value) {
            // Canvas löschen
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Schwarzer äußerer Rahmen (eckig)
            ctx.fillStyle = '#000000';
            ctx.fillRect(5, 5, 290, 90);
            
            // Hauptgehäuse (silber)
            ctx.fillStyle = '#C0C0C0';
            roundRect(ctx, 8, 8, 284, 84, 8, true);

            // Display-Bereich (dunkelgrau)
            ctx.fillStyle = '#333333';
            roundRect(ctx, 50, 20, 153, 40, 3, true);

            // LCD-Hintergrund
            ctx.fillStyle = isOn ? '#C8E6C9' : '#808080';  // Grau wenn aus, Hellgrün wenn an
            ctx.fillRect(55, 25, 143, 30);

            if (isOn) {
                // "A" Text im Display
                ctx.fillStyle = '#000000';
                ctx.font = 'bold 20px monospace';
                ctx.textAlign = 'left';
                if (!alarmActive || blinkState) {
                    ctx.fillText('A', 65, 45);
                }

                // Alarmschwelle im Display
                ctx.textAlign = 'right';
                if (!alarmActive || blinkState) {
                    ctx.fillText(thresholds[currentThresholdIndex], 168, 45);
                }
            }

            // mSv Text
            ctx.fillStyle = '#333333';
            ctx.font = 'bold 20px Arial';
            ctx.textAlign = 'left';
            ctx.fillText('mSv', 10, 45);

            // Zeichne Button-Effekt wenn gedrückt
            if (isButtonPressed) {
                ctx.fillStyle = '#000000';
                ctx.beginPath();
                ctx.arc(240, 40, 22, 0, Math.PI * 2);
                ctx.fill();

                // Silberner Rand am Button
                ctx.strokeStyle = '#808080'; // Dunkleres Silber wenn gedrückt
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.arc(240, 40, 22, 0, Math.PI * 2);
                ctx.stroke();
            } else {
                ctx.fillStyle = '#000000';
                ctx.beginPath();
                ctx.arc(240, 40, 22, 0, Math.PI * 2);
                ctx.fill();

                // Silberner Rand am Button
                ctx.strokeStyle = '#C0C0C0';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.arc(240, 40, 22, 0, Math.PI * 2);
                ctx.stroke();
            }
        }

        function formatValue(value) {
            // Konvertiere zu mSv und formatiere
            const msvValue = value * 1000; // Sv zu mSv
            return {
                value: msvValue.toFixed(2),
                unit: 'mSv'
            };
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

        function updateDisplay() {
            fetchValue(function(value) {
                if (value !== lastValue) {
                    lastValue = value;
                    drawDevice(value);
                }
            });
        }

        // Initiale Anzeige und Update alle 1000ms
        updateDisplay();
        setInterval(updateDisplay, 1000);
    </script>
</body>
</html> 