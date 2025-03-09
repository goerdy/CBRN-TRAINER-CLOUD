<?php
$sessionID = isset($_GET['sessionID']) ? $_GET['sessionID'] : 'ABCD';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>DL-Warner Test</title>
    <style>
        canvas {
            border: 1px solid black;
            width: 200px;
            height: 300px;
        }
    </style>
</head>
<body>
    <canvas id="dlwCanvas" width="200" height="300"></canvas>

    <script>
        const canvas = document.getElementById('dlwCanvas');
        const ctx = canvas.getContext('2d');
        const sessionID = "<?php echo htmlspecialchars($sessionID); ?>";
        let lastValue = null;
        let isAlarming = false;
        let deviceState = 'OFF';  // Mögliche Zustände: 'OFF', 'BAT', 'ON'

        // Audio Context für Piepton
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        let oscillator = null;

        function startBeep(frequency = 800) {
            if (oscillator) return; // Bereits aktiv
            
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

        // Event Listener für Klicks auf den Drehregler
        canvas.addEventListener('click', function(event) {
            const rect = canvas.getBoundingClientRect();
            const x = (event.clientX - rect.left) * (canvas.width / rect.width);
            const y = (event.clientY - rect.top) * (canvas.height / rect.height);
            
            // Prüfe, ob der Klick im Bereich des Reglers war
            if (x >= 70 && x <= 130 && y >= 3 && y <= 18) {
                // Zustandswechsel
                switch(deviceState) {
                    case 'OFF': deviceState = 'BAT'; break;
                    case 'BAT': deviceState = 'ON'; break;
                    case 'ON': deviceState = 'OFF'; break;
                }
                drawDevice(lastValue);
            }
        });

        function fetchValue(callback) {
            var xhr = new XMLHttpRequest();
            const url = "update.php?device=dosisleistung&sessionID=" + encodeURIComponent(sessionID);
            xhr.open("GET", url, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            callback(response.value);
                        } catch (e) {
                            console.error("Error parsing response:", e);
                            callback('0');
                        }
                    } else {
                        console.error("Error fetching value:", xhr.status, xhr.statusText);
                    }
                }
            };
            xhr.onerror = function() {
                console.error("Network error occurred");
            };
            xhr.send();
        }

        function drawDevice(value) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Grundform des Geräts (dunkelgraues Rechteck)
            ctx.fillStyle = '#333333';
            roundRect(ctx, 20, 20, 160, 260, 10, true);

            // Weißes Frontpanel
            ctx.fillStyle = '#FFFFFF';
            roundRect(ctx, 30, 40, 140, 180, 5, true);

            // Flacher Drehregler oben (gerändelt)
            ctx.fillStyle = '#C0C0C0';
            const regler = {
                x: 70,
                y: 3,
                width: 60,
                height: 15,
                teeth: 15
            };
            
            // Grundform des Reglers
            ctx.fillRect(regler.x, regler.y, regler.width, regler.height);
            
            // Rändelung
            ctx.fillStyle = '#909090';
            const toothWidth = regler.width / regler.teeth;
            for(let i = 0; i < regler.teeth; i++) {
                if(i % 2 === 0) {
                    ctx.fillRect(
                        regler.x + i * toothWidth,
                        regler.y,
                        toothWidth,
                        regler.height
                    );
                }
            }

            // Zustandsanzeige neben dem Regler
            ctx.fillStyle = '#000000';
            ctx.font = '12px Arial';
            ctx.textAlign = 'left';
            ctx.fillText(deviceState, 140, 15);

            // CE-Zeichen
            ctx.fillStyle = '#000000';
            ctx.font = '16px Arial';
            ctx.textAlign = 'left';
            ctx.fillText('CE', 35, 60);

            // Lautsprecher-Gitter (schwarzer Kreis mit Löchern)
            ctx.fillStyle = '#000000';
            ctx.beginPath();
            ctx.arc(100, 100, 25, 0, Math.PI * 2);
            ctx.fill();

            // Löcher im Lautsprecher-Gitter
            ctx.fillStyle = '#333333';
            for(let i = 0; i < 12; i++) {
                const angle = (i / 12) * Math.PI * 2;
                const x = 100 + Math.cos(angle) * 15;
                const y = 100 + Math.sin(angle) * 15;
                ctx.beginPath();
                ctx.arc(x, y, 2, 0, Math.PI * 2);
                ctx.fill();
            }

            // Gerätebezeichnung
            ctx.fillStyle = '#000000';
            ctx.font = '14px Arial';
            ctx.textAlign = 'center';
            ctx.fillText('DL-WARNER', 100, 150);
            ctx.fillText('6126 B', 100, 170);
            ctx.fillText('Hp(10)', 100, 190);

            // CBRN-TRAINER Logo
            ctx.font = 'bold 14px Arial';
            ctx.fillText('CBRN-TRAINER', 100, 210);

            // Status LEDs
            const valueInMicroSv = deviceState === 'ON' ? value * 1000000 : 0;
            const isAlarm = deviceState === 'ON' && valueInMicroSv >= 10;

            // Grüne LED (Power)
            ctx.fillStyle = deviceState !== 'OFF' ? '#00FF00' : '#003300';
            drawCircle(ctx, 50, 240, 5);
            
            // Rote LED (Alarm)
            ctx.fillStyle = isAlarm ? '#FF0000' : '#330000';
            drawCircle(ctx, 150, 240, 5);

            // Alarm Sound
            if ((deviceState === 'BAT' || isAlarm) && !isAlarming) {
                startBeep(deviceState === 'BAT' ? 1000 : 800);  // Höherer Ton für BAT
                isAlarming = true;
            } else if ((!isAlarm && deviceState !== 'BAT') && isAlarming) {
                stopBeep();
                isAlarming = false;
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

        function drawCircle(ctx, x, y, radius) {
            ctx.beginPath();
            ctx.arc(x, y, radius, 0, Math.PI * 2);
            ctx.fill();
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