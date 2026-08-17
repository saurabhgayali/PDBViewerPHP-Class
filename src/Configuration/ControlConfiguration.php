<?php

declare(strict_types=1);

namespace PDBViewerPHP\Configuration;

/**
 * Configuration for viewer controls
 */
class ControlConfiguration
{
    // Default all controls to enabled
    private bool $zoom = true;
    private bool $rotate = true;
    private bool $pan = true;
    private bool $reset = true;
    private bool $fullscreen = true;
    private bool $spin = true;
    private bool $screenshot = true;
    private bool $download = false; // Disabled by default for security
    private bool $representationSelection = true;
    private bool $colorSelection = true;
    private bool $surfaceToggle = true;
    private bool $labelToggle = true;

    /**
     * Enable/disable zoom control
     */
    public function setZoomEnabled(bool $enabled): self
    {
        $this->zoom = $enabled;
        return $this;
    }

    /**
     * Enable/disable rotate control
     */
    public function setRotateEnabled(bool $enabled): self
    {
        $this->rotate = $enabled;
        return $this;
    }

    /**
     * Enable/disable pan control
     */
    public function setPanEnabled(bool $enabled): self
    {
        $this->pan = $enabled;
        return $this;
    }

    /**
     * Enable/disable reset view control
     */
    public function setResetEnabled(bool $enabled): self
    {
        $this->reset = $enabled;
        return $this;
    }

    /**
     * Enable/disable fullscreen control
     */
    public function setFullscreenEnabled(bool $enabled): self
    {
        $this->fullscreen = $enabled;
        return $this;
    }

    /**
     * Enable/disable spin control
     */
    public function setSpinEnabled(bool $enabled): self
    {
        $this->spin = $enabled;
        return $this;
    }

    /**
     * Enable/disable screenshot control
     */
    public function setScreenshotEnabled(bool $enabled): self
    {
        $this->screenshot = $enabled;
        return $this;
    }

    /**
     * Enable/disable download control
     */
    public function setDownloadEnabled(bool $enabled): self
    {
        $this->download = $enabled;
        return $this;
    }

    /**
     * Enable/disable representation selection control
     */
    public function setRepresentationSelectionEnabled(bool $enabled): self
    {
        $this->representationSelection = $enabled;
        return $this;
    }

    /**
     * Enable/disable color selection control
     */
    public function setColorSelectionEnabled(bool $enabled): self
    {
        $this->colorSelection = $enabled;
        return $this;
    }

    /**
     * Enable/disable surface toggle control
     */
    public function setSurfaceToggleEnabled(bool $enabled): self
    {
        $this->surfaceToggle = $enabled;
        return $this;
    }

    /**
     * Enable/disable label toggle control
     */
    public function setLabelToggleEnabled(bool $enabled): self
    {
        $this->labelToggle = $enabled;
        return $this;
    }

    /**
     * Convenience method: show zoom control
     */
    public function showZoomControl(): self
    {
        return $this->setZoomEnabled(true);
    }

    /**
     * Convenience method: hide zoom control
     */
    public function hideZoomControl(): self
    {
        return $this->setZoomEnabled(false);
    }

    /**
     * Convenience method: show rotate control
     */
    public function showRotateControl(): self
    {
        return $this->setRotateEnabled(true);
    }

    /**
     * Convenience method: hide rotate control
     */
    public function hideRotateControl(): self
    {
        return $this->setRotateEnabled(false);
    }

    /**
     * Convenience method: show download control
     */
    public function showDownloadControl(): self
    {
        return $this->setDownloadEnabled(true);
    }

    /**
     * Convenience method: hide download control
     */
    public function hideDownloadControl(): self
    {
        return $this->setDownloadEnabled(false);
    }

    /**
     * Convenience method: show fullscreen control
     */
    public function showFullscreenControl(): self
    {
        return $this->setFullscreenEnabled(true);
    }

    /**
     * Convenience method: hide fullscreen control
     */
    public function hideFullscreenControl(): self
    {
        return $this->setFullscreenEnabled(false);
    }

    /**
     * Convenience method: show screenshot control
     */
    public function showScreenshotControl(): self
    {
        return $this->setScreenshotEnabled(true);
    }

    /**
     * Convenience method: hide screenshot control
     */
    public function hideScreenshotControl(): self
    {
        return $this->setScreenshotEnabled(false);
    }

    public function isZoomEnabled(): bool
    {
        return $this->zoom;
    }

    public function isRotateEnabled(): bool
    {
        return $this->rotate;
    }

    public function isPanEnabled(): bool
    {
        return $this->pan;
    }

    public function isResetEnabled(): bool
    {
        return $this->reset;
    }

    public function isFullscreenEnabled(): bool
    {
        return $this->fullscreen;
    }

    public function isSpinEnabled(): bool
    {
        return $this->spin;
    }

    public function isScreenshotEnabled(): bool
    {
        return $this->screenshot;
    }

    public function isDownloadEnabled(): bool
    {
        return $this->download;
    }

    public function isRepresentationSelectionEnabled(): bool
    {
        return $this->representationSelection;
    }

    public function isColorSelectionEnabled(): bool
    {
        return $this->colorSelection;
    }

    public function isSurfaceToggleEnabled(): bool
    {
        return $this->surfaceToggle;
    }

    public function isLabelToggleEnabled(): bool
    {
        return $this->labelToggle;
    }

    /**
     * Convert to array for JSON serialization
     */
    public function toArray(): array
    {
        return [
            'zoom' => $this->zoom,
            'rotate' => $this->rotate,
            'pan' => $this->pan,
            'reset' => $this->reset,
            'fullscreen' => $this->fullscreen,
            'spin' => $this->spin,
            'screenshot' => $this->screenshot,
            'download' => $this->download,
            'representationSelection' => $this->representationSelection,
            'colorSelection' => $this->colorSelection,
            'surfaceToggle' => $this->surfaceToggle,
            'labelToggle' => $this->labelToggle,
        ];
    }
}
