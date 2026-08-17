<?php
/**
 * Example 1: Minimal PDB Viewer
 * 
 * The simplest possible viewer showing a PDB structure
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PDBViewerPHP\PDBViewer;

$viewer = new PDBViewer();
$viewer->loadPDB('1ABC')
    ->setDimensions(600, 600);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Minimal PDB Viewer</title>
</head>
<body>
    <h1>Minimal PDB Viewer Example</h1>
    <p>Loading structure 1ABC from PDB...</p>
    
    <?php echo $viewer->render(); ?>
</body>
</html>
