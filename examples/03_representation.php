<?php
/**
 * Example 3: Representation Switching
 * 
 * Shows cartoon, stick, and surface representations
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PDBViewerPHP\PDBViewer;
use PDBViewerPHP\Configuration\{RepresentationType, ColorScheme};

$viewer = new PDBViewer();
$viewer->loadPDB('1BNA')
    ->setDimensions(700, 600)
    ->setRepresentation(RepresentationType::CARTOON)
    ->setColorScheme(ColorScheme::SPECTRUM);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Representation Example</title>
</head>
<body>
    <h1>Protein Representation Example</h1>
    <p>Displaying 1BNA with spectrum coloring and cartoon representation</p>
    
    <?php echo $viewer->render(); ?>
    
    <p style="margin-top: 20px;">
        <strong>Note:</strong> The representation and color scheme are set server-side 
        through PHP configuration.
    </p>
</body>
</html>
