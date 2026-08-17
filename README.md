# PDBViewerPHP

**A PHP class for server-configured molecular structure visualization**

> **Project status:** Concept / development brief. This is not the final project documentation.

## Project Concept

PDBViewerPHP is a reusable PHP library for embedding interactive molecular structure viewers into PHP websites and applications.

It uses **3Dmol.js as the client-side molecular rendering engine**, loaded from a CDN.

The purpose of PDBViewerPHP is **not to replace 3Dmol.js**. Instead, it provides a PHP-oriented abstraction over it.

The central idea is:

> **The PHP developer defines the viewer configuration and user permissions on the server; 3Dmol.js renders and interacts with the structure in the browser.**

This makes it possible for a PHP developer to add a controlled molecular viewer without having to build the JavaScript integration manually.

---

## The Problem

Libraries such as 3Dmol.js already provide excellent molecular rendering capabilities, but directly integrating a JavaScript molecular viewer into a PHP application still requires developers to manage:

* JavaScript initialization
* Viewer configuration
* Structure loading
* Representation configuration
* UI creation
* Viewer controls
* HTML/JavaScript integration
* Browser-side functionality
* Configuration serialization

PDBViewerPHP aims to make this simpler by providing a **PHP-first interface**.

The developer should be able to think in terms of:

> "Create a protein viewer with this structure, this representation, this theme, and these controls."

rather than:

> "Write JavaScript to initialize 3Dmol.js, create the toolbar, connect every button, and serialize the configuration."

---

# Core Concept

PDBViewerPHP sits between the PHP application and 3Dmol.js.

```text
PHP Application
       │
       ▼
  PDBViewerPHP
       │
       ├── Structure configuration
       ├── Appearance configuration
       ├── Representation configuration
       ├── UI configuration
       └── User-control configuration
       │
       ▼
 Generated HTML / JSON / JavaScript
       │
       ▼
   3Dmol.js CDN
       │
       ▼
 Browser / WebGL
```

The division of responsibility is intentional.

### PDBViewerPHP

Responsible for:

* Server-side configuration
* Viewer setup
* UI configuration
* Control availability
* HTML generation
* Safe configuration serialization
* PHP developer experience

### 3Dmol.js

Responsible for:

* Molecular rendering
* WebGL
* Molecular representations
* Structure parsing/loading
* Atom selections
* Molecular visualization
* Browser-side molecular interaction

### Browser

Responsible for:

* User interaction
* Fullscreen
* Canvas operations
* Download/screenshot mechanisms where applicable
* Rendering the generated interface

---

# Server-Controlled Viewer

One of the main concepts of PDBViewerPHP is **server-defined viewer behavior**.

A website administrator should be able to decide what the visitor is allowed to do.

For example, a website may provide:

### Research Viewer

* Rotation enabled
* Zoom enabled
* Representation switching enabled
* Surface visualization enabled
* Labels enabled
* Screenshot enabled
* Download disabled

### Public Education Viewer

* Rotation enabled
* Zoom enabled
* Representation switching disabled
* Advanced controls hidden
* Download disabled
* Simplified interface

### Publication Viewer

* Fixed representation
* Fixed background
* Fixed camera
* Minimal controls
* No unnecessary UI

### Internal Research Tool

* Full controls
* Multiple representations
* Selection tools
* Labels
* Surfaces
* Download
* Advanced interaction

The same molecular rendering engine can therefore be used for very different applications simply by changing the PHP-side configuration.

---

# What PDBViewerPHP Is Not

PDBViewerPHP is **not intended to be**:

* A replacement for 3Dmol.js
* A new WebGL molecular rendering engine
* A complete molecular modelling application
* A molecular dynamics viewer
* A protein structure analysis package
* A replacement for PyMOL, ChimeraX, or similar desktop applications
* A JavaScript framework

The project should remain focused on **embedding and controlling molecular visualization in PHP applications**.

---

# Supported Structure Concepts

The library should be designed around common molecular structure sources supported by 3Dmol.js.

Potential sources include:

* PDB identifiers
* PDB files
* PDB URLs
* mmCIF structures
* Raw structure data
* Server-hosted structure files

The PHP API should abstract the source sufficiently that the developer does not need to manually construct the corresponding JavaScript loading code.

---

# Visualization Concepts

PDBViewerPHP should expose the major visualization capabilities of 3Dmol.js through PHP configuration.

These include concepts such as:

* Cartoon representation
* Stick representation
* Sphere representation
* Line representation
* Surface representation
* Ribbon/backbone visualization
* Atom selection
* Residue selection
* Chain selection
* Molecular coloring
* Labels
* Multiple structures
* Highlighting
* Initial camera/view
* Zoom
* Rotation
* Spin
* Lighting
* Background

The library should not expose every low-level 3Dmol.js function unnecessarily.

Instead, it should provide a useful **PHP-level vocabulary for common molecular visualization tasks**.

---

# UI and Controls

3Dmol.js is primarily a molecular visualization library rather than a complete application UI.

PDBViewerPHP can therefore provide a lightweight UI layer around it.

Possible controls include:

* Zoom
* Rotate
* Pan
* Reset view
* Fullscreen
* Spin
* Screenshot
* Download
* Representation selection
* Color selection
* Surface visibility
* Label visibility
* Structure selection

