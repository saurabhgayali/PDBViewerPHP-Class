<?php

declare(strict_types=1);

namespace PDBViewerPHP;

use PDBViewerPHP\Configuration\{
    StructureConfiguration,
    RepresentationConfiguration,
    AppearanceConfiguration,
    ControlConfiguration,
    RepresentationType,
    ColorScheme,
    Theme,
};
use PDBViewerPHP\Renderer\{RendererInterface, ThreeDMolRenderer};
use PDBViewerPHP\Exception\{PDBViewerException, RendererException};

/**
 * Main PDBViewerPHP class for configuring molecular viewers
 * 
 * Fluent API for configuring and rendering molecular structure viewers
 * using 3Dmol.js
 */
class PDBViewer
{
    private StructureConfiguration $structure;
    private RepresentationConfiguration $representation;
    private AppearanceConfiguration $appearance;
    private ControlConfiguration $controls;
    private RendererInterface $renderer;
    private string $viewerId = 'pdbviewer';

    public function __construct(?RendererInterface $renderer = null)
    {
        $this->structure = new StructureConfiguration();
        $this->representation = new RepresentationConfiguration();
        $this->appearance = new AppearanceConfiguration();
        $this->controls = new ControlConfiguration();
        $this->renderer = $renderer ?? new ThreeDMolRenderer();
    }

    /**
     * Set viewer container ID
     */
    public function setViewerId(string $id): self
    {
        $this->viewerId = $id;
        return $this;
    }

    public function getViewerId(): string
    {
        return $this->viewerId;
    }

