<?php
namespace Esu\Examples\Meetphp;
declare(ticks=10);

const MEM_LIMIT=1000;

function tickFunction() {
    //echo "TICK\n";
    $memUsage = floor(memory_get_usage() / 1024);
    
    if ($memUsage > MEM_LIMIT) {
        die ("\n\nOUT OF MEMORY\n");
    }
}

register_tick_function(__NAMESPACE__ . '\tickFunction');

$data =array();
for ($x=0;$x<10000;$x++) {
    echo "x=$x\r";
    $data[] = rand(0, 10000);
    usleep(1000);
}
