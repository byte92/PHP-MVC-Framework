<?php

class Runtime
{
    public $startTime = 0;
    public $endTime = 0;

    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    function start_microtime()
    {
        $this->startTime = $this->microtime_float();
    }

    function end_microtime()
    {
        $this->endTime = $this->microtime_float();
    }

    function spend_millisecond()
    {
        return round(($this->endTime - $this->startTime) * 1000, 2);
    }

    function spend_microsecond()
    {
        return round(($this->endTime - $this->startTime) * 1000 * 1000, 2);
    }

}