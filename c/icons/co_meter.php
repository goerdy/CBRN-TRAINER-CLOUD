<?php
header('Content-Type: image/svg+xml');
?>
<svg width="100" height="160" xmlns="http://www.w3.org/2000/svg">
    <!-- Gehäuse -->
    <rect x="10" y="10" width="80" height="140" rx="10" fill="#333333"/>
    
    <!-- Display-Bereich -->
    <rect x="20" y="25" width="60" height="70" rx="5" fill="#90EE90"/>
    
    <!-- LED -->
    <circle cx="25" cy="20" r="4" fill="#00FF00"/>
    
    <!-- Display-Wert -->
    <text x="70" y="65" font-family="monospace" font-size="20" fill="black" text-anchor="end">0</text>
    <text x="70" y="80" font-family="Arial" font-size="10" fill="black" text-anchor="end">PPM</text>
    
    <!-- Buttons -->
    <circle cx="35" cy="110" r="10" fill="#0066CC"/>
    <circle cx="65" cy="110" r="10" fill="#00CC00"/>
    
    <!-- Beschriftung -->
    <text x="50" y="135" font-family="Arial" font-size="8" fill="white" text-anchor="middle">PAC 8500</text>
</svg> 