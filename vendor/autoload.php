<?php

$loader = new PDBViewerPHP_Autoloader();
spl_autoload_register([$loader, 'autoload']);

class PDBViewerPHP_Autoloader {
    private $basePath = __DIR__ . '/../src/';
    
    public function autoload($class) {
        if (strpos($class, 'PDBViewerPHP') === 0) {
            $path = $this->basePath . str_replace('\\', '/', substr($class, strlen('PDBViewerPHP\\'))) . '.php';
            if (file_exists($path)) {
                require_once $path;
                return true;
            }
        }
        if (strpos($class, 'PDBViewerPHP\\Tests') === 0) {
            $path = __DIR__ . '/../tests/' . str_replace('\\', '/', substr($class, strlen('PDBViewerPHP\\Tests\\'))) . '.php';
            if (file_exists($path)) {
                require_once $path;
                return true;
            }
        }
        return false;
    }
}
