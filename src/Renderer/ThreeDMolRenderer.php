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
        $viewerId = $config['viewerId'] ?? 'pdbviewer';
        $width = $config['appearance']['width'] ?? 600;
        $height = $config['appearance']['height'] ?? 600;
        $theme = $config['appearance']['theme'] ?? 'light';

        $themeClass = match($theme) {
            'dark' => 'pdbv-dark',
            'minimal' => 'pdbv-minimal',
            default => 'pdbv-light',
        };

        $html = <<<HTML
<div id="{$viewerId}" class="pdbviewer-container {$themeClass}" style="width: {$width}px; height: {$height}px; position: relative; border: 1px solid #ccc;">
    <div id="{$viewerId}-viewer" style="width: 100%; height: 100%;"></div>
    <div id="{$viewerId}-controls" class="pdbviewer-controls" style="position: absolute; top: 10px; right: 10px; display: flex; gap: 5px; z-index: 100;">
        <!-- Controls will be populated by JavaScript -->
    </div>
</div>
HTML;

        return $html;
    }

    public function renderJavaScript(array $config): string
    {
        $viewerId = $config['viewerId'] ?? 'pdbviewer';
        $configJson = json_encode($config, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

        if ($configJson === false) {
            throw new InvalidConfigurationException('Failed to serialize configuration to JSON');
        }

        $js = <<<JS
(function() {
    const config = {$configJson};
    const viewerId = '{$viewerId}';
    const viewerElement = document.getElementById(viewerId + '-viewer');
    
    if (!window.GLViewer) {
        console.error('3Dmol.js library not loaded');
        return;
    }
    
    // Initialize viewer
    const viewer = $3Dmol.createViewer(viewerElement, {
        defaultcolors: $3Dmol.elementColors.rasmol
    });
    
    // Apply appearance settings
    const appearance = config.appearance || {};
    
    if (appearance.backgroundColor) {
        viewer.setBackgroundColor(appearance.backgroundColor);
    } else if (appearance.backgroundTransparent) {
        viewer.setBackgroundColor(0x000000, 0); // Transparent
    } else {
        viewer.setBackgroundColor(0xFFFFFF);
    }
    
    // Load structure
    loadStructure(viewer, config.structure);
    
    // Apply representations
    applyRepresentations(viewer, config.representation);
    
    // Apply appearance settings
    if (appearance.spin) {
        viewer.spin(true);
    }
    
    if (appearance.antialiasing) {
        // Note: Antialiasing is typically set during viewer initialization
    }
    
    // Zoom to fit
    viewer.zoomTo();
    
    if (appearance.zoom) {
        viewer.zoom(appearance.zoom);
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
        $3Dmol.download('pdb:' + structureConfig.pdbId, viewer);
    } else if (type === 'pdb_url') {
        $3Dmol.download(structureConfig.pdbUrl, viewer);
    } else if (type === 'mmcif_url') {
        $3Dmol.download(structureConfig.mmCifUrl, viewer);
    } else if (type === 'raw_data') {
        viewer.addModel(structureConfig.rawData, structureConfig.format || 'pdb');
    } else if (type === 'local_file') {
        fetch(structureConfig.localFile)
            .then(response => response.text())
            .then(data => {
                viewer.addModel(data, structureConfig.format || 'pdb');
                viewer.zoomTo();
                viewer.render();
            });
    }
}

function applyRepresentations(viewer, repConfig) {
    if (!repConfig) return;
    
    const representations = repConfig.representations || [];
    const colorScheme = repConfig.colorScheme;
    
    // Clear default
    viewer.setStyle({}, {cartoon: {}});
    
    for (const rep of representations) {
        const style = {};
        style[rep] = {};
        
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
    
    // Reset button
    if (controlConfig.reset) {
        const btn = createButton('Reset', () => {
            viewer.zoomTo();
            viewer.render();
        });
        controlsDiv.appendChild(btn);
    }
    
    // Spin button
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
            const canvas = viewer._canvas;
            const link = document.createElement('a');
            link.href = canvas.toDataURL();
            link.download = 'structure.png';
            link.click();
        });
        controlsDiv.appendChild(btn);
    }
    
    // Fullscreen button
    if (controlConfig.fullscreen) {
        const btn = createButton('Fullscreen', () => {
            const container = document.getElementById(viewerId);
            if (container.requestFullscreen) {
                container.requestFullscreen();
            } else if (container.webkitRequestFullscreen) {
                container.webkitRequestFullscreen();
            }
        });
        controlsDiv.appendChild(btn);
    }
}

function createButton(label, onClick) {
    const btn = document.createElement('button');
    btn.textContent = label;
    btn.style.cssText = 'padding: 8px 12px; background: #f0f0f0; border: 1px solid #ccc; cursor: pointer; border-radius: 3px;';
    btn.addEventListener('click', onClick);
    return btn;
}
JS;

        return $js;
    }

    public function validateConfiguration(array $config): bool
    {
        // Basic validation
        if (isset($config['structure']) && is_array($config['structure'])) {
            $sourceType = $config['structure']['sourceType'] ?? null;
            if (!in_array($sourceType, ['pdb_id', 'pdb_url', 'mmcif_url', 'raw_data', 'local_file'], true)) {
                return false;
            }
        }

        return true;
    }
}
