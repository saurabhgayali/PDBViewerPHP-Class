<?php
/**
 * PDBViewerPHP Demo Application
 * 
 * Run with: php -S localhost:8000
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PDBViewerPHP\PDBViewer;
use PDBViewerPHP\Configuration\{RepresentationType, ColorScheme, Theme};

// Get form values
$pdbId = $_GET['pdb_id'] ?? '1ABC';
$representation = $_GET['representation'] ?? 'cartoon';
$colorScheme = $_GET['color_scheme'] ?? 'chain';
$theme = $_GET['theme'] ?? 'light';
$spin = isset($_GET['spin']) ? $_GET['spin'] === '1' : false;
$showDownload = isset($_GET['show_download']) ? $_GET['show_download'] === '1' : false;

// Create viewer
$viewer = new PDBViewer();
$viewer->loadPDB($pdbId)
    ->setDimensions(800, 700)
    ->setTheme(Theme::tryFrom($theme) ?? Theme::LIGHT)
    ->setSpin($spin);

// Set representation
if ($representation === 'stick') {
    $viewer->setRepresentation(RepresentationType::STICK);
} elseif ($representation === 'sphere') {
    $viewer->setRepresentation(RepresentationType::SPHERE);
} else {
    $viewer->setRepresentation(RepresentationType::CARTOON);
}

// Set color scheme
if ($colorScheme === 'spectrum') {
    $viewer->setColorScheme(ColorScheme::SPECTRUM);
} elseif ($colorScheme === 'residue') {
    $viewer->setColorScheme(ColorScheme::RESIDUE);
} else {
    $viewer->setColorScheme(ColorScheme::CHAIN);
}

// Control download
if (!$showDownload) {
    $viewer->hideDownloadControl();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>PDBViewerPHP Demo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #e5e5e5;
            min-height: 100vh;
            padding: 10px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: #6b6b6b;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 1.6em;
            margin-bottom: 5px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 0.95em;
        }
        
        .content {
            display: grid;
            grid-template-columns: 220px 1fr;
            min-height: 400px;
        }
        
        .sidebar {
            background: #f5f5f5;
            border-right: 1px solid #d0d0d0;
            padding: 15px;
            overflow-y: auto;
        }
        
        .sidebar h3 {
            font-size: 0.95em;
            margin-bottom: 12px;
            color: #333;
        }
        
        .main {
            padding: 15px;
            position: relative;
            overflow: hidden;
        }
        
        .form-group {
            margin-bottom: 12px;
        }
        
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
            font-size: 0.85em;
        }
        
        input[type="text"],
        select {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 0.85em;
            font-family: inherit;
        }
        
        input[type="text"]:focus,
        select:focus {
            outline: none;
            border-color: #999;
            box-shadow: 0 0 0 2px rgba(128, 128, 128, 0.1);
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            cursor: pointer;
        }
        
        .checkbox-group label {
            margin: 0;
            cursor: pointer;
            user-select: none;
            font-weight: 400;
            font-size: 0.85em;
        }
        
        button {
            width: 100%;
            padding: 8px;
            background: #8a8a8a;
            color: white;
            border: none;
            border-radius: 3px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.85em;
            transition: background-color 0.2s;
        }
        
        button:hover {
            background: #737373;
        }
        
        button:active {
            background: #6b6b6b;
        }
        
        .viewer-wrapper {
            background: white;
            border-radius: 3px;
            border: 1px solid #d0d0d0;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .info-box {
            background: #f0f0f0;
            border-left: 4px solid #888;
            padding: 10px;
            margin-top: 12px;
            border-radius: 2px;
            font-size: 0.8em;
            color: #555;
        }
        
        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                border-right: none;
                border-bottom: 1px solid #d0d0d0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PDBViewerPHP Demo</h1>
            <p>Interactive Molecular Structure Viewer</p>
        </div>
        
        <div class="content">
            <div class="sidebar">
                <h3 style="margin-bottom: 15px;">Configuration</h3>
                <form method="get">
                    <div class="form-group">
                        <label for="pdb_id">PDB ID</label>
                        <input type="text" id="pdb_id" name="pdb_id" value="<?php echo htmlspecialchars($pdbId); ?>" placeholder="e.g., 1ABC">
                    </div>
                    
                    <div class="form-group">
                        <label for="representation">Representation</label>
                        <select id="representation" name="representation">
                            <option value="cartoon" <?php echo $representation === 'cartoon' ? 'selected' : ''; ?>>Cartoon</option>
                            <option value="stick" <?php echo $representation === 'stick' ? 'selected' : ''; ?>>Stick</option>
                            <option value="sphere" <?php echo $representation === 'sphere' ? 'selected' : ''; ?>>Sphere</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="color_scheme">Color Scheme</label>
                        <select id="color_scheme" name="color_scheme">
                            <option value="chain" <?php echo $colorScheme === 'chain' ? 'selected' : ''; ?>>Chain</option>
                            <option value="spectrum" <?php echo $colorScheme === 'spectrum' ? 'selected' : ''; ?>>Spectrum</option>
                            <option value="residue" <?php echo $colorScheme === 'residue' ? 'selected' : ''; ?>>Residue</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="theme">Theme</label>
                        <select id="theme" name="theme">
                            <option value="light" <?php echo $theme === 'light' ? 'selected' : ''; ?>>Light</option>
                            <option value="dark" <?php echo $theme === 'dark' ? 'selected' : ''; ?>>Dark</option>
                            <option value="minimal" <?php echo $theme === 'minimal' ? 'selected' : ''; ?>>Minimal</option>
                        </select>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="spin" name="spin" value="1" <?php echo $spin ? 'checked' : ''; ?>>
                        <label for="spin">Auto Spin</label>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="show_download" name="show_download" value="1" <?php echo $showDownload ? 'checked' : ''; ?>>
                        <label for="show_download">Enable Download</label>
                    </div>
                    
                    <button type="submit">Update Viewer</button>
                </form>
                
                <div class="info-box">
                    <strong>Tip:</strong> All configuration is done server-side using PHP!
                </div>
            </div>
            
            <div class="main">
                <div class="viewer-wrapper">
                    <?php echo $viewer->render(); ?>
                </div>
                
                <div style="margin-top: 12px; padding: 10px; background: #f5f5f5; border-radius: 3px; border: 1px solid #d0d0d0;">
                    <p style="font-size: 0.85em; font-weight: 600; margin-bottom: 5px;"><strong>Current Configuration:</strong></p>
                    <code style="font-size: 0.75em; color: #666;">
                        PDB: <strong><?php echo htmlspecialchars($pdbId); ?></strong> | 
                        Representation: <strong><?php echo htmlspecialchars($representation); ?></strong> | 
                        Color Scheme: <strong><?php echo htmlspecialchars($colorScheme); ?></strong> | 
                        Theme: <strong><?php echo htmlspecialchars($theme); ?></strong>
                    </code>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
