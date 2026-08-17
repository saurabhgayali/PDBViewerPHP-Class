# PDBViewerPHP Audit: Documentation Update Guide

## Overview

This document provides specific guidance on what documentation needs to be updated based on the audit findings. These updates will align README.md with the actual implementation.

---

## Critical Updates Required

### 1. UI Controls Section - MAJOR REVISION NEEDED

**Current README Text** (§ UI Controls):
```
Enable or disable user controls:

$viewer->setZoomControlEnabled(true);
$viewer->setRotateControlEnabled(false);
$viewer->setPanControlEnabled(true);
...
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

**Issue**: 
- These methods exist in PHP but many don't create UI buttons
- Zoom, Rotate, Pan are built-in mouse controls, not configurable buttons
- Creates false expectation that `setZoomControlEnabled(false)` will disable zooming

**Recommended Fix**:

Replace the UI Controls section with:

```markdown
#### UI Controls

PDBViewerPHP provides both **mouse-based controls** (built-in to 3Dmol.js) and **button controls** (configurable).

##### Mouse Controls (Always Active)

The following controls are built-in to the 3Dmol.js viewer and always available to users:

- **Rotate**: Click and drag with left mouse button
- **Zoom**: Scroll wheel or pinch gesture
- **Pan**: Right-click and drag (or Ctrl+drag on Mac)
- **Reset View**: Via the Reset button

Note: These mouse controls cannot be disabled via PDBViewerPHP configuration. If you need to prevent user interaction, consider using an image/screenshot instead of the interactive viewer.

##### Button Controls (Configurable)

Enable or disable button controls:

```php
// Enable/disable specific buttons
$viewer->setResetControlEnabled(true);      // Reset View button
$viewer->setFullscreenControlEnabled(true);  // Fullscreen button
$viewer->setSpinControlEnabled(true);        // Auto Spin toggle
$viewer->setScreenshotControlEnabled(true);  // Screenshot button
$viewer->setDownloadControlEnabled(false);   // Screenshot Download button

// Convenience methods
$viewer->showFullscreenControl();
$viewer->hideDownloadControl();
```

**Available Button Controls**:

- **Reset**: Resets the view to initial orientation and zoom
- **Spin**: Toggles automatic rotation of the structure
- **Screenshot**: Downloads the current view as a PNG image
- **Fullscreen**: Expands viewer to fullscreen (Esc to exit)
- **Download**: Downloads the current view as a PNG image

**Note on "Download"**: The download button downloads a PNG screenshot, not the structure file. See the "Known Limitations" section.
```

---

### 2. Molecular Representations Section - REVISION NEEDED

**Current README Text**:
```
// Add multiple representations
$viewer->addRepresentation(RepresentationType::CARTOON);
$viewer->addRepresentation(RepresentationType::STICK);
```

**Issue**: 
Multiple representations are added to the PHP configuration but only the last one renders due to the `setStyle()` overwriting previous styles.

**Recommended Fix**:

```markdown
#### Molecular Representations

```php
use PDBViewerPHP\Configuration\RepresentationType;

// Set primary representation
$viewer->setRepresentation(RepresentationType::CARTOON);
$viewer->setRepresentation(RepresentationType::STICK);
// ... other types
```

**Supported Representations**:

- `CARTOON` - Ribbon structure (default for proteins)
- `STICK` - Ball-and-stick atoms
- `SPHERE` - Space-filling representation
- `RIBBON` - Thicker ribbon/tube representation
- `LINE` - Wireframe structure
- `CROSS` - Cross representation
- `SURFACE` - Molecular surface

**Note on Multiple Representations**: 

The current version supports setting one primary representation via `setRepresentation()`. The `addRepresentation()` method is provided for future use but currently only the last added representation will display.

**Example - Different representations for different contexts**:

```php
// For protein backbone view
$viewer->setRepresentation(RepresentationType::CARTOON);

// For ligand detail view
$viewer->setRepresentation(RepresentationType::STICK);

// For surface visualization
$viewer->setRepresentation(RepresentationType::SURFACE);
```

**Roadmap**: Support for overlaying multiple representations simultaneously is planned for a future version.
```

---

### 3. Download Feature - CLARIFICATION NEEDED

**Current README Text**:
```
// Control download
if (!$showDownload) {
    $viewer->hideDownloadControl();
}
```

Under "Rendering" section:
```
// Get configuration as JSON
$config = $viewer->getConfigurationJson();
```

**Issue**: 
No clarification that "Download" downloads a screenshot image, not the structure file.

**Recommended Fix**:

Add this note to the demo section or create a "Known Limitations" section:

```markdown
#### Known Limitations

- **Download Button**: The "Download" button downloads a screenshot of the current viewer state as a PNG image, not the structure data file. If you need users to download the structure file, you must provide a separate download link outside the viewer.

- **Measurement Tools**: Distance and angle measurements are not currently supported.

- **Electron Density Maps**: Visualization of electron density maps is not supported.

- **Animation Sequences**: Molecular dynamics trajectories and frame-by-frame animation are not supported.

- **Complex Atom Selection**: Advanced atom/residue selection with custom UI is not yet implemented.

