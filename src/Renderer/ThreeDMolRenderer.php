<?php

declare(strict_types=1);

namespace PDBViewerPHP\Renderer;

use PDBViewerPHP\Exception\InvalidConfigurationException;

/**
 * 3Dmol.js renderer implementation
 */
class ThreeDMolRenderer implements RendererInterface
{
    private string $version = '2.0.1';
    private string $cdnUrl = 'https://3Dmol.csb.pitt.edu';

    public function __construct(?string $version = null, ?string $cdnUrl = null)
    {
        if ($version !== null) {
            $this->version = $version;
        }
        if ($cdnUrl !== null) {
            $this->cdnUrl = $cdnUrl;
        }
    }

    public function getName(): string
    {
        return '3Dmol.js';
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getExternalResources(): array
    {
        return [
            'css' => $this->cdnUrl . '/build/3Dmol-min.css',
            'js' => $this->cdnUrl . '/build/3Dmol-min.js',
        ];
    }

    public function renderHtml(array $config): string
    {
        $viewerId = htmlspecialchars($config['viewerId'] ?? 'pdbviewer', ENT_QUOTES, 'UTF-8');
        $width = (int)($config['appearance']['width'] ?? 600);
        $height = (int)($config['appearance']['height'] ?? 600);
        $theme = $config['appearance']['theme'] ?? 'light';

        $themeClass = match($theme) {
            'dark' => 'pdbv-dark',
            'minimal' => 'pdbv-minimal',
            default => 'pdbv-light',
        };

        $html = <<<HTML
<div id="{$viewerId}" class="pdbviewer-container {$themeClass}" style="width: {$width}px; height: {$height}px; position: relative; border: 1px solid #d0d0d0; overflow: hidden; display: flex;">
    <div id="{$viewerId}-viewer" style="width: 100%; height: 100%; position: relative;"></div>
    <div id="{$viewerId}-controls" class="pdbviewer-controls" style="position: absolute; top: 10px; right: 10px; display: flex; gap: 5px; z-index: 100;">
        <!-- Controls will be populated by JavaScript -->
    </div>
</div>
HTML;

        return $html;
    }

    public function renderJavaScript(array $config): string
    {
        $viewerId = htmlspecialchars($config['viewerId'] ?? 'pdbviewer', ENT_QUOTES, 'UTF-8');
        $configJson = json_encode($config, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

        if ($configJson === false) {
            throw new InvalidConfigurationException('Failed to serialize configuration to JSON');
        }

        $js = <<<JS
(function() {
    const config = {$configJson};
    const viewerId = '{$viewerId}';
    const viewerElement = document.getElementById(viewerId + '-viewer');
    
    if (!window.\$3Dmol) {
        console.error('3Dmol.js library not loaded');
        return;
    }
    
    // Initialize viewer
    const viewer = \$3Dmol.createViewer(viewerElement, {
        defaultcolors: \$3Dmol.elementColors.rasmol
    });
    
    // Apply appearance settings
    const appearance = config.appearance || {};
    
    // Handle background color
    if (appearance.backgroundColor) {
        // Convert hex string (#rrggbb) to hex number (0xrrggbb)
        let hexColor = appearance.backgroundColor;
        if (typeof hexColor === 'string' && hexColor.startsWith('#')) {
            hexColor = '0x' + hexColor.substring(1);
            viewer.setBackgroundColor(parseInt(hexColor, 16));
        } else if (typeof hexColor === 'number') {
            viewer.setBackgroundColor(hexColor);
        }
    } else if (appearance.backgroundTransparent) {
        viewer.setBackgroundColor(0x000000, 0); // Transparent
    } else {
        viewer.setBackgroundColor(0xFFFFFF);
    }
    
    // Load structure
    loadStructure(viewer, config.structure);
    
    // Apply representations
    applyRepresentations(viewer, config.representation);
    
    // Apply spin animation if configured
    if (appearance.spin) {
        viewer.spin(true);
    }
    
    // Apply zoom level after loading structure
    if (appearance.zoom && typeof appearance.zoom === 'number') {
        // Note: zoom is applied after structure loads via viewer.zoomTo()
        // The zoom value multiplies the default zoom level
        setTimeout(() => {
            viewer.zoomTo();
            viewer.zoom(appearance.zoom);
            viewer.render();
        }, 100);
    } else {
        viewer.zoomTo();
    }
    
    // Render
    viewer.render();
    
    // Store viewer globally for control manipulation
    window.pdbViewers = window.pdbViewers || {};
    window.pdbViewers[viewerId] = viewer;
    
    // Setup controls
    setupControls(viewer, viewerId, config.controls);
})();

function loadStructure(viewer, structureConfig) {
    if (!structureConfig) return;
    
    const type = structureConfig.sourceType;
    
    if (type === 'pdb_id') {
        \$3Dmol.download('pdb:' + structureConfig.pdbId, viewer, {}, () => {
            viewer.zoomTo();
            viewer.render();
        });
    } else if (type === 'pdb_url') {
        \$3Dmol.download(structureConfig.pdbUrl, viewer, {}, () => {
            viewer.zoomTo();
            viewer.render();
        });
    } else if (type === 'mmcif_url') {
        \$3Dmol.download(structureConfig.mmCifUrl, viewer, {}, () => {
            viewer.zoomTo();
            viewer.render();
        });
    } else if (type === 'raw_data') {
        try {
            viewer.addModel(structureConfig.rawData, structureConfig.format || 'pdb');
            viewer.zoomTo();
            viewer.render();
        } catch (e) {
            console.error('Failed to load raw structure data:', e);
        }
    } else if (type === 'local_file') {
        fetch(structureConfig.localFile)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch structure file');
                return response.text();
            })
            .then(data => {
                viewer.addModel(data, structureConfig.format || 'pdb');
                viewer.zoomTo();
                viewer.render();
            })
            .catch(e => console.error('Failed to load structure file:', e));
    }
}

function applyRepresentations(viewer, repConfig) {
    if (!repConfig) return;
    
    const representations = repConfig.representations || [];
    if (representations.length === 0) return;
    
    // Apply representations with optional color scheme
    const colorScheme = repConfig.colorScheme;
    
    for (const rep of representations) {
        const style = {};
        style[rep] = {};
        
        // Apply color scheme if specified
        if (colorScheme) {
            style[rep].colorscheme = colorScheme;
        }
        
        viewer.setStyle({}, style);
    }
}

function setupControls(viewer, viewerId, controlConfig) {
    if (!controlConfig) return;
    
    const controlsDiv = document.getElementById(viewerId + '-controls');
    if (!controlsDiv) return;
    
    // Reset view button
    if (controlConfig.reset) {
        const btn = createButton('Reset', () => {
            viewer.zoomTo();
            viewer.render();
        });
        controlsDiv.appendChild(btn);
    }
    
    // Toggle spin button
    if (controlConfig.spin) {
        const btn = createButton('Spin', () => {
            const isSpinning = viewer._spin;
            viewer.spin(!isSpinning);
            viewer.render();
        });
        controlsDiv.appendChild(btn);
    }
    
    // Screenshot button
    if (controlConfig.screenshot) {
        const btn = createButton('Screenshot', () => {
            try {
                const canvas = viewer._canvas;
                if (canvas && canvas.toDataURL) {
                    const link = document.createElement('a');
                    link.href = canvas.toDataURL('image/png');
                    link.download = 'structure.png';
                    link.click();
                } else {
                    console.error('Canvas not available for screenshot');
                }
            } catch (e) {
                console.error('Screenshot failed:', e);
            }
        });
        controlsDiv.appendChild(btn);
    }
    
    // Fullscreen button
    if (controlConfig.fullscreen) {
        const btn = createButton('Fullscreen', () => {
            const container = document.getElementById(viewerId);
            if (!container) return;
            
            if (container.requestFullscreen) {
                container.requestFullscreen().catch(err => {
                    console.error('Fullscreen request failed:', err);
                });
            } else if (container.webkitRequestFullscreen) {
                container.webkitRequestFullscreen();
            } else if (container.mozRequestFullScreen) {
                container.mozRequestFullScreen();
            } else if (container.msRequestFullscreen) {
                container.msRequestFullscreen();
            }
        });
        controlsDiv.appendChild(btn);
    }
    
    // Download structure button
    if (controlConfig.download) {
        const btn = createButton('Download', () => {
            try {
                const canvas = viewer._canvas;
                if (canvas && canvas.toDataURL) {
                    const link = document.createElement('a');
                    link.href = canvas.toDataURL('image/png');
                    link.download = 'structure.png';
                    link.click();
                } else {
                    console.error('Cannot download - canvas not available');
                }
            } catch (e) {
                console.error('Download failed:', e);
            }
        });
        controlsDiv.appendChild(btn);
    }
    
    // Note: Zoom, Rotate, Pan controls require mouse/touch handling which is built-in to 3Dmol.js
    // These are handled natively by the viewer and don't require UI buttons
}

function createButton(label, onClick) {
    const btn = document.createElement('button');
    btn.textContent = label;
    btn.style.cssText = 'padding: 6px 10px; background: #888; border: 1px solid #666; color: white; cursor: pointer; border-radius: 2px; font-family: inherit; font-size: 11px; transition: background-color 0.2s;';
    btn.addEventListener('click', onClick);
    btn.addEventListener('mouseenter', () => { btn.style.background = '#737373'; });
    btn.addEventListener('mouseleave', () => { btn.style.background = '#888'; });
    return btn;
}
JS;

        return $js;
    }

    public function validateConfiguration(array $config): bool
    {
        // Basic validation - allow configuration even if structure is not set yet
        // (structure can be loaded dynamically)
        if (isset($config['structure']) && is_array($config['structure'])) {
            $sourceType = $config['structure']['sourceType'] ?? null;
            // sourceType can be null if structure not yet configured, which is OK
            if ($sourceType !== null && !in_array($sourceType, ['pdb_id', 'pdb_url', 'mmcif_url', 'raw_data', 'local_file'], true)) {
                return false;
            }
        }

        return true;
    }
}