Controls should be individually configurable.

A developer should be able to create both a completely minimal viewer and a feature-rich viewer using the same class.

---

# Themes

PDBViewerPHP should have a concept of viewer themes.

Themes primarily control the **PDBViewerPHP interface**, including things such as:

* Toolbar appearance
* Control arrangement
* Typography
* Light/dark presentation
* UI spacing
* Button appearance

Molecular rendering properties such as molecular colors and backgrounds remain separate configuration concepts.

This separation allows a website to have its own interface style while independently controlling how the structure itself is rendered.

---

# Use Cases

## 1. Scientific Websites

A research group can publish interactive structures alongside publications or project descriptions.

The viewer can be configured specifically for the structure being discussed.

For example:

* Protein displayed as cartoon
* Ligand displayed as sticks
* Important residues labelled
* Fixed initial orientation
* Minimal controls

---

## 2. Bioinformatics Portals

A PHP-based bioinformatics portal can embed structures directly into search or result pages.

A structure page could combine:

* Protein information
* Sequence information
* PDB structure
* Interactive visualization
* Annotation

The viewer can inherit the portal's configuration without requiring each page to implement its own JavaScript integration.

---

## 3. Educational Websites

Teachers and educational websites can provide simplified molecular viewers.

Students may only need:

* Rotate
* Zoom
* Reset

Advanced controls can remain hidden.

This prevents a relatively complex molecular visualization interface from overwhelming beginners.

---

## 4. Pharmaceutical / Drug Discovery Applications

A PHP application could use the viewer to display:

* Protein structures
* Protein-ligand complexes
* Binding sites
* Ligands
* Important residues
* Surface representations

The administrator could control exactly which visualization options are exposed.

---

## 5. Scientific Reports and Project Portals

A research project website could provide an interactive structure viewer alongside experimental results.

The viewer could be configured as part of the report template rather than manually integrated into every page.

---

## 6. CMS-Based Scientific Websites

A CMS or PHP-based content management system could use PDBViewerPHP as a reusable component.

An administrator could define a structure and viewer configuration while the underlying page remains unaware of the JavaScript implementation.

---

## 7. Internal Research Tools

A laboratory or research organization could use PDBViewerPHP as a lightweight structure visualization component inside an existing PHP application.

The viewer does not need to become the entire application.

It can remain a focused component alongside:

* Tables
* Search
* Metadata
* Annotations
* Reports
* Sequence information
* Experimental data

---

## 8. Structure Databases

A custom protein or structural database could use PDBViewerPHP to provide an interactive viewer for every structure record.

The database backend can determine:

* Which structure is displayed
* Which representation is initially used
* Which controls are exposed
* Which annotations are visible
* Which download options are available

---

# Server Control vs Client Rendering

A fundamental concept of this project is the distinction between **configuration** and **rendering**.

The PHP application decides:

> What should the viewer look like and what should the user be allowed to do?

The browser decides:

> How should that configuration be rendered interactively?

This does **not** imply that PHP can enforce browser-side security.

If molecular data is sent to a browser, a sufficiently technical user can potentially access that data.

Therefore, options such as disabling download should be understood as **UI/feature restrictions**, not data-security mechanisms.

---

# Extensibility

The initial implementation should use 3Dmol.js.

However, the architecture should leave room for additional molecular rendering engines in the future.

Conceptually:

```text
PDBViewerPHP
     │
     └── Renderer
          ├── 3Dmol.js
          ├── Future renderer
          └── Future renderer
```

The project should not implement multiple engines simply for the sake of abstraction.

The first objective is to make the **3Dmol.js implementation excellent**.

---

# Why 3Dmol.js?

3Dmol.js is a strong foundation because it already provides the molecular visualization capabilities that PDBViewerPHP needs.

This means the project can concentrate on the PHP-specific gap:

**configuration + embedding + UI + developer experience**

rather than rebuilding:

**WebGL + molecular rendering + structure visualization.**

---

# Project Philosophy

PDBViewerPHP should follow these principles:

1. **PHP-first**
2. **Minimal JavaScript required from the user**
3. **3Dmol.js does the molecular rendering**
4. **Server-defined configuration**
5. **Configurable user controls**
6. **Simple defaults**
7. **Advanced configuration when required**
8. **No unnecessary framework dependencies**
9. **Security-conscious generated output**
10. **Remain a reusable library rather than becoming a full application**

---

# Future Possibilities

Potential future features may include:

* Additional rendering engines
* Advanced residue/atom annotations
* Measurement tools
* Structure comparison
* Predefined viewer profiles
* Saved camera configurations
* Annotation overlays
* Sequence-to-structure interaction
* Ligand-focused views
* Binding-site presets
* Plugin hooks
* CMS integrations
* WordPress integration
* Laravel integration
* Symfony integration

These are **future possibilities**, not requirements for the initial version.

---

# Initial Success Criteria

The project will be successful if a PHP developer can install the library and create a useful molecular viewer with minimal effort while controlling the viewer primarily from PHP.

The important demonstration is:

> **A PHP developer should not need to understand the internal 3Dmol.js initialization process to create and configure a useful molecular viewer.**

The first version should therefore prioritize **clean PHP API design, reliable 3Dmol.js integration, configurable controls, and a solid separation between server configuration and client rendering**.
