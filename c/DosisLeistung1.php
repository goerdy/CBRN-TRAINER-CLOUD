<?php
$sessionID = isset($_GET['sessionID']) ? $_GET['sessionID'] : 'ABCD';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dosisleistungsmessgerät Test</title>
    <style>
        canvas {
            border: 1px solid black;
            width: 300px;
            height: 500px;
        }
    </style>
</head>
<body>
    <canvas id="dlCanvas" width="300" height="500"></canvas>

    <script>
        const canvas = document.getElementById('dlCanvas');
        const ctx = canvas.getContext('2d');
        const sessionID = "<?php echo htmlspecialchars($sessionID); ?>";
        let lastValue = null;

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
            
            // Grundform des Geräts
            ctx.fillStyle = '#000000';
            roundRect(ctx, 20, 20, 260, 460, 10, true);

            // Weißer Hintergrund für CBRN-TRAINER
            ctx.fillStyle = '#FFFFFF';
            roundRect(ctx, 35, 35, 80, 25, 3, true);
            
            // CBRN-TRAINER Text
            ctx.fillStyle = '#000000';
            ctx.font = 'bold 11px Arial';
            ctx.textAlign = 'center';
            ctx.fillText('CBRN-TRAINER', 75, 52);
            
            // Weißer Hintergrund für Seriennummer
            ctx.fillStyle = '#FFFFFF';
            roundRect(ctx, 185, 35, 80, 25, 3, true);
            
            // Seriennummer
            ctx.fillStyle = '#000000';
            ctx.font = '12px Arial';
            ctx.textAlign = 'left';
            ctx.fillText('S/N: ' + sessionID, 190, 52);

            // Radioaktivitäts-Warnsymbol (zentriert zwischen den Kästen)
            ctx.fillStyle = '#FFD700';  // Gelb
            
            // Dreieck mit abgerundeten Ecken
            ctx.beginPath();
            const centerX = 150;
            const centerY = 47;
            const size = 15;
            const radius = 3; // Radius für die abgerundeten Ecken
            
            // Berechne die Punkte des Dreiecks (nach unten zeigend)
            const point1 = { x: centerX - size, y: centerY - size }; // oben links
            const point2 = { x: centerX + size, y: centerY - size }; // oben rechts
            const point3 = { x: centerX, y: centerY + size };        // unten mitte
            
            // Zeichne das Dreieck mit abgerundeten Ecken
            ctx.moveTo((point1.x + point2.x) / 2, point1.y); // Start in der Mitte oben
            
            // Obere rechte Ecke
            ctx.arcTo(point2.x, point2.y, 
                     (point2.x + point3.x) / 2, (point2.y + point3.y) / 2, 
                     radius);
            
            // Untere rechte Ecke
            ctx.arcTo(point3.x, point3.y,
                     (point3.x + point1.x) / 2, (point3.y + point1.y) / 2,
                     radius);
            
            // Untere linke Ecke
            ctx.arcTo(point1.x, point1.y,
                     (point1.x + point2.x) / 2, point1.y,
                     radius);
            
            ctx.closePath();
            ctx.fill();
            
            // Kreis im Warnsymbol
            ctx.beginPath();
            ctx.arc(centerX, centerY, 6, 0, Math.PI * 2);
            ctx.fill();

            // LCD Display
            ctx.fillStyle = '#333333';
            roundRect(ctx, 40, 90, 220, 100, 5, true);
            ctx.fillStyle = '#90EE90';
            ctx.fillRect(45, 95, 210, 90);

            // Messwert im Display
            ctx.fillStyle = '#000000';
            ctx.font = 'bold 20px monospace';
            ctx.textAlign = 'center';
            const formattedValue = formatValue(value);
            ctx.fillText(formattedValue.unit, 150, 120);

            // Messwert größer unten
            ctx.fillStyle = '#000000';
            ctx.font = 'bold 36px monospace';
            ctx.textAlign = 'center';
            ctx.fillText(formattedValue.value, 150, 165);

            // Bedienknöpfe (2x2 Raster)
            for(let i = 0; i < 4; i++) {
                const row = Math.floor(i / 2);
                const col = i % 2;
                const centerX = 60 + col * 180;
                const centerY = 230 + row * 90;
                const radius = 25;

                // Knopf-Hintergrund
                ctx.fillStyle = '#444444';
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
                ctx.fill();
                
                // Knopf-Rand
                ctx.strokeStyle = '#666666';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
                ctx.stroke();

                // Button-Symbole
                ctx.fillStyle = '#FFD700';  // Gelb
                ctx.strokeStyle = '#FFD700';
                
                const symbolY = centerY + 38;  // Symbole etwas tiefer
                
                switch(i) {
                    case 0: // Oben links: Kreis mit Punkt
                        ctx.beginPath();
                        ctx.arc(centerX, symbolY, 10, 0, Math.PI * 2);  // Größerer Kreis
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.arc(centerX, symbolY, 3, 0, Math.PI * 2);   // Größerer Mittelpunkt
                        ctx.fill();
                        break;
                        
                    case 1: // Oben rechts: Glühbirne
                        ctx.beginPath();
                        // Birnenform (vertikal)
                        ctx.arc(centerX, symbolY - 4, 6, 0, Math.PI * 2);
                        ctx.fill();
                        // Sockel (horizontal)
                        ctx.fillRect(centerX - 3, symbolY + 2, 6, 3);
                        break;
                        
                    case 2: // Unten links: Haus
                        ctx.beginPath();
                        ctx.moveTo(centerX, symbolY - 8);                   // Größeres Haus
                        ctx.lineTo(centerX - 8, symbolY);
                        ctx.lineTo(centerX + 8, symbolY);
                        ctx.closePath();
                        ctx.fill();
                        ctx.fillRect(centerX - 5, symbolY, 10, 8);         // Größerer Hauskörper
                        break;
                        
                    case 3: // Unten rechts: Lautsprecher
                        ctx.beginPath();
                        ctx.moveTo(centerX - 5, symbolY);                   // Größerer Lautsprecher
                        ctx.lineTo(centerX + 3, symbolY - 7);
                        ctx.lineTo(centerX + 3, symbolY + 7);
                        ctx.closePath();
                        ctx.fill();
                        // Schallwellen
                        ctx.beginPath();
                        ctx.arc(centerX + 6, symbolY, 5, -Math.PI/3, Math.PI/3);
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.arc(centerX + 9, symbolY, 8, -Math.PI/3, Math.PI/3);
                        ctx.stroke();
                        break;
                }
            }

            // Typenschild
            ctx.fillStyle = '#FFFFFF';
            roundRect(ctx, 40, 380, 220, 85, 5, true);
            
            // Typenschild Text
            ctx.fillStyle = '#000000';
            ctx.textAlign = 'left';
            ctx.font = '12px Arial';
            
            // Erste Zeile: Modell und H*(10)
            ctx.font = 'bold 14px Arial';
            ctx.fillText('6150 AD 5/E', 45, 400);
            ctx.font = '12px Arial';
            ctx.fillText('H*(10)', 160, 400);
            
            // Messbereich Zeilen
            ctx.font = '11px Arial';
            // Bezeichnungen (linksbündig)
            ctx.textAlign = 'left';
            ctx.fillText('Anzeigebereich', 45, 415);
            ctx.fillText('Messbereich', 45, 430);
            ctx.fillText('Energiebereich', 45, 445);
            ctx.fillText('Winkelbereich', 45, 460);
            
            // Werte (rechtsbündig)
            ctx.textAlign = 'right';
            ctx.fillText('0,1µSv/h - 1Sv/h', 240, 415);
            ctx.fillText('0.5µSv/h - 999mSv/h', 240, 430);
            ctx.fillText('60keV - 1,3MeV', 240, 445);
            ctx.fillText('±45°', 240, 460);
            
            // CE Symbol und Warnsymbol rechts
            ctx.textAlign = 'left';
            ctx.font = '12px Arial';
            ctx.fillText('CE', 230, 400);
        }

        function drawScale(ctx, x, y, width, height) {
            ctx.strokeStyle = '#000000';
            ctx.lineWidth = 1;
            
            // Hauptlinie
            ctx.beginPath();
            ctx.moveTo(x, y + height/2);
            ctx.lineTo(x + width, y + height/2);
            ctx.stroke();

            // Skalenstriche
            const divisions = [0, 0.2, 0.5, 1, 2, 5, 10, 20, 50, 100];
            divisions.forEach(div => {
                const xPos = x + (Math.log10(div + 0.1) / 2) * width;
                ctx.beginPath();
                ctx.moveTo(xPos, y);
                ctx.lineTo(xPos, y + height);
                ctx.stroke();
            });
        }

        function formatValue(value) {
            // Begrenze den Maximalwert auf 1 Sv/h für die Anzeige
            if (value > 1) {
                value = 1;
            }

            // Konvertiere in µSv/h für die Berechnung
            let microSv = value * 1000000;

            if (microSv < 999) {
                // Unter 999 µSv/h
                displayValue = microSv.toFixed(2);
                unit = 'µSv/h';
            } else if (microSv < 999000) {
                // Unter 999 mSv/h
                displayValue = (microSv / 1000).toFixed(2);
                unit = 'mSv/h';
            } else {
                // Ab 1 Sv/h
                displayValue = (microSv / 1000000).toFixed(2);
                unit = 'Sv/h';
            }

            return { value: displayValue, unit: unit };
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

        function getCurrentRange(value) {
            if (value <= 0.000001) return 0; // µSv/h
            if (value <= 0.001) return 1;    // mSv/h
            return 2;                        // Sv/h
        }

        // Initiale Anzeige und Update alle 1000ms
        updateDisplay();
        setInterval(updateDisplay, 1000);
    </script>
</body>
</html> 