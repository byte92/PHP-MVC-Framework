<?php

class index extends Controller
{
    public function run()
    {
        $GLOBALS['time'] = microtime(true);

        $data = array(
            "pl_header"   => "pl_header is loaded!",
            "pl_content"  => "pl_content is loaded!",
            "pl_footer"   => "pl_footer is loaded!"
        );

        $this->renderPage('index', $data, true);
    }
}