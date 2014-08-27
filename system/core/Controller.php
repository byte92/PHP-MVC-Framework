<?php

abstract class Controller
{
    public $parameter = array();
    public $modal = array();
    private $config = '';

    public function __construct($parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * @desc abstract function, the main action
     */
    abstract function run();

    /**
     * @param $viewName page file
     * @param array $data data of the custume
     * @reutrn null
     */
    public function renderPage($viewName, $data = array(), $renderType = false)
    {
        // render main page
        $this->_get_page($viewName, $data);

        // render  pagelet
        $this->_get_pagelet($viewName, $data, $renderType);
    }

    /**
     * @desc load the main page
     * @param $viewName page file
     * @param $data data of the custume
     * @return null
     */
    private function _get_page($viewName, $data)
    {
        if (file_exists("application/views/{$viewName}/{$viewName}.phtml")) {
            $html = file_get_contents("application/views/{$viewName}/{$viewName}.phtml");
            $lastPos = strripos($html, '</body>');
            $html = substr_replace($html, '', $lastPos, 16);
            eval('?>'.$html);
            $this->_flush();
        }
        else
        {
            exit("Error: {$viewName}.phtml is not exists " . __FILE__ . ' ' . __LINE__);
        }
    }

    private function _get_pagelet($viewName, $data = array(), $renderType = false)
    {
        //  render pagelet
        $this->config = $this->_get_config($viewName);

        if($renderType)
        {
            $this->_parallel_render($viewName, $data);
        }
        else
        {
            $this->_serival_render($viewName, $data);
        }

    }

    /**
     * @desc serival render pagelet
     */
    private function _serival_render($viewName, $data = array())
    {
        foreach($this->config as $key => $value)
        {
            $pagelet = '';
            if(file_exists("application/views/{$viewName}/{$key}.phtml"))
            {
                $pagelet = file_get_contents("application/views/{$viewName}/{$key}.phtml", true);
                ob_start();
                eval("?>" . $pagelet);
                $buffer = ob_get_contents();
                ob_end_clean();

            }
            $value->html = $buffer;
            $value->time = (microtime(true) - $GLOBALS['time']) * 1000;
            $tmp = json_encode($value);
            echo "<script>{$value->fun}({$tmp});</script>\r\n";

            $this->_flush();
        }

        echo "</body>\r\n</html>";
    }

    /**
     * @desc parallel render pagelet
     */
    private function _parallel_render($viewName, $data = array())
    {
        $thread = '';
        $pool = array();
        foreach($this->config as $plName => $plConfig)
        {

            if(file_exists("application/views/{$viewName}/{$plName}.phtml"))
            {
                $pagelet = file_get_contents("application/views/{$viewName}/{$plName}.phtml", true);
            }
            else
            {
                exit("Error: {$plName} is not exits " . __FILE__ . ' ' . __LINE__);
            }

            $thread = new ParallelRender($viewName, $plName, $plConfig, $pagelet, $data, $GLOBALS['time']);
            $thread->start();
            array_push($pool, $thread);
        }

        // 加入线程池，同时启动
//        foreach($pool as $key => $value)
//        {
//            $value->start();
//        }

        echo "</body>\r\n</html>";
    }

    /**
     * @desc get config of pagelet
     * @param $viewName
     * @return config object
     */
    private function _get_config($viewName)
    {
        if (file_exists("application/views/{$viewName}/{$viewName}.json"))
        {
            return json_decode(file_get_contents("application/views/{$viewName}/{$viewName}.json", true));
        }
        else
        {
            exit("Error: {$viewName}.json is not exists " . __FILE__ . ' ' . __LINE__);
        }

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