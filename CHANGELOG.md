# Changelog

All notable changes to PDBViewerPHP will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-01-XX

### Added

- **Core Library**
  - PDBViewer class with fluent configuration API
  - Support for PDB IDs, URLs, local files, and raw data
  - Configuration classes for Structure, Representation, Appearance, and Controls
  - RendererInterface abstraction for future rendering engines
  - ThreeDMolRenderer implementation using 3Dmol.js

- **Molecular Representations**
  - Cartoon representation
  - Stick representation
  - Sphere representation
  - Line representation
  - Cross representation
  - Ribbon representation
  - Backbone representation
  - Surface representation
  - Label support

- **Color Schemes**
  - Chain coloring
  - Spectrum coloring
  - Atom coloring
  - Residue coloring
  - Cartoon coloring
  - Secondary structure coloring
  - Hydrophobicity coloring

- **Appearance Configuration**
  - Custom width and height
  - Background color customization
  - Transparent backgrounds
  - Theme system (light, dark, minimal)
  - Spin animation control
  - Initial zoom level
  - Lighting configuration
  - Camera positioning

- **UI Controls**
  - Zoom control
  - Rotation control
  - Pan control
  - Reset view control
  - Fullscreen support
  - Spin animation control
  - Screenshot functionality
  - Download control
  - Representation selection
  - Color selection
  - Surface toggle
  - Label toggle

- **Themes**
  - Light theme (default)
  - Dark theme
  - Minimal theme

- **Documentation**
  - Comprehensive README with API reference
  - 4 example files demonstrating key features
  - Interactive demo application
  - Architecture documentation
  - Security considerations guide

- **Testing**
  - Configuration class tests
  - PDBViewer functionality tests
  - 20+ passing tests

### Technical

- PHP 8.1+ with strict types
- PSR-4 autoloading
- No framework dependencies
- Safe HTML escaping and JSON serialization
- Comprehensive exception classes
- Validation for all configuration inputs

### Known Limitations

- Measurement tools not implemented
- Electron density maps not supported
- Molecular dynamics trajectories not supported
- Complex atom selection limited by 3Dmol.js
- Single renderer implementation (3Dmol.js only)

## Future Versions

Planned features for 1.1.0 and beyond:

- [ ] Advanced selection UI
- [ ] Measurement tools
- [ ] Electron density map support
- [ ] Additional molecular renderers
- [ ] Plugin system
- [ ] Session persistence
- [ ] Sequence-structure linking
- [ ] Performance optimizations
