<?php
//start a session
//session_start();

class Session
{
    private $session;

    public function __construct()
    {
        session_start();

    }

    public static function get_session_id()
    {
        //$this->$session = session_id();
        return session_id();
        
    }

    public static function set_userdata($session_var,$value)
    {
        $_SESSION[$session_var] = $value;

    }

    public static function userdata($session_var)
    {
        if(isset($_SESSION[$session_var]))
        {
            return $_SESSION[$session_var];

        }
        else 
        {
            return null;
        }
        
    }

    public static function unset_userdata($session_var)
    {
        if(isset($_SESSION[$session_var]))
        {
           unset($_SESSION[$session_var]);

        }
        else 
        {
            return null;
        }

    }
    

}







?>