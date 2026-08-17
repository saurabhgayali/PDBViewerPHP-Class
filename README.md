# PDBViewerPHP

A PHP library for embedding interactive molecular structure viewers into PHP applications, using **3Dmol.js** as the client-side rendering engine.

> **3Dmol.js is the molecular rendering engine. PDBViewerPHP is the PHP configuration, embedding, and UI-control layer.**

## What is PDBViewerPHP?

PDBViewerPHP allows PHP developers to create configurable, interactive molecular viewers without writing JavaScript. The library provides a clean PHP API for server-side configuration of viewer appearance, molecular representations, and user controls—all without requiring direct knowledge of 3Dmol.js.

### Key Features

- **PHP-First API**: Configure everything in PHP with a fluent interface
- **No JavaScript Required**: PHP developers don't need to write JavaScript
- **Server-Controlled UI**: Decide which controls are available to users
- **Multiple Representation Types**: Cartoon, stick, sphere, ribbon, surface, and more
- **Configurable Appearance**: Colors, themes, backgrounds, dimensions, lighting
- **Flexible Structure Sources**: PDB IDs, URLs, local files, or raw data
- **Minimal Dependencies**: No framework dependencies, just PHP 8.1+
- **Security-Conscious**: Safe HTML escaping and configuration serialization
- **Multiple Themes**: Light, dark, and minimal built-in themes

## Installation

### Requirements

- PHP 8.1 or higher
- Composer
- Modern web browser with WebGL support

### Setup

```bash
composer require saurabhgayali/pdbviewerphp
```

Or clone the repository:

```bash
git clone https://github.com/saurabhgayali/PDBViewerPHP-Class.git
cd PDBViewerPHP-Class
composer install
```

## Quick Start

### Minimal Example

```php
<?php
require_once 'vendor/autoload.php';

use PDBViewerPHP\PDBViewer;

$viewer = new PDBViewer();
$viewer->loadPDB('1ABC')
    ->setDimensions(600, 600);

echo $viewer->render();
?>
```

### With Customization

```php
<?php
require_once 'vendor/autoload.php';

use PDBViewerPHP\PDBViewer;
use PDBViewerPHP\Configuration\{RepresentationType, ColorScheme, Theme};

$viewer = new PDBViewer();
$viewer->loadPDB('1ABC')
    ->setDimensions(800, 600)
    ->setTheme(Theme::DARK)
    ->setRepresentation(RepresentationType::CARTOON)
    ->setColorScheme(ColorScheme::CHAIN)
    ->setBackgroundColor('#1e1e1e')
    ->hideDownloadControl();

echo $viewer->render();
?>
```

## API Reference

### PDBViewer Class

The main class for creating and configuring viewers. Uses a fluent interface for chainable method calls.

#### Structure Loading

```php
// Load from PDB ID
$viewer->loadPDB('1ABC');

// Load from PDB URL
$viewer->loadFromPdbUrl('https://files.rcsb.org/download/1ABC.pdb');

// Load from mmCIF URL
$viewer->loadFromMmCifUrl('https://mmcif.example.com/structure.cif');

// Load from local file
$viewer->loadFromFile('/path/to/structure.pdb');

// Load from raw data
$viewer->loadFromRawData($pdbData, 'pdb');
```

#### Molecular Representations

```php
use PDBViewerPHP\Configuration\RepresentationType;

// Set primary representation
$viewer->setRepresentation(RepresentationType::CARTOON);
$viewer->setRepresentation(RepresentationType::STICK);
$viewer->setRepresentation(RepresentationType::SPHERE);
$viewer->setRepresentation(RepresentationType::RIBBON);
$viewer->setRepresentation(RepresentationType::LINE);
$viewer->setRepresentation(RepresentationType::CROSS);
$viewer->setRepresentation(RepresentationType::SURFACE);

// Add multiple representations
$viewer->addRepresentation(RepresentationType::CARTOON);
$viewer->addRepresentation(RepresentationType::STICK);
```

#### Color Schemes

```php
use PDBViewerPHP\Configuration\ColorScheme;

$viewer->setColorScheme(ColorScheme::CHAIN);      // Color by chain
$viewer->setColorScheme(ColorScheme::SPECTRUM);   // Spectrum gradient
$viewer->setColorScheme(ColorScheme::ATOM);       // Atom type colors
$viewer->setColorScheme(ColorScheme::RESIDUE);    // Residue type colors
$viewer->setColorScheme(ColorScheme::CARTOON);    // Cartoon colors
$viewer->setColorScheme(ColorScheme::SSTYPE);     // Secondary structure
$viewer->setColorScheme(ColorScheme::HYDRO);      // Hydrophobicity
```

