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
        
        function fetchValue(callback) {
            var xhr = new XMLHttpRequest();
            const url = "update.php?device=co&sessionID=" + encodeURIComponent(sessionID);
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
            const displayValue = Math.round(parseFloat(value));
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

            // Alarmschwellen prüfen und LEDs setzen
            if (value >= 300) {
                // A2-Alarm LED (rot)
                ctx.fillStyle = '#FF0000';
                drawCircle(ctx, 140, 30, 5);
            } else if (value >= 30) {
                // A1-Alarm LED (orange)
                ctx.fillStyle = '#FFA500';
                drawCircle(ctx, 140, 30, 5);
            } else {
                // Normal LED (grün)
                ctx.fillStyle = '#00FF00';
                drawCircle(ctx, 140, 30, 5);
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

        // Initiale Anzeige und Update alle 1000ms
        updateDisplay();
        setInterval(updateDisplay, 1000);
    </script>
</body>
</html> 