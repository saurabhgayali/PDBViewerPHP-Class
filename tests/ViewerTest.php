<?php

declare(strict_types=1);

namespace PDBViewerPHP\Tests;

require_once __DIR__ . '/BaseTestCase.php';

use PDBViewerPHP\PDBViewer;
use PDBViewerPHP\Configuration\{RepresentationType, ColorScheme, Theme};

class ViewerTest extends BaseTestCase
{
    /**
     * Test basic viewer creation
     */
    public function testViewerCreation(): void
    {
        $viewer = new PDBViewer();

        if ($viewer->getViewerId() !== 'pdbviewer') {
            throw new \Exception('Default viewer ID should be pdbviewer');
        }

        echo "✓ Viewer creation test passed\n";
    }

    /**
     * Test fluent API chaining
     */
    public function testFluentApi(): void
    {
        $viewer = new PDBViewer();
        $result = $viewer
            ->loadPDB('1ABC')
            ->setRepresentation(RepresentationType::CARTOON)
            ->setColorScheme(ColorScheme::CHAIN)
            ->setWidth(800)
            ->setHeight(600);

        if (!$result instanceof PDBViewer) {
            throw new \Exception('Fluent API should return PDBViewer instance');
        }

        echo "✓ Fluent API test passed\n";
    }

    /**
     * Test configuration getters
     */
    public function testConfigurationGetters(): void
    {
        $viewer = new PDBViewer();
        $viewer->loadPDB('1ABC');

        $structure = $viewer->getStructure();
        if ($structure->getPdbId() !== '1ABC') {
            throw new \Exception('Structure configuration not retrieved correctly');
        }

        echo "✓ Configuration getters test passed\n";
    }

    /**
     * Test HTML rendering
     */
    public function testHtmlRendering(): void
    {
        $viewer = new PDBViewer();
        $viewer->loadPDB('1ABC')->setWidth(600)->setHeight(600);

        $html = $viewer->renderHtml();

        if (empty($html)) {
            throw new \Exception('HTML should not be empty');
        }

        if (strpos($html, 'pdbviewer') === false) {
            throw new \Exception('HTML should contain viewer ID');
        }

        if (strpos($html, 'pdbviewer-container') === false) {
            throw new \Exception('HTML should contain container class');
        }

        echo "✓ HTML rendering test passed\n";
    }

    /**
     * Test JavaScript rendering
     */
    public function testJavaScriptRendering(): void
    {
        $viewer = new PDBViewer();
        $viewer->loadPDB('1ABC');

        $js = $viewer->renderJavaScript();

        if (empty($js)) {
            throw new \Exception('JavaScript should not be empty');
        }

        if (strpos($js, 'createViewer') === false) {
            throw new \Exception('JavaScript should contain viewer creation');
        }

        echo "✓ JavaScript rendering test passed\n";
    }

    /**
     * Test configuration JSON
     */
    public function testConfigurationJson(): void
    {
        $viewer = new PDBViewer();
        $viewer->loadPDB('1ABC')
            ->setRepresentation(RepresentationType::STICK)
            ->setColorScheme(ColorScheme::SPECTRUM)
            ->setWidth(800)
            ->setHeight(600);

        $json = $viewer->getConfigurationJson();
        $config = $this->assertValidJson($json);

        if ($config['structure']['pdbId'] !== '1ABC') {
            throw new \Exception('PDB ID not in configuration JSON');
        }

        if (!in_array('stick', $config['representation']['representations'])) {
            throw new \Exception('Stick representation not in configuration JSON');
        }

        if ($config['appearance']['width'] !== 800) {
            throw new \Exception('Width not in configuration JSON');
        }

        echo "✓ Configuration JSON test passed\n";
    }

    /**
     * Test complete render with external resources
     */
    public function testCompleteRender(): void
    {
        $viewer = new PDBViewer();
        $viewer->loadPDB('1ABC')
            ->setRepresentation(RepresentationType::CARTOON)
            ->setWidth(600)
            ->setHeight(600);

        $output = $viewer->render();

        if (empty($output)) {
            throw new \Exception('Complete render should not be empty');
        }

        if (strpos($output, '<link') === false) {
            throw new \Exception('Complete render should include CSS link');
        }

        if (strpos($output, '<script') === false) {
            throw new \Exception('Complete render should include script tags');
        }

        if (strpos($output, '3Dmol') === false) {
            throw new \Exception('Complete render should reference 3Dmol');
        }

        echo "✓ Complete render test passed\n";
    }

