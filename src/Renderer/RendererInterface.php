<?php

declare(strict_types=1);

namespace PDBViewerPHP\Renderer;

/**
 * Interface for molecular rendering engines
 */
interface RendererInterface
{
    /**
     * Get the name of the renderer
     */
    public function getName(): string;

    /**
     * Get the version of the renderer
     */
    public function getVersion(): string;

    /**
     * Generate the HTML for the viewer container
     */
    public function renderHtml(array $config): string;

    /**
     * Generate the JavaScript for viewer initialization
     */
    public function renderJavaScript(array $config): string;

    /**
     * Get required external resources (CSS, JS files)
     * 
     * @return array<string, string> Array of [type => url]
     */
    public function getExternalResources(): array;

    /**
     * Validate configuration before rendering
     */
    public function validateConfiguration(array $config): bool;
}
