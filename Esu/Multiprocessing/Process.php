<?php
namespace Esu\Multiprocessing;

class Process {
    protected $_callback=array();

    private $_pipes = array();
    private $_handler = false;
    private $_cwd = null;

    private $_status = array();

    public function __construct($callback=null) {
        if ($callback) {
            $this->_callback = $callback;
        }
    }

    public function isRunning() {
        $status = $this->getStatus();
        return (bool)$status['running'];
    }

    public function getPid() {
        $status = $this->getStatus();
        return $status['pid'];
    }

    public function getStatus() {
        return proc_get_status($this->_handler);
    }

    public function getOutput() {
        $data = stream_get_contents($this->_pipes[1]);
        return $data;
    }

    public function run() {
        if (is_resource($this->_handler) && $this->isRunning()) {
            throw new \Exception('Process already running!');
        }

        $desc = array(
            0 => array('pipe', 'r'), // stdin
            1 => array('pipe', 'w'), // stdout
            //2 => array('pipe', 'w')  // stderr
            2 => array('file', getcwd() . '/childError.log', 'a')
        );

        $cmd = 'php ' . __DIR__ . '/Launcher.php';

        if (false === $this->_handler = proc_open($cmd, $desc, $pipes)) {
            throw new \Exception('Cannot create proces!');
        }

        $this->_pipes = $pipes;

        if (is_resource($this->_handler)) {
            Controller::getInstance()->addProcess($this);
            fwrite($this->_pipes[0], serialize($this->_callback) . PHP_EOL);
        }
    }

    public function close() {
        $returnVal = null;
        if (is_resource($this->_handler) && $this->isRunning()) {
            fclose($this->_pipes[0]);
            fclose($this->_pipes[1]);
            fclose($this->_pipes[2]);
            $returnVal = proc_close($this->_handler);
        }

        return $returnVal;
    }

    public function terminate($signal=null) {
        if (is_resource($this->_handler) && $this->isRunning()) {
            fclose($this->_pipes[0]);
            fclose($this->_pipes[1]);
            fclose($this->_pipes[2]);
            return proc_terminate($this->_handler, $signal);
        }

        return null;
    }

    public function kill() {
        $this->close();
    }
}
