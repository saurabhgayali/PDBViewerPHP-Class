// ===== 3Dmol.js Interactive Demonstration =====
// This script manages the browser-side demonstration using 3Dmol.js
// It does NOT execute PHP - it's purely client-side JavaScript

let viewer = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the 3Dmol viewer
    initializeViewer();
    
    // Set up event listeners for demo controls
    setupDemoControls();
});

/**
 * Initialize the 3Dmol.js viewer
 */
function initializeViewer() {
    const viewerElement = document.getElementById('viewer');
    
    if (!viewerElement) {
        console.error('Viewer element not found');
        return;
    }
    
    // Ensure the viewer element is properly positioned
    if (viewerElement.style.position !== 'relative' && viewerElement.style.position !== 'absolute') {
        viewerElement.style.position = 'relative';
    }
    
    // Create viewer config with proper container setup
    const config = { 
        backgroundColor: 'white'
    };
    
    // Initialize 3Dmol viewer
    // Pass the DOM element (not the ID string) for better positioning handling
    viewer = $3Dmol.createViewer(viewerElement, config);
    
    if (!viewer) {
        console.error('Failed to create 3Dmol viewer');
        return;
    }
    
    console.log('3Dmol viewer initialized successfully');
    
    // Load initial structure (1FBB)
    loadStructure('1fbb');
}

/**
 * Load a PDB structure into the viewer
 */
function loadStructure(pdbId) {
    if (!viewer) {
        console.error('Viewer not initialized');
        return;
    }
    
    // Clear the viewer
    viewer.clear();
    
    // Fetch structure from RCSB PDB
    fetch(`https://files.rcsb.org/download/${pdbId}.pdb`)
        .then(response => response.text())
        .then(pdbData => {
            // Add structure to viewer
            viewer.addModel(pdbData, 'pdb');
            
            // Apply current visualization settings
            applyVisualization();
            
            // Zoom to fit
            viewer.zoomTo();
            
            // Render
            viewer.render();
        })
        .catch(error => {
            console.error(`Error loading structure ${pdbId}:`, error);
            // Show error in console
            showViewerError(`Failed to load structure ${pdbId}`);
        });
}

/**
 * Apply the current visualization settings
 */
function applyVisualization() {
    if (!viewer) return;
    
    const style = document.getElementById('demoStyle').value;
    const colorScheme = document.getElementById('demoColorScheme').value;
    const background = document.getElementById('demoBackground').value;
    
    // Get all models
    const models = viewer.getModel();
    if (!models) return;
    
    // Clear all representations
    viewer.setStyle({}, { cartoon: {} });
    
    // Parse background color
    const bgColor = background.startsWith('0x') ? 
        parseInt(background.replace('0x', ''), 16) : 
        background;
    viewer.setBackgroundColor(bgColor);
    
    // Apply style
    let styleConfig = {};
    
    switch(style) {
        case 'cartoon':
            styleConfig = { cartoon: { colorscheme: colorScheme } };
            break;
        case 'stick':
            styleConfig = { stick: { colorscheme: colorScheme } };
            break;
        case 'sphere':
            styleConfig = { sphere: { colorscheme: colorScheme } };
            break;
        case 'line':
            styleConfig = { line: { colorscheme: colorScheme } };
            break;
        case 'cartoon+surface':
            // First apply cartoon
            viewer.setStyle({}, { cartoon: { colorscheme: colorScheme } });
            // Then add surface
            viewer.addSurface($3Dmol.SurfaceType.VDW, {
                opacity: 0.7,
                colorscheme: colorScheme
            });
            viewer.render();
            return; // Early return since we already rendered
        default:
            styleConfig = { cartoon: { colorscheme: colorScheme } };
    }
    
    // Apply the style
    viewer.setStyle({}, styleConfig);
    
    // Render
    viewer.render();
}

/**
 * Set up event listeners for demo controls
 */
function setupDemoControls() {
    const structureSelect = document.getElementById('demoStructure');
    const styleSelect = document.getElementById('demoStyle');
    const colorSchemeSelect = document.getElementById('demoColorScheme');
    const backgroundSelect = document.getElementById('demoBackground');
    const resetButton = document.getElementById('demoReset');
    
    if (structureSelect) {
        structureSelect.addEventListener('change', function() {
            loadStructure(this.value);
        });
    }
    
    if (styleSelect) {
        styleSelect.addEventListener('change', applyVisualization);
    }
    
    if (colorSchemeSelect) {
        colorSchemeSelect.addEventListener('change', applyVisualization);
    }
    
    if (backgroundSelect) {
        backgroundSelect.addEventListener('change', applyVisualization);
    }
    
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (viewer) {
                viewer.zoomTo();
                viewer.render();
            }
        });
    }
}

/**
 * Show an error message in the viewer
 */
function showViewerError(message) {
    const viewerElement = document.getElementById('viewer');
    if (viewerElement) {
        viewerElement.innerHTML = `
            <div style="
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%;
                background-color: #fee;
                color: #c00;
                font-weight: bold;
                text-align: center;
                padding: 1rem;
                border-radius: 8px;
            ">
                <div>
                    <div style="font-size: 1.2rem; margin-bottom: 0.5rem;">⚠️ Error</div>
                    <div>${message}</div>
                    <div style="font-size: 0.9rem; margin-top: 0.5rem; opacity: 0.8;">
                        Check the browser console for more details.
                    </div>
                </div>
            </div>
        `;
    }
}

/**
 * Smooth scrolling for navigation links
 */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        // Only prevent default for same-page anchors
        if (href.startsWith('#') && href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});

/**
 * Utility: Log that the demonstration is running
 */
console.log('PDBViewerPHP Documentation Site - Interactive Demo');
console.log('This is a browser-side demonstration using 3Dmol.js from CDN');
console.log('It does not execute PHP - it shows the type of viewer that PDBViewerPHP would generate');
