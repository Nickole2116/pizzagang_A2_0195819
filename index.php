<?php


/**
 * @author Nickole Tan Yin Yin 
 * @since 2020/11/23 
 * @copyright Copyright (c) 2020, Pizza-Gang, PizzaG Management Team 
 */

 require_once("libraries/my_constant.php");
 require_once("libraries/my_functions.php");
 require_once("config/Session.php");
 require_once("config/PDOConn.php");
 require_once("Models/CRUD.php");




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
  $cur_session = Session::get_session_id();
  $conn = PDOConnection::getConnection();
  $db_query = new Model($conn);
  $_log = $db_query->check_visitor_log($cur_session);
  $my_functions = new My_functions();
  $cur_time = $my_functions->now();

  /**check userdata - role */
  /**
   * if role is admin, then load 
   * if role empty, then check db got session (visitor)
   * no session , add session
   * got session, update session date and status (visitor)
   * 
   */
//check here - db visitor log 
//if got

    if($_log == "yes")
    {
        $_ROLE = Session::userdata('role');
        switch($_ROLE)
        {
            case 'member':
                /**
                 * update status 
                 */
                $update_log = $db_query->update_log_visitor($cur_time, 1, 1, $cur_session);
                header("Location: ./Views/member/home.html");

            break;
            case 'admin':
                /**
                 * update status
                 */
                $update_log = $db_query->update_log_visitor($cur_time, 3, 2, $cur_session);

                header("Location: ./Views/admin/dashboard.html");

            break;
            case 'visitor':
                /**
                 * 1. userdata set role and visit - session_ids
                 */
                $update_log = $db_query->update_log_visitor($cur_time, 2, 0, $cur_session);
                $visitor_token = $my_functions->md5_generator($cur_session);
                Session::set_userdata('visit',$visitor_token);

                header("Location: ./Views/visitor/index.html");

            break;
            default:
            /**add
             * session  here
             * visitor log
             * set userdata role and visitor 
             */
                echo "NO ROLE TAKEN.";

            break;

        }

    }else
    {
        //if db no exsited any visitor log matched
        //add 

        $cur_session = Session::get_session_id();
        $visitor_token = $my_functions->md5_generator($cur_session);
        $data_insert = $db_query->insert_log_visitor($cur_session,$cur_time);

        //set userdata 
        Session::set_userdata('role',"visitor");
        Session::set_userdata('visit',$visitor_token);





    }
  

  //if not got
  //add visitor log and set userdata - visitor

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