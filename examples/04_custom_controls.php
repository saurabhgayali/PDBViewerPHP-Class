<?php
/**
 * Example 4: Control Customization
 * 
 * Demonstrates enabling/disabling various UI controls
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PDBViewerPHP\PDBViewer;
use PDBViewerPHP\Configuration\RepresentationType;

$viewer = new PDBViewer();
$viewer->loadPDB('3Z0F')
    ->setDimensions(800, 600)
    ->setRepresentation(RepresentationType::STICK)
    // Enable common controls
    ->showZoomControl()
    ->showRotateControl()
    ->showResetControl()
    ->showFullscreenControl()
    // Disable download
    ->hideDownloadControl();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Custom Controls</title>
</head>
<body>
    <h1>Custom Controls Example</h1>
    <p>Demonstrating selective control availability</p>
    
    <div style="background: #f9f9f9; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
        <strong>Available Controls:</strong>
        <ul>
            <li>Zoom</li>
            <li>Rotate</li>
            <li>Reset View</li>
            <li>Fullscreen</li>
        </ul>
        <strong>Disabled Controls:</strong>
        <ul>
            <li>Download</li>
        </ul>
    </div>
    
    <?php echo $viewer->render(); ?>
</body>
</html>
