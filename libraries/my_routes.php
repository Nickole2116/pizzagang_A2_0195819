<?php

/**
 * --------------------------------------
 * Routes Validation of Controllers
 * --------------------------------------
 */

    $route = new Controller();
    
    function __check_functions($class,$func)
    {
        if(method_exists($class,$func))
        {
            return 1;
        }else
        {
            return 0;
        }
    }

    function load($view_name,array $data=[])
    {
        $view = "../Views/".$view_name;
        header("Location: $view");
        $data_return = json_encode($data);
        return $data_return;

    }








?>