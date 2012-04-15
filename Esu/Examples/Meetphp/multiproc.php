<?php
namespace Esu\Examples\Meetphp;

use \Esu\Multiprocessing\Process;
use \Esu\Multiprocessing\Controller;

require __DIR__ . '/../../Utils/Autoloader.php';
\Esu\Utils\Autoloader::register();


for ($x=0;$x<50;$x++) {
    $y = new HeavyWorkProcess(rand(0,20));
    $p = new Process(array($y, 'doSomeHeavyWork'));
    $p->run();
}

while(($count = Controller::getInstance()->getRunningCount()) > 0) {
    echo "Running processes: $count    \r";
    sleep(1);
}

echo "\nDone!\n";