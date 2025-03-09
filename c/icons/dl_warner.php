<?php
header('Content-Type: image/svg+xml');
?>
<svg width="100" height="160" xmlns="http://www.w3.org/2000/svg">
    <!-- Gehäuse -->
    <rect x="10" y="10" width="80" height="140" rx="8" fill="#333333"/>
    
    <!-- Frontpanel -->
    <rect x="15" y="20" width="70" height="120" rx="5" fill="#FFFFFF"/>
    
    <!-- LED -->
    <circle cx="25" cy="35" r="5" fill="#00FF00"/>
    
    <!-- Lautsprechergitter -->
    <circle cx="50" cy="70" r="15" fill="black"/>
    <circle cx="50" cy="70" r="13" fill="#333"/>
    
    <!-- Beschriftung -->
    <text x="50" y="100" font-family="Arial" font-size="10" fill="black" text-anchor="middle">DL-WARNER</text>
    <text x="50" y="115" font-family="Arial" font-size="8" fill="black" text-anchor="middle">6126 B</text>
    <text x="50" y="125" font-family="Arial" font-size="8" fill="black" text-anchor="middle">Hp(10)</text>
</svg> 