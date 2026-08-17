<?php

declare(strict_types=1);

namespace PDBViewerPHP\Configuration;

use PDBViewerPHP\Exception\InvalidConfigurationException;

/**
 * Configuration for molecular representation
 */
class RepresentationConfiguration
{
    /** @var RepresentationType[] */
    private array $representations = [];
    private ?ColorScheme $colorScheme = null;
    /** @var array<string, mixed> */
    private array $selectionRepresentations = [];

    /**
     * Set global representation type
     */
    public function setRepresentation(RepresentationType $type): self
    {
        $this->representations = [$type];
        return $this;
    }

    /**
     * Add a representation type
     */
    public function addRepresentation(RepresentationType $type): self
    {
        $this->representations[] = $type;
        return $this;
    }

    /**
     * Set color scheme
     */
    public function setColorScheme(ColorScheme $scheme): self
    {
        $this->colorScheme = $scheme;
        return $this;
    }

    /**
     * Add selection-specific representation
     * 
     * @param string $selector CSS selector string
     * @param RepresentationType $type
     * @param array<string, mixed> $options
     */
    public function addSelectionRepresentation(
        string $selector,
        RepresentationType $type,
        array $options = []
    ): self {
        $this->selectionRepresentations[$selector] = [
            'type' => $type,
            'options' => $options,
        ];
        return $this;
    }

    /**
     * @return RepresentationType[]
     */
    public function getRepresentations(): array
    {
        return $this->representations;
    }

    public function getColorScheme(): ?ColorScheme
    {
        return $this->colorScheme;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSelectionRepresentations(): array
    {
        return $this->selectionRepresentations;
    }

    /**
     * Check if any representations are configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->representations) || $this->colorScheme !== null;
    }

    /**
     * Convert to array for JSON serialization
     */
    public function toArray(): array
    {
        return [
            'representations' => array_map(
                fn($r) => $r->value,
                $this->representations
            ),
            'colorScheme' => $this->colorScheme?->value,
            'selectionRepresentations' => $this->selectionRepresentations,
        ];
    }
}
