<?php

declare(strict_types=1);

namespace PDBViewerPHP\Configuration;

/**
 * Configuration for molecular structure loading
 */
class StructureConfiguration
{
    private ?string $pdbId = null;
    private ?string $pdbUrl = null;
    private ?string $mmCifUrl = null;
    private ?string $localFile = null;
    private ?string $rawData = null;
    private ?string $format = null;

    /**
     * Load structure from PDB identifier
     */
    public function setPdbId(string $pdbId): self
    {
        $this->pdbId = $pdbId;
        $this->pdbUrl = null;
        $this->mmCifUrl = null;
        $this->localFile = null;
        $this->rawData = null;
        return $this;
    }

    /**
     * Load structure from PDB URL
     */
    public function setPdbUrl(string $url): self
    {
        $this->pdbUrl = $url;
        $this->pdbId = null;
        $this->mmCifUrl = null;
        $this->localFile = null;
        $this->rawData = null;
        $this->format = 'pdb';
        return $this;
    }

    /**
     * Load structure from mmCIF URL
     */
    public function setMmCifUrl(string $url): self
    {
        $this->mmCifUrl = $url;
        $this->pdbId = null;
        $this->pdbUrl = null;
        $this->localFile = null;
        $this->rawData = null;
        $this->format = 'cif';
        return $this;
    }

    /**
     * Load structure from local file
     */
    public function setLocalFile(string $filePath): self
    {
        $this->localFile = $filePath;
        $this->pdbId = null;
        $this->pdbUrl = null;
        $this->mmCifUrl = null;
        $this->rawData = null;
        return $this;
    }

    /**
     * Load structure from raw data
     */
    public function setRawData(string $data, string $format = 'pdb'): self
    {
        $this->rawData = $data;
        $this->format = $format;
        $this->pdbId = null;
        $this->pdbUrl = null;
        $this->mmCifUrl = null;
        $this->localFile = null;
        return $this;
    }

    public function getPdbId(): ?string
    {
        return $this->pdbId;
    }

    public function getPdbUrl(): ?string
    {
        return $this->pdbUrl;
    }

    public function getMmCifUrl(): ?string
    {
        return $this->mmCifUrl;
    }

    public function getLocalFile(): ?string
    {
        return $this->localFile;
    }

    public function getRawData(): ?string
    {
        return $this->rawData;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * Check if structure source is configured
     */
    public function isConfigured(): bool
    {
        return $this->pdbId !== null
            || $this->pdbUrl !== null
            || $this->mmCifUrl !== null
            || $this->localFile !== null
            || $this->rawData !== null;
    }

    /**
     * Get the type of structure source
     */
    public function getSourceType(): ?string
    {
        if ($this->pdbId !== null) {
            return 'pdb_id';
        }
        if ($this->pdbUrl !== null) {
            return 'pdb_url';
        }
        if ($this->mmCifUrl !== null) {
            return 'mmcif_url';
        }
        if ($this->localFile !== null) {
            return 'local_file';
        }
        if ($this->rawData !== null) {
            return 'raw_data';
        }
        return null;
    }

    /**
     * Convert to array for JSON serialization
     */
    public function toArray(): array
    {
        return [
            'pdbId' => $this->pdbId,
            'pdbUrl' => $this->pdbUrl,
            'mmCifUrl' => $this->mmCifUrl,
            'localFile' => $this->localFile,
            'rawData' => $this->rawData,
            'format' => $this->format,
        ];
    }
}
