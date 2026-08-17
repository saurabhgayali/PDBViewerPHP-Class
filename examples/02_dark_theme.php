<?php
/**
 * Example 2: Custom Appearance and Theme
 * 
 * Demonstrates configuring appearance and using themes
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PDBViewerPHP\PDBViewer;
use PDBViewerPHP\Configuration\{RepresentationType, Theme};

$viewer = new PDBViewer();
$viewer->loadPDB('2WHZ')
    ->setDimensions(800, 600)
    ->setTheme(Theme::DARK)
    ->setBackgroundColor('#1e1e1e')
    ->setRepresentation(RepresentationType::CARTOON)
    ->setSpinEnabled(false);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Dark Theme Viewer</title>
    <style>
        body { 
            background: #222; 
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 20px;
        }
    </style>
</head>
<body>
    <h1>Dark Theme Viewer</h1>
    <p>Displaying 2WHZ in dark theme with cartoon representation</p>
    
    <?php echo $viewer->render(); ?>
</body>
</html>