#### Appearance Configuration

```php
// Dimensions
$viewer->setWidth(800);
$viewer->setHeight(600);
$viewer->setDimensions(800, 600);

// Background
$viewer->setBackgroundColor('#FFFFFF');
$viewer->setBackgroundTransparent(true);

// Themes
use PDBViewerPHP\Configuration\Theme;
$viewer->setTheme(Theme::LIGHT);
$viewer->setTheme(Theme::DARK);
$viewer->setTheme(Theme::MINIMAL);

// Animation
$viewer->setSpin(true);
$viewer->setSpin(false);

// Initial zoom
$viewer->setZoom(1.5);
```

#### UI Controls

Enable or disable user controls:

```php
// Standard method
$viewer->setZoomControlEnabled(true);
$viewer->setRotateControlEnabled(false);
$viewer->setPanControlEnabled(true);
$viewer->setResetControlEnabled(true);
$viewer->setFullscreenControlEnabled(true);
$viewer->setSpinControlEnabled(false);
$viewer->setScreenshotControlEnabled(true);
$viewer->setDownloadControlEnabled(false);

// Convenience methods
$viewer->showZoomControl();
$viewer->hideDownloadControl();
$viewer->showFullscreenControl();
$viewer->hideScreenshotControl();
$viewer->showRotateControl();
```

#### Rendering

```php
// Render complete HTML with external resources
echo $viewer->render();

// Render just the container
$html = $viewer->renderHtml();

// Render just the JavaScript
$js = $viewer->renderJavaScript();

// Get configuration as JSON
$config = $viewer->getConfigurationJson();
```

## Configuration Classes

### StructureConfiguration

Manages structure source configuration.

```php
use PDBViewerPHP\Configuration\StructureConfiguration;

$config = new StructureConfiguration();
$config->setPdbId('1ABC');
$config->getSourceType();        // Returns 'pdb_id'
$config->isConfigured();         // Returns true/false
$config->toArray();              // For serialization
```

### RepresentationConfiguration

Manages molecular representation settings.

```php
use PDBViewerPHP\Configuration\RepresentationConfiguration;

$config = new RepresentationConfiguration();
$config->setRepresentation(RepresentationType::CARTOON);
$config->setColorScheme(ColorScheme::CHAIN);
$config->getRepresentations();
```

### AppearanceConfiguration

Manages viewer appearance settings.

```php
use PDBViewerPHP\Configuration\AppearanceConfiguration;

$config = new AppearanceConfiguration();
$config->setDimensions(800, 600);
$config->setBackgroundColor('#FFFFFF');
$config->setTheme(Theme::DARK);
$config->getWidth();
$config->getHeight();
$config->getTheme();
```

### ControlConfiguration

Manages UI control availability.

```php
use PDBViewerPHP\Configuration\ControlConfiguration;

$config = new ControlConfiguration();
$config->setZoomEnabled(true);
$config->setDownloadEnabled(false);
$config->isZoomEnabled();
```

## Themes

PDBViewerPHP includes three built-in themes:

### Light Theme (Default)
Clean, bright interface suitable for most applications.

```php
$viewer->setTheme(Theme::LIGHT);
```

### Dark Theme
Dark interface with high contrast, suitable for dark-mode websites.

```php
$viewer->setTheme(Theme::DARK);
```

### Minimal Theme
Minimalist interface with hidden controls by default.

```php
$viewer->setTheme(Theme::MINIMAL);
```

## Examples

The `examples/` directory includes working examples:

- `01_minimal.php` - Simplest possible viewer
- `02_dark_theme.php` - Dark theme configuration
- `03_representation.php` - Different representations
- `04_custom_controls.php` - Control customization

Run an example:

```bash
php -S localhost:8000 examples/01_minimal.php
```

## Demo Application

A complete interactive demo is included:

```bash
php -S localhost:8000 demo/index.php
```

Then open `http://localhost:8000` in your browser. The demo allows you to:

- Enter a PDB ID
- Select representation type
- Choose color scheme
- Switch themes
- Toggle controls
- Enable/disable features

## Architecture

PDBViewerPHP is built around a clean separation of concerns:

```
PHP Application
    ↓
PDBViewerPHP (Server-side configuration)
    ├── Structure Configuration
    ├── Representation Configuration
    ├── Appearance Configuration
    ├── Control Configuration
    └── Renderer (RendererInterface)
    ↓
Generated HTML + JSON Configuration + JavaScript Adapter
    ↓
3Dmol.js (Client-side molecular rendering)
    ↓
WebGL (Browser rendering)
```

### PDBViewerPHP Responsibilities

