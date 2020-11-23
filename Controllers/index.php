<?php
require_once("../Models/CRUD.php");
require_once("../libraries/my_routes.php");
require_once("../libraries/my_constant.php");

class Controller{    
    private $model;  
    private $_data;
      
    public function __construct(){ 

        /**
         * --------------------------------------------------------------------
         * Check Action Variables 
         * --------------------------------------------------------------------
         * THE FUNCTION from MY_ROUTES.php will be validate the functions/methods
         * are existing or not. IF not existing the function and no match with 
         * ACTION variables , if will be returned 0 to detect the invalidation of
         * methods / functions name on its controllers. 
         */
        $check = __check_functions($this,$_GET['action']);
       
        if (isset($_GET['action']) && !empty($_GET['action'])) { 
            if($check == 0)
            {
                //ERROR OCCURED - INVALID FUNCTION ACTIONS
                echo sprintf("INVALID FUNCTION DETECTIONS FROM %s()",$_GET['action']);   
            }else if($check == 1)
            {
                //LOAD THE CONTROLLER ACTIONS
                $this->{$_GET['action']}();
            }
            
        }
        else{
            //CASES OF BLANK ACTION VARIABLES
            echo "NO ACTION TAKEN";
        }
    }

    

    public function home() {        
        //$this->model->string = "Updated Data, thanks to MVC and PHP!";  
        ECHO "DONE";
        echo $_GET['action'];
        echo __FUNCTION__ ;
    }
    public function register_member()
    {
        //echo load("member/home.html",array("husband"=>"allan ng","2"=>"nickole tan"));
        
        //print_r(array("name"=>"allan ng","test"=>"nickole tan"));
        //http_response_code(200);
        echo json_encode(array("husband"=>"allan ng","2"=>"nickole tan"));
    }
}


?>