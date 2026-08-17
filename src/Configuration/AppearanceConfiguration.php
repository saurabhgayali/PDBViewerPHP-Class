<?php

declare(strict_types=1);

namespace PDBViewerPHP\Configuration;

use PDBViewerPHP\Exception\InvalidConfigurationException;

/**
 * Configuration for viewer appearance (colors, background, dimensions, lighting)
 */
class AppearanceConfiguration
{
    private ?int $width = null;
    private ?int $height = null;
    private ?string $backgroundColor = null;
    private ?bool $backgroundTransparent = null;
    private ?bool $spin = null;
    private ?float $zoom = null;
    private ?bool $antialiasing = null;
    private ?bool $shadow = null;
    private ?array $lightColor = null;
    private ?float $lightIntensity = null;
    private ?array $cameraPosition = null;
    private Theme $theme = Theme::LIGHT;

    /**
     * Set viewer width in pixels
     */
    public function setWidth(int $width): self
    {
        if ($width <= 0) {
            throw new InvalidConfigurationException('Width must be positive');
        }
        $this->width = $width;
        return $this;
    }

    /**
     * Set viewer height in pixels
     */
    public function setHeight(int $height): self
    {
        if ($height <= 0) {
            throw new InvalidConfigurationException('Height must be positive');
        }
        $this->height = $height;
        return $this;
    }

    /**
     * Set viewer dimensions
     */
    public function setDimensions(int $width, int $height): self
    {
        return $this->setWidth($width)->setHeight($height);
    }

    /**
     * Set background color (hex or CSS color)
     */
    public function setBackgroundColor(string $color): self
    {
        $this->backgroundColor = $color;
        $this->backgroundTransparent = false;
        return $this;
    }

    /**
     * Make background transparent
     */
    public function setBackgroundTransparent(bool $transparent = true): self
    {
        $this->backgroundTransparent = $transparent;
        if ($transparent) {
            $this->backgroundColor = null;
        }
        return $this;
    }

    /**
     * Enable/disable spin animation
     */
    public function setSpin(bool $spin): self
    {
        $this->spin = $spin;
        return $this;
    }

    /**
     * Set initial zoom level
     */
    public function setZoom(float $zoom): self
    {
        $this->zoom = $zoom;
        return $this;
    }

    /**
     * Enable/disable antialiasing
     */
    public function setAntialiasing(bool $antialiasing): self
    {
        $this->antialiasing = $antialiasing;
        return $this;
    }

    /**
     * Enable/disable shadows
     */
    public function setShadow(bool $shadow): self
    {
        $this->shadow = $shadow;
        return $this;
    }

    /**
     * Set theme
     */
    public function setTheme(Theme $theme): self
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Set light color (RGB array)
     */
    public function setLightColor(array $rgb): self
    {
        if (count($rgb) !== 3) {
            throw new InvalidConfigurationException('Light color must be RGB array [r, g, b]');
        }
        $this->lightColor = $rgb;
        return $this;
    }

    /**
     * Set light intensity
     */
    public function setLightIntensity(float $intensity): self
    {
        if ($intensity < 0 || $intensity > 1) {
            throw new InvalidConfigurationException('Light intensity must be between 0 and 1');
        }
        $this->lightIntensity = $intensity;
        return $this;
    }

    /**
     * Set camera position
     */
    public function setCameraPosition(array $position): self
    {
        if (count($position) !== 3) {
            throw new InvalidConfigurationException('Camera position must be [x, y, z]');
        }
        $this->cameraPosition = $position;
        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function getBackgroundTransparent(): ?bool
    {
        return $this->backgroundTransparent;
    }

    public function getSpin(): ?bool
    {
        return $this->spin;
    }

    public function getZoom(): ?float
    {
        return $this->zoom;
    }

    public function getAntialiasing(): ?bool
    {
        return $this->antialiasing;
    }

    public function getShadow(): ?bool
    {
        return $this->shadow;
    }

    public function getTheme(): Theme
    {
        return $this->theme;
    }

    public function getLightColor(): ?array
    {
        return $this->lightColor;
    }

    public function getLightIntensity(): ?float
    {
        return $this->lightIntensity;
    }

    public function getCameraPosition(): ?array
    {
        return $this->cameraPosition;
    }

    /**
     * Convert to array for JSON serialization
     */
    public function toArray(): array
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
            'backgroundColor' => $this->backgroundColor,
            'backgroundTransparent' => $this->backgroundTransparent,
            'spin' => $this->spin,
            'zoom' => $this->zoom,
            'antialiasing' => $this->antialiasing,
            'shadow' => $this->shadow,
            'theme' => $this->theme->value,
            'lightColor' => $this->lightColor,
            'lightIntensity' => $this->lightIntensity,
            'cameraPosition' => $this->cameraPosition,
        ];
    }
}
