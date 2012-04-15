<?php
namespace Esu\Multiprocessing;
declare(ticks=10);

class Launcher {
    public function __construct() {}

    public static function init() {
        Controller::getInstance()->handle();
    }
}

require __DIR__ . '/../utils/Autoloader.php';
\Esu\Utils\Autoloader::register();

Launcher::init();