- **Theme Styling**: Theme classes are applied but custom CSS styling for light/dark/minimal themes is not yet implemented.
```

---

### 4. Themes Section - PARTIAL REVISION NEEDED

**Current README Text**:
```
## Themes

PDBViewerPHP includes three built-in themes:

### Light Theme (Default)
Clean, bright interface suitable for most applications.

### Dark Theme
Dark interface with high contrast, suitable for dark-mode websites.

### Minimal Theme
Minimalist interface with hidden controls by default.
```

**Issue**: 
No CSS is actually provided for these themes. They only set CSS class names.

**Recommended Fix**:

```markdown
## Themes

PDBViewerPHP includes three built-in themes. Note that custom CSS styling for themes is not yet fully implemented - currently only CSS class names are applied.

```php
use PDBViewerPHP\Configuration\Theme;

$viewer->setTheme(Theme::LIGHT);    // Default - light interface
$viewer->setTheme(Theme::DARK);     // Dark interface
$viewer->setTheme(Theme::MINIMAL);  // Minimal interface
```

### Theme Classes

The following CSS classes are applied to the viewer container:

- `pdbv-light` - Light theme
- `pdbv-dark` - Dark theme  
- `pdbv-minimal` - Minimal theme

**Customizing Themes**:

You can apply custom CSS to style these theme classes:

```html
<style>
  .pdbv-dark .pdbviewer-controls {
    background: #333;
    color: white;
  }
  
  .pdbv-minimal .pdbviewer-controls {
    display: none; /* Hide controls by default */
  }
</style>
```

**Note**: Built-in theme styling is not currently implemented. If you need styled themes, apply your own CSS using the class names above, or submit a feature request on GitHub.
```

---

### 5. Selection Representations - ADD CLEAR NOTE

**Current Documentation**: 
No mention of this feature being incomplete.

**Recommended Fix**:

Add note in Advanced Configuration section or new subsection:

```markdown
#### Selection Representations (Advanced - Work in Progress)

The API supports configuration of selection-specific representations:

```php
$config->addSelectionRepresentation(
    'resi 42:50',      // Residue selection
    RepresentationType::STICK,
    ['color' => 'red']
);
```

**Note**: This feature is implemented on the PHP side but not yet functional in the JavaScript renderer. It is planned for a future version. If you need selection-specific representations, you can work around this by using separate viewers for different selections.
```

---

## Minor Documentation Updates

### 6. Add 3Dmol.js Version Information

**Where**: Under "3Dmol.js Dependency" section

**Add**:
```markdown
### API Stability

PDBViewerPHP uses 3Dmol.js 2.0.1 from the official CDN. The library accesses some internal properties (`viewer._canvas`, `viewer._spin`) that may change in future versions. If you encounter compatibility issues with newer 3Dmol.js versions, please report them on GitHub.
```

### 7. Color Schemes - Add Compatibility Note

**Where**: Under "Color Schemes" section

**Add**:
```markdown
**Note on Color Schemes**: 

The schemes `SSTYPE` (secondary structure type) and `HYDRO` (hydrophobicity) may require specific 3Dmol.js configurations to work correctly. If they don't produce colors, try using one of the standard schemes (CHAIN, SPECTRUM, ATOM, RESIDUE, CARTOON) instead.
```

---

## Summary of Changes

### Files to Update

1. **README.md** - PRIMARY DOCUMENT
   - [ ] Revise UI Controls section (separate mouse vs. buttons)
   - [ ] Clarify multiple representations limitation
   - [ ] Clarify download button downloads screenshot
   - [ ] Add note about theme styling not implemented
   - [ ] Add note about selection representations being WIP
   - [ ] Add version/stability notes for 3Dmol.js

2. **CONCEPT.md** - NO CHANGES NEEDED
   - Architecture is correctly described
   - No documentation errors found

3. **CHANGELOG.md** - CONSIDER ADDING
   - Add entry noting this audit
   - Document known limitations found

### Priority Levels

**CRITICAL** (Must update):
1. UI Controls section - creates false expectations about disabling zoom/rotate/pan
2. Multiple representations - users will try addRepresentation() and it won't work as expected

**HIGH** (Should update):
3. Download button - users will expect structure files not screenshots
4. Themes - users will expect styled themes

**MEDIUM** (Should update):
5. Selection representations - advanced users may try to use this
6. Color schemes - some schemes might not work

**LOW** (Nice to have):
7. Version/stability notes
8. Private API access notes

---

## Testing After Updates

After making documentation changes, verify:

1. [ ] README is clear about what can/cannot be disabled
2. [ ] README no longer suggests multiple representations work
3. [ ] Download button behavior is clearly documented
4. [ ] Themes section explains the CSS class approach
5. [ ] New users won't be confused by misleading descriptions

---

## Questions for Project Maintainer

After review of this audit, consider:

1. Should multiple representations be implemented to actually overlay?
2. Should themes get actual CSS styling?
3. Should download button download structure file or remain screenshot?
4. Should selection representations be completed or removed from v1?
5. What is the priority for implementing missing advanced features?

---

## Conclusion

These documentation updates will significantly improve the experience for new users by setting correct expectations about what features are actually available. They require no code changes - just accurate documentation of the current implementation.

Once these updates are made, the README will accurately reflect what the library actually does.
