<?php

declare(strict_types=1);

namespace PDBViewerPHP\Tests;

require_once __DIR__ . '/BaseTestCase.php';

use PDBViewerPHP\Configuration\{
    StructureConfiguration,
    RepresentationConfiguration,
    AppearanceConfiguration,
    ControlConfiguration,
    RepresentationType,
    ColorScheme,
    Theme,
};
use PDBViewerPHP\Exception\InvalidConfigurationException;

class ConfigurationTest extends BaseTestCase
{
    /**
     * Test StructureConfiguration with PDB ID
     */
    public function testStructureConfigurationPdbId(): void
    {
        $config = new StructureConfiguration();
        $config->setPdbId('1ABC');

        if ($config->getPdbId() !== '1ABC') {
            throw new \Exception('PDB ID not set correctly');
        }

        if ($config->getSourceType() !== 'pdb_id') {
            throw new \Exception('Source type should be pdb_id');
        }

        if (!$config->isConfigured()) {
            throw new \Exception('Configuration should be marked as configured');
        }

        echo "✓ StructureConfiguration PDB ID test passed\n";
    }

    /**
     * Test StructureConfiguration with URL
     */
    public function testStructureConfigurationUrl(): void
    {
        $config = new StructureConfiguration();
        $config->setPdbUrl('https://files.rcsb.org/download/1ABC.pdb');

        if ($config->getPdbUrl() !== 'https://files.rcsb.org/download/1ABC.pdb') {
            throw new \Exception('PDB URL not set correctly');
        }

        if ($config->getSourceType() !== 'pdb_url') {
            throw new \Exception('Source type should be pdb_url');
        }

        echo "✓ StructureConfiguration URL test passed\n";
    }

    /**
     * Test StructureConfiguration with raw data
     */
    public function testStructureConfigurationRawData(): void
    {
        $config = new StructureConfiguration();
        $pdbData = "ATOM      1  N   ALA A   1";
        $config->setRawData($pdbData, 'pdb');

        if ($config->getRawData() !== $pdbData) {
            throw new \Exception('Raw data not set correctly');
        }

        if ($config->getFormat() !== 'pdb') {
            throw new \Exception('Format should be pdb');
        }

        if ($config->getSourceType() !== 'raw_data') {
            throw new \Exception('Source type should be raw_data');
        }

        echo "✓ StructureConfiguration raw data test passed\n";
    }

    /**
     * Test RepresentationConfiguration
     */
    public function testRepresentationConfiguration(): void
    {
        $config = new RepresentationConfiguration();
        $config->setRepresentation(RepresentationType::CARTOON);
        $config->setColorScheme(ColorScheme::CHAIN);

        $repArray = $config->toArray();
        if (!in_array('cartoon', $repArray['representations'])) {
            throw new \Exception('Cartoon representation not added');
        }

        if ($repArray['colorScheme'] !== 'chain') {
            throw new \Exception('Color scheme not set correctly');
        }

        echo "✓ RepresentationConfiguration test passed\n";
    }

    /**
     * Test AppearanceConfiguration
     */
    public function testAppearanceConfiguration(): void
    {
        $config = new AppearanceConfiguration();
        $config->setDimensions(800, 600);
        $config->setBackgroundColor('#FFFFFF');
        $config->setTheme(Theme::DARK);
        $config->setSpin(true);

        if ($config->getWidth() !== 800) {
            throw new \Exception('Width not set correctly');
        }

        if ($config->getHeight() !== 600) {
            throw new \Exception('Height not set correctly');
        }

        if ($config->getBackgroundColor() !== '#FFFFFF') {
            throw new \Exception('Background color not set correctly');
        }

        if ($config->getTheme() !== Theme::DARK) {
            throw new \Exception('Theme not set correctly');
        }

        if ($config->getSpin() !== true) {
            throw new \Exception('Spin not set correctly');
        }

        echo "✓ AppearanceConfiguration test passed\n";
    }

    /**
     * Test AppearanceConfiguration validation
     */
    public function testAppearanceConfigurationValidation(): void
    {
        $config = new AppearanceConfiguration();

        try {
            $config->setWidth(-100);
            throw new \Exception('Should have thrown exception for negative width');
        } catch (InvalidConfigurationException $e) {
            // Expected
        }

        try {
            $config->setHeight(0);
            throw new \Exception('Should have thrown exception for zero height');
        } catch (InvalidConfigurationException $e) {
            // Expected
        }

        try {
            $config->setLightColor([1, 2]); // Only 2 values
            throw new \Exception('Should have thrown exception for invalid RGB array');
        } catch (InvalidConfigurationException $e) {
            // Expected
        }

        echo "✓ AppearanceConfiguration validation test passed\n";
    }

    /**
     * Test ControlConfiguration
     */
    public function testControlConfiguration(): void
    {
        $config = new ControlConfiguration();

        if (!$config->isZoomEnabled()) {
            throw new \Exception('Zoom should be enabled by default');
        }

        if (!$config->isRotateEnabled()) {
            throw new \Exception('Rotate should be enabled by default');
        }

        if ($config->isDownloadEnabled()) {
            throw new \Exception('Download should be disabled by default');
        }

        $config->setZoomEnabled(false);
        $config->setDownloadEnabled(true);

        if ($config->isZoomEnabled()) {
            throw new \Exception('Zoom should be disabled after setZoomEnabled(false)');
        }

        if (!$config->isDownloadEnabled()) {
            throw new \Exception('Download should be enabled after setDownloadEnabled(true)');
        }

        echo "✓ ControlConfiguration test passed\n";
    }

    /**
     * Test ControlConfiguration convenience methods
     */
    public function testControlConfigurationConvenience(): void
    {
        $config = new ControlConfiguration();

        $config->hideZoomControl();
        if ($config->isZoomEnabled()) {
            throw new \Exception('hideZoomControl should disable zoom');
        }

        $config->showZoomControl();
        if (!$config->isZoomEnabled()) {
            throw new \Exception('showZoomControl should enable zoom');
        }

        $config->showDownloadControl();
        if (!$config->isDownloadEnabled()) {
            throw new \Exception('showDownloadControl should enable download');
        }

        $config->hideDownloadControl();
        if ($config->isDownloadEnabled()) {
            throw new \Exception('hideDownloadControl should disable download');
        }

        echo "✓ ControlConfiguration convenience methods test passed\n";
    }

    /**
     * Test configuration serialization
     */
    public function testConfigurationSerialization(): void
    {
        $config = new AppearanceConfiguration();
        $config->setWidth(600);
        $config->setHeight(600);
        $config->setTheme(Theme::LIGHT);

        $array = $config->toArray();

        if (!is_array($array)) {
            throw new \Exception('toArray should return array');
        }

        if ($array['width'] !== 600) {
            throw new \Exception('Width not serialized correctly');
        }

        if ($array['theme'] !== 'light') {
            throw new \Exception('Theme not serialized correctly');
        }

        // Check JSON serializable
        $json = json_encode($array);
        if ($json === false) {
            throw new \Exception('Configuration should be JSON serializable');
        }

        echo "✓ Configuration serialization test passed\n";
    }

    public function runAll(): void
    {
        $this->testStructureConfigurationPdbId();
        $this->testStructureConfigurationUrl();
        $this->testStructureConfigurationRawData();
        $this->testRepresentationConfiguration();
        $this->testAppearanceConfiguration();
        $this->testAppearanceConfigurationValidation();
        $this->testControlConfiguration();
        $this->testControlConfigurationConvenience();
        $this->testConfigurationSerialization();
    }
}