- Server-side viewer configuration
- UI control availability management
- HTML generation
- Safe configuration serialization
- Theme application

### 3Dmol.js Responsibilities

- WebGL rendering
- Molecular visualization
- Structure parsing and loading
- Atom/residue selection
- Interactive controls (zoom, rotate, pan)

### Browser Responsibilities

- User interaction
- Canvas rendering
- Fullscreen API
- Screenshot/download functionality

## Security Considerations

### Security Features

1. **HTML Escaping**: All user-controlled output is properly escaped for HTML context
2. **JSON Serialization**: Configuration is safely serialized to JSON without creating arbitrary JavaScript
3. **Configuration Validation**: All configuration values are validated before rendering
4. **Attribute Escaping**: HTML attributes are properly quoted and escaped

### Important Notes

**PDBViewerPHP is NOT a security boundary.**

Once molecular data is sent to the browser, a sufficiently technical user can potentially access that data regardless of PDBViewerPHP's UI controls. The "disable download" feature is a **UI restriction**, not a security mechanism.

If you have sensitive data:
- Use HTTPS to encrypt transmission
- Implement server-side access controls
- Don't rely on browser-side restrictions
- Consider data classification and need-to-know

## 3Dmol.js Dependency

PDBViewerPHP uses **3Dmol.js 2.0.1** from the official CDN by default:

```
https://3Dmol.csb.pitt.edu/build/3Dmol-min.js
https://3Dmol.csb.pitt.edu/build/3Dmol-min.css
```

### Changing the Version

```php
use PDBViewerPHP\Renderer\ThreeDMolRenderer;

$renderer = new ThreeDMolRenderer('2.0.0', 'https://3Dmol.csb.pitt.edu');
$viewer->setRenderer($renderer);
```

### 3Dmol.js Features Used

- Structure loading (PDB, mmCIF formats)
- Molecular representations (cartoon, stick, sphere, etc.)
- Selection and highlighting
- Camera and zoom control
- Canvas rendering
- Color schemes

See the [3Dmol.js documentation](https://3Dmol.csb.pitt.edu/3Dmol/AlphaFold) for more information.

## Browser Compatibility

PDBViewerPHP works in all modern browsers with WebGL support:

- Chrome/Chromium 60+
- Firefox 55+
- Safari 11+
- Edge 79+
- Opera 47+

### Known Limitations

- **Measurement Tools**: Distance/angle measurements are not yet implemented
- **Electron Density Maps**: Map visualization is not currently supported
- **Animation Sequences**: Molecular dynamics trajectories are not supported
- **Advanced Selection**: Complex atom selection syntax may be limited

See the roadmap for planned features.

## Testing

Run the test suite:

```bash
php run_tests.php
```

This runs 20+ tests covering:

- Configuration classes
- Fluent API
- HTML/JavaScript generation
- JSON serialization
- Control configuration
- Theme application

## Roadmap

Potential future enhancements:

- [ ] Measurement tools (distance, angles)
- [ ] Electron density maps
- [ ] Molecular dynamics visualization
- [ ] Advanced selection UI
- [ ] Predefined viewer presets
- [ ] Additional renderers (NGL, Mol*)
- [ ] Session persistence
- [ ] Sequence-structure interaction
- [ ] Additional themes
- [ ] Plugin system

## API Stability

The current API is **stable** for v1. Public methods may be extended but existing signatures will not break.

Configuration classes and enums are stable. New enum values may be added in minor versions.

## Code Quality

PDBViewerPHP follows these standards:

- **PHP 8.1+** with strict types
- **PSR-4** autoloading
- **Meaningful exceptions** for error cases
- **Small focused classes** with single responsibilities
- **Comprehensive PHPDoc** documentation
- **Fluent interfaces** for usability

## Contributing

Contributions are welcome! Please:

1. Follow the existing code style
2. Add tests for new features
3. Update documentation
4. Keep the API clean and simple
5. Maintain backward compatibility

## License

MIT License - See LICENSE file for details

## Support

For issues, questions, or suggestions:

- GitHub Issues: [PDBViewerPHP Issues](https://github.com/saurabhgayali/PDBViewerPHP-Class/issues)
- Documentation: See the examples/ and demo/ directories

## Credits

- **3Dmol.js**: Molecular visualization engine by David Goodsell, RCSB PDB
- **3Dmol.js CDN**: Hosted by the Pittsburgh Supercomputing Center

## See Also

- [3Dmol.js Documentation](https://3Dmol.csb.pitt.edu/3Dmol/AlphaFold)
- [RCSB PDB](https://www.rcsb.org)
- [mmCIF Format](https://mmcif.wwpdb.org/)
