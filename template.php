<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>CBRN-TRAINER - <?php echo $pageTitle; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 0;
            margin: 0;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #333;
        }

        h2 {
            font-size: 24px;
            margin: 30px 0 15px;
            color: #444;
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #666;
            line-height: 1.6;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 10px;
            flex-wrap: wrap;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .device-icons {
            display: flex;
            flex-direction: row;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .device-icons img {
            height: 50px;
            object-fit: contain;
        }

        .main-menu {
            display: flex;
            justify-content: center;
            background-color: #4a4a4a;
            padding: 10px 0;
            margin-bottom: 30px;
        }

        .main-menu a {
            color: white;
            text-decoration: none;
            padding: 8px 20px;
            margin: 0 5px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .main-menu a:hover {
            background-color: #666;
        }

        .main-menu a.active {
            background-color: #007BFF;
        }

        .content-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #f4f4f4;
            color: #666;
            font-size: 0.9em;
            border-top: 1px solid #ddd;
        }

        footer a {
            color: #666;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Weitere Stile aus der Startseite hier einfügen */
        /* ... */

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
            }
            
            .main-menu {
                flex-wrap: wrap;
            }
            
            .main-menu a {
                margin: 5px;
            }
        }
    </style>
    <?php if (isset($additionalStyles)): ?>
    <style>
        <?php echo $additionalStyles; ?>
    </style>
    <?php endif; ?>
</head>
<body>
    <div class="header-container">
        <div class="device-icons">
            <img src="c/icons/co_meter.php" alt="CO Warngerät">
            <img src="c/icons/multi_meter.php" alt="Multiwarngerät">
        </div>
        <h1>CBRN-TRAINER</h1>
        <div class="device-icons">
            <img src="c/icons/dosisleistung_meter.php" alt="Dosisleistungsmessgerät">
            <img src="c/icons/dl_warner.php" alt="DL-Warner">
            <img src="c/icons/dosis_warner.php" alt="Dosiswarngerät">
        </div>
    </div>

    <div class="main-menu">
        <a href="index.php" <?php echo ($currentPage === 'home') ? 'class="active"' : ''; ?>>Startseite</a>
        <a href="index.php?page=anleitung" <?php echo ($currentPage === 'anleitung') ? 'class="active"' : ''; ?>>Anleitung</a>
        <a href="index.php?page=faq" <?php echo ($currentPage === 'faq') ? 'class="active"' : ''; ?>>FAQ</a>
        <a href="index.php?page=impressum" <?php echo ($currentPage === 'impressum') ? 'class="active"' : ''; ?>>Impressum</a>
    </div>

    <div class="content-container">
        <?php include($contentFile); ?>
    </div>

    <footer>
        Privates Projekt im Alpha-Status. Keine Gewährleistung. | 
        <a href="index.php?page=faq">FAQ</a> |
        <a href="index.php?page=impressum">Impressum</a> |
        <a href="mailto:info@cbrn-trainer.de">Kontakt</a> | 
        &copy; <?php echo date('Y'); ?>
    </footer>
</body>
</html> 