    /**
     * Test control configuration through viewer
     */
    public function testControlConfiguration(): void
    {
        $viewer = new PDBViewer();
        $viewer->hideDownloadControl()
            ->showFullscreenControl()
            ->hideScreenshotControl();

        $controls = $viewer->getControls();

        if ($controls->isDownloadEnabled()) {
            throw new \Exception('Download control should be disabled');
        }

        if (!$controls->isFullscreenEnabled()) {
            throw new \Exception('Fullscreen control should be enabled');
        }

        if ($controls->isScreenshotEnabled()) {
            throw new \Exception('Screenshot control should be disabled');
        }

        echo "✓ Control configuration test passed\n";
    }

    /**
     * Test viewer ID customization
     */
    public function testViewerIdCustomization(): void
    {
        $viewer = new PDBViewer();
        $viewer->setViewerId('my-custom-viewer');

        if ($viewer->getViewerId() !== 'my-custom-viewer') {
            throw new \Exception('Custom viewer ID not set');
        }

        $html = $viewer->renderHtml();
        if (strpos($html, 'my-custom-viewer') === false) {
            throw new \Exception('Custom viewer ID should appear in HTML');
        }

        echo "✓ Viewer ID customization test passed\n";
    }

    /**
     * Test theme configuration
     */
    public function testThemeConfiguration(): void
    {
        $viewer = new PDBViewer();
        $viewer->setTheme(Theme::DARK);

        $appearance = $viewer->getAppearance();
        if ($appearance->getTheme() !== Theme::DARK) {
            throw new \Exception('Theme not set correctly');
        }

        $html = $viewer->renderHtml();
        if (strpos($html, 'pdbv-dark') === false) {
            throw new \Exception('Dark theme class should be in HTML');
        }

        echo "✓ Theme configuration test passed\n";
    }

    /**
     * Test appearance configuration through viewer
     */
    public function testAppearanceConfiguration(): void
    {
        $viewer = new PDBViewer();
        $viewer->setBackgroundColor('#FF0000')
            ->setSpin(true)
            ->setZoom(2.5);

        $appearance = $viewer->getAppearance();

        if ($appearance->getBackgroundColor() !== '#FF0000') {
            throw new \Exception('Background color not set');
        }

        if ($appearance->getSpin() !== true) {
            throw new \Exception('Spin not set');
        }

        if ($appearance->getZoom() !== 2.5) {
            throw new \Exception('Zoom not set');
        }

        echo "✓ Appearance configuration test passed\n";
    }

    /**
     * Test container ID handling in JavaScript (regression test)
     * Verifies that the viewer is initialized with the correct element ID string
     * and includes error checking for missing container elements
     */
    public function testContainerIdHandling(): void
    {
        $viewer = new PDBViewer();
        $viewer->loadPDB('1ABC')->setViewerId('custom-viewer-id');

        $js = $viewer->renderJavaScript();

        // Verify the viewer container ID is used correctly
        if (strpos($js, "const viewerContainerId = viewerId + '-viewer'") === false) {
            throw new \Exception('JavaScript should construct viewer container ID');
        }

        // Verify container element verification is in place
        if (strpos($js, "document.getElementById(viewerContainerId)") === false) {
            throw new \Exception('JavaScript should verify container element exists');
        }

        // Verify element ID string is passed to createViewer (not DOM element)
        if (strpos($js, "\$3Dmol.createViewer(viewerContainerId,") === false) {
            throw new \Exception('JavaScript should pass element ID string to createViewer');
        }

        // Verify error handling for missing container
        if (strpos($js, "Viewer container element not found") === false) {
            throw new \Exception('JavaScript should have error handling for missing container');
        }

        // Verify the HTML contains the correct viewer element ID
        $html = $viewer->renderHtml();
        if (strpos($html, "id=\"custom-viewer-id-viewer\"") === false) {
            throw new \Exception('HTML should contain viewer element with correct ID');
        }

        echo "✓ Container ID handling regression test passed\n";
    }

    public function runAll(): void
    {
        $this->testViewerCreation();
        $this->testFluentApi();
        $this->testConfigurationGetters();
        $this->testHtmlRendering();
        $this->testJavaScriptRendering();
        $this->testConfigurationJson();
        $this->testCompleteRender();
        $this->testControlConfiguration();
        $this->testViewerIdCustomization();
        $this->testThemeConfiguration();
        $this->testAppearanceConfiguration();
        $this->testContainerIdHandling();
    }
}