    /**
     * Set renderer
     */
    public function setRenderer(RendererInterface $renderer): self
    {
        $this->renderer = $renderer;
        return $this;
    }

    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }

    /**
     * Load structure from PDB identifier
     */
    public function loadPDB(string $pdbId): self
    {
        $this->structure->setPdbId($pdbId);
        return $this;
    }

    /**
     * Load structure from PDB URL
     */
    public function loadFromPdbUrl(string $url): self
    {
        $this->structure->setPdbUrl($url);
        return $this;
    }

    /**
     * Load structure from mmCIF URL
     */
    public function loadFromMmCifUrl(string $url): self
    {
        $this->structure->setMmCifUrl($url);
        return $this;
    }

    /**
     * Load structure from local file
     */
    public function loadFromFile(string $filePath): self
    {
        $this->structure->setLocalFile($filePath);
        return $this;
    }

    /**
     * Load structure from raw data
     */
    public function loadFromRawData(string $data, string $format = 'pdb'): self
    {
        $this->structure->setRawData($data, $format);
        return $this;
    }

    /**
     * Set structure configuration directly
     */
    public function setStructure(StructureConfiguration $config): self
    {
        $this->structure = $config;
        return $this;
    }

    public function getStructure(): StructureConfiguration
    {
        return $this->structure;
    }

    /**
     * Set representation type
     */
    public function setRepresentation(RepresentationType $type): self
    {
        $this->representation->setRepresentation($type);
        return $this;
    }

    /**
     * Add representation type
     */
    public function addRepresentation(RepresentationType $type): self
    {
        $this->representation->addRepresentation($type);
        return $this;
    }

    /**
     * Set color scheme
     */
    public function setColorScheme(ColorScheme $scheme): self
    {
        $this->representation->setColorScheme($scheme);
        return $this;
    }

    /**
     * Set representation configuration directly
     */
    public function setRepresentationConfig(RepresentationConfiguration $config): self
    {
        $this->representation = $config;
        return $this;
    }

    public function getRepresentation(): RepresentationConfiguration
    {
        return $this->representation;
    }

    /**
     * Set viewer width
     */
    public function setWidth(int $width): self
    {
        $this->appearance->setWidth($width);
        return $this;
    }

    /**
     * Set viewer height
     */
    public function setHeight(int $height): self
    {
        $this->appearance->setHeight($height);
        return $this;
    }

    /**
     * Set viewer dimensions
     */
    public function setDimensions(int $width, int $height): self
    {
        $this->appearance->setDimensions($width, $height);
        return $this;
    }

    /**
     * Set background color
     */
    public function setBackgroundColor(string $color): self
    {
        $this->appearance->setBackgroundColor($color);
        return $this;
    }

    /**
     * Set background transparent
     */
    public function setBackgroundTransparent(bool $transparent = true): self
    {
        $this->appearance->setBackgroundTransparent($transparent);
        return $this;
    }

    /**
     * Enable/disable spin
     */
    public function setSpin(bool $spin): self
    {
        $this->appearance->setSpin($spin);
        return $this;
    }

    /**
     * Set zoom level
     */
    public function setZoom(float $zoom): self
    {
        $this->appearance->setZoom($zoom);
        return $this;
    }

    /**
     * Set theme
     */
    public function setTheme(Theme $theme): self
    {
        $this->appearance->setTheme($theme);
        return $this;
    }

    /**
     * Set appearance configuration directly
     */
    public function setAppearance(AppearanceConfiguration $config): self
    {
        $this->appearance = $config;
        return $this;
    }

    public function getAppearance(): AppearanceConfiguration
    {
        return $this->appearance;
    }

    /**
     * Enable/disable zoom control
     */
    public function setZoomControlEnabled(bool $enabled): self
    {
        $this->controls->setZoomEnabled($enabled);
        return $this;
    }

    /**
     * Enable/disable rotate control
     */
    public function setRotateControlEnabled(bool $enabled): self
    {
        $this->controls->setRotateEnabled($enabled);
        return $this;
    }

    /**
     * Enable/disable pan control
     */
    public function setPanControlEnabled(bool $enabled): self
    {
        $this->controls->setPanEnabled($enabled);
        return $this;
    }

    /**
     * Enable/disable reset control
     */
    public function setResetControlEnabled(bool $enabled): self
    {
        $this->controls->setResetEnabled($enabled);
        return $this;
    }

    /**
     * Enable/disable fullscreen control
     */
    public function setFullscreenControlEnabled(bool $enabled): self
    {
        $this->controls->setFullscreenEnabled($enabled);
        return $this;
    }

    /**
     * Enable/disable spin control
     */
    public function setSpinControlEnabled(bool $enabled): self
    {
        $this->controls->setSpinEnabled($enabled);
        return $this;
    }

    /**
     * Enable/disable screenshot control
     */
    public function setScreenshotControlEnabled(bool $enabled): self
    {
        $this->controls->setScreenshotEnabled($enabled);
        return $this;
    }

    /**
     * Enable/disable download control
     */
    public function setDownloadControlEnabled(bool $enabled): self
    {
        $this->controls->setDownloadEnabled($enabled);
        return $this;
    }

    /**
     * Convenience: show zoom control
     */
    public function showZoomControl(): self
    {
        $this->controls->showZoomControl();
        return $this;
    }

    /**
     * Convenience: hide zoom control
     */
    public function hideZoomControl(): self
    {
        $this->controls->hideZoomControl();
        return $this;
    }

    /**
     * Convenience: show download control
     */
    public function showDownloadControl(): self
    {
        $this->controls->showDownloadControl();
        return $this;
    }

    /**
     * Convenience: hide download control
     */
    public function hideDownloadControl(): self
    {
        $this->controls->hideDownloadControl();
        return $this;
    }

    /**
     * Convenience: show fullscreen control
     */
    public function showFullscreenControl(): self
    {
        $this->controls->showFullscreenControl();
        return $this;
    }

    /**
     * Convenience: hide fullscreen control
     */
    public function hideFullscreenControl(): self
    {
        $this->controls->hideFullscreenControl();
        return $this;
    }

    /**
     * Convenience: show screenshot control
     */
    public function showScreenshotControl(): self
    {
        $this->controls->showScreenshotControl();
        return $this;
    }

    /**
     * Convenience: hide screenshot control
     */
    public function hideScreenshotControl(): self
    {
        $this->controls->hideScreenshotControl();
        return $this;
    }

    /**
     * Set control configuration directly
     */
    public function setControls(ControlConfiguration $config): self
    {
        $this->controls = $config;
        return $this;
    }

    public function getControls(): ControlConfiguration
    {
        return $this->controls;
    }

    /**
     * Get all configuration as array
     */
    private function getConfiguration(): array
    {
        return [
            'viewerId' => $this->viewerId,
            'structure' => array_filter($this->structure->toArray()),
            'structure' => $this->structure->toArray(),
            'representation' => $this->representation->toArray(),
            'appearance' => $this->appearance->toArray(),
            'controls' => $this->controls->toArray(),
        ];
    }

    /**
     * Render the viewer HTML container
     */
    public function renderHtml(): string
    {
        $config = $this->getConfiguration();

        if (!$this->renderer->validateConfiguration($config)) {
            throw new PDBViewerException('Invalid configuration for renderer');
        }

        return $this->renderer->renderHtml($config);
    }

    /**
     * Render the JavaScript initialization code
     */
    public function renderJavaScript(): string
    {
        $config = $this->getConfiguration();

        if (!$this->renderer->validateConfiguration($config)) {
            throw new PDBViewerException('Invalid configuration for renderer');
        }

        return $this->renderer->renderJavaScript($config);
    }

    /**
     * Render complete HTML and JavaScript combined
     */
    public function render(): string
    {
        $html = $this->renderHtml();
        $externalResources = $this->renderer->getExternalResources();

        $output = '';

        // Add external CSS
        if (isset($externalResources['css'])) {
            $cssUrl = htmlspecialchars($externalResources['css'], ENT_QUOTES, 'UTF-8');
            $output .= <<<HTML
<link rel="stylesheet" href="{$cssUrl}">

HTML;
        }

        $output .= $html . "\n";

        // Add external JS
        if (isset($externalResources['js'])) {
            $jsUrl = htmlspecialchars($externalResources['js'], ENT_QUOTES, 'UTF-8');
            $output .= <<<HTML
<script src="{$jsUrl}"></script>

HTML;
        }

        $output .= '<script>';
        $output .= $this->renderJavaScript();
        $output .= '</script>';

        return $output;
    }

    /**
     * Get configuration as JSON string
     */
    public function getConfigurationJson(): string
    {
        $config = $this->getConfiguration();
        $json = json_encode($config, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

        if ($json === false) {
            throw new PDBViewerException('Failed to serialize configuration to JSON');
        }

        return $json;
    }
}
