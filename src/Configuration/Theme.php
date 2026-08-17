<?php

declare(strict_types=1);

namespace PDBViewerPHP\Configuration;

/**
 * UI themes
 */
enum Theme: string
{
    case LIGHT = 'light';
    case DARK = 'dark';
    case MINIMAL = 'minimal';
}
