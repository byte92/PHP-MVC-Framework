<?php

function __autoload($className)
{
    $map = array(
        'Config'         => 'system/config/Config.php',
        'Router'         => 'system/core/Router.php',
        'Controller'     => 'system/core/Controller.php',
        'Runtime'        => 'system/libraries/Runtime.php',
        'ParallelRender' => 'system/core/ParallelRender.php'
    );

    if($map[$className] && is_file($map[$className]))
    {
        require($map[$className]);
    }
    else
    {
        exit("Error: {$className} is not exits " . __FILE__ . ' ' . __LINE__);
    }
}

class App {

    public function __construct()
    {
        require_once('Common.php');
    }

    public function load_controller($class, $parameter = array())
    {
        if(is_file('application/controllers/' . $class . '.php'))
        {
            require('application/controllers/' . $class . '.php');
            return new $class($parameter);
        }
        else
        {
            exit('Error: ' . $class . ' is exits,  '.__FILE__ .' '.__LINE__);
        }
    }

    public function run()
    {
        $router = new Router();

        $class = $router->get_class();
        $method = $router->get_method();
        $parameter = $router->get_parameter();

        $controller = $this->load_controller($class, $parameter);

        if(method_exists($controller, $method))
        {
            $controller->$method();
        }
        else
        {
            exit("Error: {$method} method is not exits " . __FILE__ . ' ' . __LINE__);
        }
    }

}