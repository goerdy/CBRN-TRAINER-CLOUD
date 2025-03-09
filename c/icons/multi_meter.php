<?php
header('Content-Type: image/svg+xml');
?>
<svg width="100" height="160" xmlns="http://www.w3.org/2000/svg">
    <!-- Gehäuse -->
    <rect x="10" y="10" width="80" height="140" rx="10" fill="#333333"/>
    
    <!-- Display-Bereich -->
    <rect x="20" y="25" width="60" height="90" rx="5" fill="#FFFFFF"/>
    
    <!-- Display-Inhalte -->
    <text x="25" y="45" font-family="Arial" font-size="10" fill="black">O₂: 20.9%</text>
    <text x="25" y="65" font-family="Arial" font-size="10" fill="black">CO: 0 ppm</text>
    <text x="25" y="85" font-family="Arial" font-size="10" fill="black">H₂S: 0 ppm</text>
    <text x="25" y="105" font-family="Arial" font-size="10" fill="black">CH₄: 0 %UEG</text>
    
    <!-- Buttons -->
    <circle cx="35" cy="130" r="8" fill="#0066CC"/>
    <circle cx="60" cy="130" r="8" fill="#00CC00"/>
    <circle cx="85" cy="130" r="8" fill="#0066CC"/>
    
    <!-- Beschriftung -->
    <text x="50" y="145" font-family="Arial" font-size="8" fill="white" text-anchor="middle">X-AM 8000</text>
</svg> 