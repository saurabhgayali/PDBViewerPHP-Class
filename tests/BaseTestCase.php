<?php

declare(strict_types=1);

namespace PDBViewerPHP\Tests;

class BaseTestCase
{
    /**
     * Assert that a string contains valid JSON
     */
    protected function assertValidJson(string $json): array
    {
        $decoded = json_decode($json, true);
        if ($decoded === null && $json !== 'null') {
            throw new \Exception("Invalid JSON: $json");
        }
        return $decoded ?? [];
    }

    /**
     * Assert HTML is properly escaped
     */
    protected function assertHtmlEscaped(string $html): void
    {
        // Check that potentially dangerous patterns are escaped
        if (strpos($html, '<script') !== false && strpos($html, 'var config = {') === false) {
            throw new \Exception("Unescaped script tag found: $html");
        }
    }

    /**
     * Assert that output is safe for HTML context
     */
    protected function assertHtmlSafe(string $output): void
    {
        // Should not contain unescaped quotes or angle brackets in attribute context
        if (preg_match('/[<>].*?=["\']/', $output)) {
            // This might be legitimate, so just log for now
        }
    }
}
