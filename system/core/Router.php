<?php

class Router
{
    public $segment = array();
    public $class = '';
    public $method = '';
    public $parameter = array();

    public function __construct()
    {
        // $this->segment = preg_split('/\//', $_SERVER['REQUEST_URI']); // 1微秒差距
        $segtmp = explode('sepf', $_SERVER['REQUEST_URI']);
        $segtmp = count($segtmp) == 1 ? $segtmp[0] : $segtmp[1];
        $this->segment = explode('/', $segtmp);
        $this->class = isset($this->segment[2]) && $this->segment[2] != '' ?
            $this->segment[2] : exit('Error: class is null,  '.__FILE__ .' '.__LINE__);
        $this->method = isset($this->segment[3]) && $this->segment[3] != '' ? $this->segment[3] : 'run';
        $this->parameter = array_merge($this->parameter, array_slice($this->segment, 4));
    }

    public function get_class()
    {
        return $this->class;
    }

    public function get_method()
    {
        return $this->method;
    }

    public function get_parameter()
    {
        return $this->parameter;
    }
}