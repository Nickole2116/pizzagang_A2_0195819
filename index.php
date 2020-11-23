<?php


/**
 * @author Nickole Tan Yin Yin 
 * @since 2020/11/23 
 * @copyright Copyright (c) 2020, Pizza-Gang, PizzaG Management Team 
 */

 require_once("libraries/my_constant.php");
 require_once("libraries/my_functions.php");
 require_once("config/Session.php");


 /**
  *---------------------------------------------------------------
  * ROUTES ENVIRONMENT
  *---------------------------------------------------------------
  * Firstly, to Check out the session variable have to iniliate or
  * not. IF the each session key had been set before, its will goes
  * into their environment controls.
  * 
  * The ENVIRONMENT CONTROLS is :
  *     
  *     administrator sites 
  *     member sites
  *     visitor sites
  *
  */
  
  $s = new Session();
  Session::set_userdata('role',"visitor");

  $_ROLE = Session::userdata('role');
  switch($_ROLE)
  {
    case 'member':
        header("Location: ./Views/member/home.html");

    break;
    case 'admin':
        echo "admin sites";

    break;
    case 'visitor':
        echo "visitor sites";

    break;
    default:
        echo "none";

    break;

  }

/*if(file_exists(__FILE__))
{
    //echo "ya";
    //echo dirname($_SERVER['REQUEST_URI']);
    //header("Location: ./Views/member/home.html");

    //echo TEST;
}else 
{
    echo "no";
    //header("Location: index.php");
}*/

?>