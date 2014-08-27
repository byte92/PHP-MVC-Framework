<?php

class ParallelRender extends Thread
{
    private $data     = array();
    private $plName   = '';
    private $plConfig = array();
    private $viewName = '';
    private $html     = '';
    private $startTime = 0;

    public function __construct($viewName, $plName, $plConfig=array(), $html = '', $data=array(), $time)
    {
        $this->data     = $data;
        $this->plName   = $plName;
        $this->plConfig = $plConfig;
        $this->viewName = $viewName;
        $this->html     = $html;

        $this->startTime = $time;
    }

    public function run()
    {
        $data = $this->data;
        ob_start();
        eval('?>' . $this->html);
        $buffer = ob_get_contents();
        ob_end_clean();
        $config = $this->plConfig;

        $config->html = $buffer;
        $config->time = (microtime(true) - $this->startTime) * 1000;
        $tmp = json_encode($config);

        echo "<script>{$this->plConfig->fun}({$tmp});</script>\r\n";

        $this->_flush();
    }

    private function _flush()
    {
        if(ob_get_level())
        {
            ob_flush();
        }
        flush();
    }
}