<?php
namespace Esu\Multiprocessing;

class Controller {
    private static $_instance;

    /* child process properties */
    protected $_isChild = false;
    protected $_stdIn = null;
    protected $_stdOut = null;
    protected $_stdErr = null;

    /* main process properties */
    protected $_running = array();

    private function __construct() {}

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function addProcess($proc) {
        $pid = $proc->getPid();

        if (!$this->_running[$pid]) {
            $this->_running[$pid] = $proc;
        }
    }

    public function getRunningPids() {
        $runnedPids = array();
        if (!empty($this->_running)) {
            foreach ($this->_running as $pid => $proces) {
                if ($proces->isRunning()) {
                    $runnedPids[] = $pid;
                }
            }
        }
        
        return $runnedPids;
    }

    public function getRunningCount() {
        $runnedPids = $this->getRunningPids();
        return count($runnedPids);
    }

    public function tick() {
        echo "\nTICK!\n";
    }

    public function handle() {
        register_tick_function(array($this, 'tick'));
        $this->_isChild = true;

        $this->_stdIn = fopen('php://stdin', 'r');
        $this->_stdOut = fopen('php://stdout', 'w');
        $this->_stdErr = fopen('php://stderr', 'w');

        while (true) {
            // TODO: add some 'system' commands, like 'getMemUsage', or 'selfKill' ;-)
            $possibleCommand = trim(fgets($this->_stdIn));

            $callback = unserialize($possibleCommand);
            if ($callback && is_array($callback)) {
                call_user_func($callback);
            }

            //usleep(100);  // we don't want to kill uC ;-)
            break;  // break because we do not have any 'system' command support ;-)
        }
    }

}
