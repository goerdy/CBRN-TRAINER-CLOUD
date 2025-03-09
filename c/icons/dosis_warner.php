<?php
header('Content-Type: image/svg+xml');
?>
<svg width="100" height="160" xmlns="http://www.w3.org/2000/svg">
    <!-- Gehäuse -->
    <rect x="10" y="10" width="80" height="140" rx="8" fill="#333333"/>
    
    <!-- Display -->
    <rect x="20" y="30" width="60" height="30" rx="2" fill="#90EE90"/>
    <text x="70" y="50" font-family="monospace" font-size="14" fill="black" text-anchor="end">0.00</text>
    <text x="70" y="58" font-family="Arial" font-size="8" fill="black" text-anchor="end">mSv</text>
    
    <!-- Buttons -->
    <rect x="25" y="70" width="50" height="15" rx="3" fill="#666666"/>
    <rect x="25" y="95" width="50" height="15" rx="3" fill="#666666"/>
    
    <!-- Beschriftung -->
    <text x="50" y="135" font-family="Arial" font-size="8" fill="white" text-anchor="middle">EPD Mk2</text>
</svg> 