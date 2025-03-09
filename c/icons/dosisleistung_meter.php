<?php
header('Content-Type: image/svg+xml');
?>
<svg width="100" height="160" xmlns="http://www.w3.org/2000/svg">
    <!-- Gehäuse -->
    <rect x="10" y="10" width="80" height="140" rx="8" fill="#333333"/>
    
    <!-- Display -->
    <rect x="20" y="25" width="60" height="40" rx="5" fill="#90EE90"/>
    <text x="70" y="55" font-family="monospace" font-size="16" fill="black" text-anchor="end">0.00</text>
    <text x="70" y="65" font-family="Arial" font-size="8" fill="black" text-anchor="end">µSv/h</text>
    
    <!-- Drehschalter -->
    <circle cx="50" cy="90" r="20" fill="#444444"/>
    <circle cx="50" cy="90" r="15" fill="#666666"/>
    <circle cx="50" cy="90" r="12" fill="#888888"/>
    <line x1="50" y1="90" x2="50" y2="80" stroke="black" stroke-width="2"/>
    
    <!-- Beschriftung -->
    <text x="50" y="130" font-family="Arial" font-size="10" fill="white" text-anchor="middle">AUTOMESS</text>
    <text x="50" y="140" font-family="Arial" font-size="8" fill="white" text-anchor="middle">6150AD6</text>
</svg> 