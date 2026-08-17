<?php

declare(strict_types=1);

namespace PDBViewerPHP\Configuration;

/**
 * Color schemes supported by 3Dmol.js
 */
enum ColorScheme: string
{
    case CHAIN = 'chain';
    case SPECTRUM = 'spectrum';
    case ATOM = 'atom';
    case RESIDUE = 'residue';
    case CARTOON = 'cartoon';
    case SSTYPE = 'sstype';
    case HYDRO = 'hydro';
}
