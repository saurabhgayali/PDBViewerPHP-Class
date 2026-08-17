# PDBViewerPHP

**A PHP-first wrapper for [3Dmol.js](https://3dmol.csb.pitt.edu/) for embedding configurable molecular structure viewers in PHP applications.**

PDBViewerPHP lets PHP developers create and configure an interactive molecular viewer without writing the JavaScript required to initialize and configure 3Dmol.js themselves.

> **PHP configures it. 3Dmol.js renders it.**

[![PHP](https://img.shields.io/badge/PHP-%3E%3D8.1-777BB4?logo=php\&logoColor=white)](https://www.php.net/)
[![3Dmol.js](https://img.shields.io/badge/3Dmol.js-2.x-blue)](https://3dmol.csb.pitt.edu/)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

---

## Why PDBViewerPHP?

[3Dmol.js](https://3dmol.csb.pitt.edu/) already provides the molecular visualization engine. PDBViewerPHP does not attempt to replace it.

The purpose of this project is to make 3Dmol.js easier to integrate into **PHP applications** by providing a PHP-side configuration and embedding layer.

Instead of manually writing HTML and JavaScript to initialize a molecular viewer, a PHP application can define the viewer configuration and let PDBViewerPHP generate the required client-side code.

### The concept

```text
PHP Application
      │
      ▼
 PDBViewerPHP
      │
      ├── Structure
      ├── Representation
      ├── Appearance
      ├── Theme
      └── Viewer UI
      │
      ▼
Generated HTML + JavaScript
      │
      ▼
   3Dmol.js
      │
      ▼
Browser / WebGL
```

PDBViewerPHP handles the PHP/developer-facing layer.

3Dmol.js remains responsible for molecular visualization and WebGL rendering.

---

## Features

### Structure visualization

Supports structure sources provided through the underlying 3Dmol.js capabilities, including:

* PDB identifiers
* PDB URLs
* Browser-accessible structure files
* Raw PDB data
* mmCIF where supported

### Molecular representations

Configure common molecular representations such as:

* Cartoon
* Stick
* Sphere
* Line
* Cross
* Ribbon
* Backbone
* Surface

Representations can be associated with molecular selections where supported.

### Appearance

Configure:

* Viewer dimensions
* Background
* Molecular colors
* Color schemes
* Initial zoom
* Initial orientation
* Spin
* Lighting-related viewer settings

### Viewer interface

PDBViewerPHP provides a lightweight interface around the 3Dmol.js viewer.

The PHP developer can configure the availability of interface features such as:

* Reset view
* Fullscreen
* Spin
* Screenshot/image export
* Representation selection
* Color selection
* Surface visibility
* Labels

Native molecular interaction such as mouse/touch rotation and zoom is provided by 3Dmol.js.

### Themes

The generated PDBViewerPHP interface can use predefined themes such as light, dark, or minimal styling.

The interface theme and molecular rendering configuration are separate concepts.

---

## Quick Start

Install the package with Composer:

```bash
composer require saurabhgayali/pdbviewerphp
```

A minimal viewer can then be created from PHP by providing a structure and rendering configuration.

The library generates the required HTML and JavaScript, while 3Dmol.js is loaded from its CDN.

See the [`examples/`](examples/) directory for complete examples.

---

## What the PHP Layer Controls

The main purpose of PDBViewerPHP is to move common viewer configuration into PHP.

For example, a PHP application can determine:

| Category       | Examples                           |
| -------------- | ---------------------------------- |
| Structure      | PDB ID, URL, raw structure data    |
| Representation | Cartoon, sticks, spheres, surfaces |
| Appearance     | Background, colors, dimensions     |
| View           | Zoom, orientation, spin            |
| Interface      | Available toolbar features         |
| Theme          | Light, dark, minimal               |
| Rendering      | 3Dmol.js viewer configuration      |

This makes the viewer suitable for applications where the **server-side application determines how the structure should initially be presented**.

---

## Typical Use Cases

PDBViewerPHP can be used as a molecular visualization component in:

* Scientific websites
* Protein databases
* Bioinformatics portals
* Research project websites
* Pharmaceutical and biotechnology applications
* Educational molecular visualization
* Laboratory information systems
* PHP-based scientific tools
* CMS-based scientific websites
* Internal research applications

For example, a protein database could display every structure using the same PHP viewer configuration while changing only the structure and annotations.

An educational website could provide a simplified interface with only basic viewing controls.

A research portal could use a more advanced configuration with surfaces, labels, ligand visualization, and multiple representations.

---

## Architecture

PDBViewerPHP intentionally separates the application layer from the molecular rendering engine.

```text
┌──────────────────────────────┐
│       PHP Application        │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│        PDBViewerPHP          │
│                              │
│ Configuration                │
│ Structure                    │
│ Representation               │
│ Appearance                   │
│ UI / Themes                  │
└──────────────┬───────────────┘
               │
               ▼
       Generated HTML/JS
               │
               ▼
┌──────────────────────────────┐
│          3Dmol.js            │
│                              │
│ Molecular rendering          │
│ Structure visualization      │
│ WebGL                        │
└──────────────┬───────────────┘
               │
               ▼
            Browser
```

A renderer abstraction is used so that alternative molecular rendering engines could potentially be supported in the future.

The initial implementation uses **3Dmol.js only**.

---

## 3Dmol.js Dependency

PDBViewerPHP relies on 3Dmol.js for client-side molecular visualization.

3Dmol.js is loaded from a CDN rather than bundled into the PHP package.

This keeps the PHP library lightweight and avoids duplicating the molecular rendering engine.

The project should be considered dependent on the capabilities and browser compatibility of the 3Dmol.js version used by the renderer.

---

## Browser-Side Reality

PDBViewerPHP configures the viewer from PHP, but the resulting viewer runs in the user's browser.

Therefore, PHP-side configuration should not be confused with browser-side security.

For example, disabling a download/export control can prevent the library's UI from exposing that feature, but it cannot guarantee that a user cannot access data that has already been delivered to their browser.

---

## Current Limitations

This project currently focuses on **structure visualization and viewer integration**.

It is not intended to provide:

* Molecular dynamics visualization
* Electron-density map visualization
* Trajectory analysis
* Full molecular modelling
* Advanced molecular analysis
* A replacement for PyMOL or ChimeraX
* A complete molecular structure database

Some advanced functionality may be added in future versions.

The available API should be considered **pre-1.0 and subject to change**.

---

## Project Structure

```text
PDBViewerPHP-Class/
├── src/             PHP library
├── tests/           PHPUnit tests
├── examples/        Usage examples
├── demo/            Standalone PHP demonstration
├── assets/          Client-side assets
├── docs/            Project documentation / GitHub Pages
├── composer.json
├── phpunit.xml
├── README.md
├── CONCEPT.md
└── LICENSE
```

---

## Development

Clone the repository and install the Composer dependencies.

Run the test suite with PHPUnit.

The `demo/` directory contains a standalone PHP demonstration that can be run using a PHP-enabled development environment.

The project does not require Laravel, Symfony, WordPress, or another PHP framework.

---

## Roadmap

Possible future directions include:

* Improved viewer controls
* More advanced selection tools
* Measurement functionality
* Structure annotations
* Sequence/structure interaction
* Binding-site presets
* Additional molecular rendering engines
* Additional viewer themes
* Better server-side structure handling
* Framework/CMS integrations

The roadmap is intentionally flexible while the core API is being established.

---

## License

PDBViewerPHP is released under the [MIT License](LICENSE).

3Dmol.js is a separate project with its own license and terms. See the 3Dmol.js project for details.

---

## Credits

PDBViewerPHP uses [3Dmol.js](https://3dmol.csb.pitt.edu/) for molecular visualization.

**PDBViewerPHP:** PHP configuration and integration layer
**3Dmol.js:** Molecular visualization and WebGL rendering
