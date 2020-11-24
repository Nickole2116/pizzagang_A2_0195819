<?php
require_once("../Models/CRUD.php");
require_once("../libraries/my_routes.php");
require_once("../libraries/my_constant.php");
require_once("../libraries/my_functions.php");
require_once("../config/PDOConn.php");



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
                require_once("../libraries/my_functions.php");
                require_once("../config/PDOConn.php");
                require_once("../config/Session.php");


                

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
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_session = Session::get_session_id();

        if(empty($_POST['new_username']))
        {
            //blank username
            echo "empty username";

        }else if(empty($_POST['new_password']))
        {
            //blank password 
            echo "empty password";


        }else if(empty($_POST['new_email']) || empty($_POST['new_phone']))
        {
            //blank email or phone 
            echo "empty vals";

        }else
        {
            $username = $_POST['new_username'];
            $password = $_POST['new_password'];
            $email = $_POST['new_email'];
            $phone = $_POST['new_phone'];
            
            //can register , validate in models
            /**REGISTER HERE (FOR MEMBER) */
            $pass_salt = $password . "_M29SA";
            $pass_encrypt = $my_functions->md5_generator($pass_salt);

            $return = $db_query->member_register($cur_session, $username, $pass_encrypt, $phone, $email);
            echo json_encode($return);

            if($return["responses"] == "MEMBER CREATED")
            {
                $tokens = $return["token"];
                $session_loads->set_userdata("role","member");
                $session_loads->set_userdata("token",$tokens);
                

            }else
            {
                //error of insert
                //other repsonses 

            }
 

        }
    }

    public function login_member()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();

        if(empty($_POST['email_member']))
        {
            //when blank email inputs 
            echo "empty email";
            

        }else if(empty($_POST['password_member']))
        {
            //when blank password inouts 
            echo "empty pass";

        }else
        {
            $email_member = $_POST['email_member'];
            $password_member = $_POST['password_member'];
            if(filter_var($email_member, FILTER_VALIDATE_EMAIL))
            {
                if(!My_functions::checkemail($email_member)){

                    //wrong email format
                
                }else{

                    $salt_pass = $password_member . "_M29SA";
                    $cur_time = $my_functions->now();
                    $encryp_pass = $my_functions->md5_generator($salt_pass);
                    $acc_no = $db_query->fetch_acc_no($email_member);
                    $cur_session = Session::get_session_id();
                    $check_match = $db_query->member_login($email_member,$encryp_pass,$cur_session,$cur_time,$acc_no);

                    if($check_match["Response"] == "OK")
                    {
                        $token = $check_match["Token"];
                        echo $token ;
                        $session_loads->set_userdata("role","member");
                        $session_loads->set_userdata("token",$token);
                                
                                        //RETURN THE STATUS MESSAGE
                        $sendback = array("Response"=>$token,
                                          "Messages"=>"Success for User Login");



                    }else{
                        /** IF THE USER CREDENTIAL WAS NOT MATCHED */
                        $sendback = array("Response"=>"Failed to Fetch Token. Login Failed",
                                            "Messages"=>"Invalid Login Credential");


                    }

                    echo json_encode($sendback,JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT );
                }
            }else 
            {
                //wrong email format- filter var

            }
        }  
    }

    public function login_admin()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();

        
        if(isset($_POST['username_admin']) && isset($_POST['password_admin']))
        {
            if(empty($_POST['username_admin']))
            {
                $res = "BLANK USERNAME FIELDS";

            }else if(empty($_POST['password_admin']))
            {
                $res = "BLANK PASSWORD FIELDS";

            }else
            {
                $username = $_POST['username_admin'];
                $password = $_POST['password_admin'];

                $salt_password = $password . "_A29SA";
                $encryp_pass = $my_functions->md5_generator($salt_password);

                $result = $db_query->admin_login($username, $encryp_pass, $session, $cur_time);
                if($result == "VALID")
                {
                    //set userdata 
                    //update visitor log
                    $session_loads->set_userdata("role","admin");
                    $session_loads->set_userdata("user",$username);
                    $res = "SUCCESSFUL LOGIN CREDENTIAL";
        
        
                }else{
                    $res = "FAILED LOGIN CREDENTIAL";
                }

            }
            


        }else{
            $res = "BLANK VARIABLE SETS";

        }

        $result = array("responses"=>$res,"actioned"=>$cur_time);

        echo json_encode($result);

    }


    public function get_all_pizza()
    {

        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $data = $db_query->get_type_listings(1);

        if(!empty($data))
        {
            echo json_encode($data);
        }else
        {

            $my_functions = new My_functions();

            $data = array("Response"=>"No Result Found",
                          "Path"=>__FUNCTION__,
                          "Actioned"=>$my_functions->now());
            echo json_encode($data);

        }
    }

    public function get_all_beverages()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $data = $db_query->get_type_listings(4);

        if(!empty($data))
        {
            echo json_encode($data);
        }else
        {

            $my_functions = new My_functions();

            $data = array("Response"=>"No Result Found",
                          "Path"=>__FUNCTION__,
                          "Actioned"=>$my_functions->now());
            echo json_encode($data);

        }
    }

    public function get_all_salads()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $data = $db_query->get_type_listings(2);

        if(!empty($data))
        {
            echo json_encode($data);
        }else
        {

            $my_functions = new My_functions();

            $data = array("Response"=>"No Result Found",
                          "Path"=>__FUNCTION__,
                          "Actioned"=>$my_functions->now());
            echo json_encode($data);

        }
    }

    public function get_all_desserts()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $data = $db_query->get_type_listings(3);

        if(!empty($data))
        {
            echo json_encode($data);
        }else
        {

            $my_functions = new My_functions();

            $data = array("Response"=>"No Result Found",
                          "Path"=>__FUNCTION__,
                          "Actioned"=>$my_functions->now());
            echo json_encode($data);

        }
    }

    public function get_all_sides()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $data = $db_query->get_type_listings(5);

        if(!empty($data))
        {
            echo json_encode($data);
        }else
        {

            $my_functions = new My_functions();

            $data = array("Response"=>"No Result Found",
                          "Path"=>__FUNCTION__,
                          "Actioned"=>$my_functions->now());
            echo json_encode($data);

        }
    }

    public function add_to_cart()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();

        $list = $_POST['new_obj'];

        $data = $db_query->add_cart_visitor($session,$list,$cur_time);
        //print_r($data);

        $result = array("responses"=>$data,"actioned"=>$cur_time);

        echo json_encode($result);


    }

    public function add_to_cart_member()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $ecc_acc = $session_loads->userdata("token");

        $list = $_POST['new_obj'];

        $data = $db_query->add_cart($ecc_acc,$session,$list,$cur_time);
        //print_r($data);

        $result = array("responses"=>$data,"actioned"=>$cur_time, "token"=>$ecc_acc);

        echo json_encode($result);


    }
}


?>