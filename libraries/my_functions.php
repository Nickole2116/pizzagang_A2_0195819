<?php

    class My_functions
    {
        public function __construct(){ 
        }
        public function now()
        {
            $date = new DateTime("now", new DateTimeZone('Asia/Kuala_Lumpur'));
            return $date->format('Y-m-d H:i:s');

        }

        public function md5_generator($sand)
        {
            return md5($sand);
        }

        public function passcode_generate($sand)
        {
            return 0;
        }

        public static function checkemail($str)
        {
            return (!preg_match("/^([a-z0-9\+_\-]+)(.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE :TRUE;
        }
       

    }






?>