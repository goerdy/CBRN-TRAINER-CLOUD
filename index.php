<?php
// Bestimme die aktuelle Seite
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Sicherheitscheck
$allowedPages = ['home', 'faq', 'anleitung', 'impressum'];
if (!in_array($page, $allowedPages)) {
    $page = 'home';
}

// Setze Variablen für das Template
$currentPage = $page;
$pageTitle = ($page === 'home') ? 'Simulation von Messgeräten' : 
             (($page === 'faq') ? 'Häufig gestellte Fragen' : 
             (($page === 'anleitung') ? 'Anleitung' : 'Impressum'));

// Bestimme die zu ladende Inhaltsdatei
$contentFile = "content/{$page}.php";

// Zusätzliche Stile je nach Seite
$additionalStyles = '';

if ($page === 'home') {
    $additionalStyles = '
        .free-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            z-index: 100;
        }
        
        .options-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin: 40px 0;
        }
        
        .option-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 30px;
            width: 300px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .option-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .option-card h3 {
            font-size: 22px;
            margin-top: 0;
            margin-bottom: 15px;
            color: #333;
        }
        
        .option-card p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }
        
        .option-button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .option-button:hover {
            background-color: #0056b3;
        }
        
        .option-button.android {
            background-color: #3ddc84;
            color: #000;
        }
        
        .option-button.android:hover {
            background-color: #32b36c;
        }
        
        .option-button.cloud {
            background-color: #007BFF;
        }
        
        .option-button.cloud:hover {
            background-color: #0056b3;
        }
        
        .features-list {
            text-align: left;
            max-width: 800px;
            margin: 0 auto 40px;
        }
        
        .features-list h3 {
            margin-bottom: 15px;
            color: #444;
        }
        
        .features-list ul {
            padding-left: 20px;
        }
        
        .features-list li {
            margin-bottom: 10px;
            color: #555;
        }
        
        /* Feature Matrix Styles */
        .feature-matrix {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .feature-matrix table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .feature-matrix th, 
        .feature-matrix td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        
        .feature-matrix th {
            background-color: #4a4a4a;
            color: white;
            font-weight: bold;
        }
        
        .feature-matrix th:first-child,
        .feature-matrix td:first-child {
            text-align: left;
            padding-left: 20px;
        }
        
        .feature-matrix tr:last-child td {
            border-bottom: none;
        }
        
        .feature-matrix tr:hover {
            background-color: #f9f9f9;
        }
        
        .feature-check {
            color: #28a745;
            font-size: 20px;
            font-style: normal;
        }
        
        .feature-no {
            color: #dc3545;
            font-size: 20px;
            font-style: normal;
        }
        
        .feature-soon {
            color: #ffc107;
            font-size: 20px;
            font-style: normal;
        }
        
        .feature-notes {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
            color: #666;
        }
        
        .feature-notes p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .image-container {
            margin: 30px auto;
            max-width: 800px;
            text-align: center;
        }
        
        .feature-image {
            max-width: 60%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .image-caption {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
            font-style: italic;
        }
        
        .app-demo-container {
            margin: 40px auto;
            max-width: 900px;
            text-align: center;
        }
        
        .demo-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px;
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .feature-image {
                max-width: 90%;
            }
        }
    ';
} else if ($page === 'faq') {
    $additionalStyles = '
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            text-align: left;
        }
        
        .faq-item {
            margin-bottom: 30px;
        }
        
        .faq-question {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        
        .faq-answer {
            font-size: 16px;
            color: #555;
            padding-left: 15px;
            border-left: 3px solid #007BFF;
        }
    ';
} else if ($page === 'anleitung') {
    $additionalStyles = '
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            text-align: left;
        }
        
        .instruction-section {
            margin-bottom: 40px;
        }
        
        .instruction-image {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .step {
            margin-bottom: 20px;
            padding-left: 20px;
            border-left: 3px solid #007BFF;
        }
        
        .step-number {
            font-weight: bold;
            color: #007BFF;
            margin-right: 10px;
        }
        
        .note {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
        
        .tip {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
        }
        
        .warning {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #dc3545;
            margin: 20px 0;
        }
    ';
} else if ($page === 'impressum') {
    $additionalStyles = '
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            text-align: left;
        }
        
        .impressum-content {
            margin-top: 20px;
        }
        
        .impressum-content p {
            margin: 10px 0;
            font-size: 16px;
            line-height: 1.5;
            text-align: left;
        }
        
        .disclaimer {
            margin-top: 30px;
            font-style: italic;
            color: #666;
            font-size: 14px;
        }
    ';
}

// Lade das Template
include('template.php');
?> 