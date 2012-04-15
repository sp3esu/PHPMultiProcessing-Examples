<?php
namespace Esu\Utils;

/**
 * Very simple autoloader :-)
 */
class Autoloader {
    static public function autoload($classname) {
        $filePath = realpath(__DIR__ . '/../../' . str_replace('\\', '/', $classname) . '.php');

        if (file_exists($filePath)) {
            require $filePath;
            return true;
        }

        return false;
    }

    static public function register() {
        spl_autoload_register('Esu\Utils\Autoloader::autoload');
    }
}

