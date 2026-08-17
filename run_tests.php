<?php

declare(strict_types=1);

// Simple test runner - replaces PHPUnit for basic testing
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/tests/BaseTestCase.php';
require_once __DIR__ . '/tests/ConfigurationTest.php';
require_once __DIR__ . '/tests/ViewerTest.php';

use PDBViewerPHP\Tests\{ConfigurationTest, ViewerTest};

echo "========================================\n";
echo "PDBViewerPHP Test Suite\n";
echo "========================================\n\n";

$totalTests = 0;
$passedTests = 0;
$failedTests = 0;
$errors = [];

// Run Configuration Tests
echo "Running Configuration Tests...\n";
echo "----------------------------------------\n";
$configTest = new ConfigurationTest();
try {
    $configTest->runAll();
    $passedTests += 9;
    $totalTests += 9;
} catch (\Exception $e) {
    $failedTests++;
    $errors[] = "ConfigurationTest: " . $e->getMessage();
}

// Run Viewer Tests
echo "\nRunning Viewer Tests...\n";
echo "----------------------------------------\n";
$viewerTest = new ViewerTest();
try {
    $viewerTest->runAll();
    $passedTests += 11;
    $totalTests += 11;
} catch (\Exception $e) {
    $failedTests++;
    $errors[] = "ViewerTest: " . $e->getMessage();
}

// Summary
echo "\n========================================\n";
echo "Test Results\n";
echo "========================================\n";
echo "Total Tests:  $totalTests\n";
echo "Passed:       $passedTests\n";
echo "Failed:       $failedTests\n";

if (!empty($errors)) {
    echo "\nErrors:\n";
    foreach ($errors as $error) {
        echo "  ✗ $error\n";
    }
}

echo "\n";
if ($failedTests === 0) {
    echo "✓ All tests passed!\n";
    exit(0);
} else {
    echo "✗ Some tests failed.\n";
    exit(1);
}
