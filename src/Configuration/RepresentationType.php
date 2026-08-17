<?php

declare(strict_types=1);

namespace PDBViewerPHP\Configuration;

/**
 * Representation types supported by 3Dmol.js
 */
enum RepresentationType: string
{
    case CARTOON = 'cartoon';
    case STICK = 'stick';
    case SPHERE = 'sphere';
    case LINE = 'line';
    case CROSS = 'cross';
    case RIBBON = 'ribbon';
    case BACKBONE = 'backbone';
    case SURFACE = 'surface';
    case LABEL = 'label';
}
