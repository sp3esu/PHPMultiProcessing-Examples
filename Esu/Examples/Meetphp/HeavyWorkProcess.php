<?php
namespace Esu\Examples\Meetphp;

class HeavyWorkProcess {
    
    private $_counter = 0;
    
    public function __construct($counter) {
        $this->_counter = $counter;
    }

    public function doSomeHeavyWork() {
        for ($x=0;$x<$this->_counter;$x++) {
            echo "$x\n";
            sleep(1);
        }
    }
}