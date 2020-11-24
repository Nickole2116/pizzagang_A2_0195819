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
                $conn = PDOConnection::getConnection();
                $db_query = new Model($conn);


                

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
                Session::set_userdata("role","member");
                Session::set_userdata("token",$tokens);
                

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
                        Session::set_userdata("role","member");
                        Session::set_userdata("token",$token);
                                
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
                    Session::set_userdata("role","admin");
                    Session::set_userdata("user",$username);
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
        $ecc_acc = Session::userdata("token");

        $list = $_POST['new_obj'];

        $data = $db_query->add_cart($ecc_acc,$session,$list,$cur_time);
        //print_r($data);

        $result = array("responses"=>$data,"actioned"=>$cur_time, "token"=>$ecc_acc);

        echo json_encode($result);


    }

    public function delete_cart_item()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $ecc_acc = Session::userdata("token");
        $roles = Session::userdata("role");
        $visits = Session::userdata("visit");


        //post var
        $delete_item = $_POST['pid_delete'];

        echo $db_query->delete_one_cart_items($delete_item,$roles,$ecc_acc,$cur_time);

    }

    public function delete_cart_item_visitor()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $roles = Session::userdata("role");
        $visits = Session::userdata("visit");


        $delete_item = $_POST['pid_delete'];

        echo $db_query->delete_one_cart_items($delete_item,$roles,$session,$cur_time);


    }

    public function my_current_cart()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $ecc_acc = Session::userdata("token");
        $roles = Session::userdata("role");
        $visits = Session::userdata("visit");

        $data = $db_query->view_my_cart($ecc_acc);
        if(empty($data))
        {
            echo $data;

        }else{
            $data_ex = json_decode($data);
            $p_array = $data_ex->Products ;
            $count_array = $data_ex->Count ;
            $total = 0;
            

            //print_r($data[1]['product_name']);

            foreach($p_array as $row => $val)
            {
                foreach($count_array as $row => $qualit)
                {
                    if($row == $val->product_id)
                    {
                        //echo '<br>'.$val->product_name . '<br>Price : RM '. $val->product_price .' ( '. $qualit . ' rows left ) ';
                        $total_per = $val->product_price * $qualit ;
                        $total += $total_per ;

                        /** Create array to display json format */
                        $cur_data = array("Product Name"=>$val->product_name , 
                        "Quality"=>$qualit , 
                        "Product Price (Per)"=>$val->product_price,
                        "Total"=>$total);
                        echo json_encode($cur_data);
                    }
                }

            }
            $service_tax_count = $total * 0.06 ; 
            $total_amount = $service_tax_count + $total;

        }
        

    }

    public function my_visit_cart()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $roles = Session::userdata("role");
        $visits = Session::userdata("visit");

        $data = $db_query->view_my_cart_visitor($session);
        if(empty($data))
        {
            echo "No Result Found";

        }else{

            $data_ex = json_decode($data);
            $p_array = $data_ex->Products ;
            $count_array = $data_ex->Count ;
            $total = 0;
            

            //print_r($data[1]['product_name']);

            foreach($p_array as $row => $val)
            {
                foreach($count_array as $row => $qualit)
                {
                    if($row == $val->product_id)
                    {
                        //echo '<br>'.$val->product_name . '<br>Price : RM '. $val->product_price .' ( '. $qualit . ' rows left ) ';
                        $total_per = $val->product_price * $qualit ;
                        $total += $total_per ;

                        /** Create array to display json format */
                        $cur_data = array("Product Name"=>$val->product_name , 
                        "Quality"=>$qualit , 
                        "Product Price (Per)"=>$val->product_price,
                        "Total"=>$total);
                        echo json_encode($cur_data);
                    }
                }

            }
            $service_tax_count = $total * 0.06 ; 
            $total_amount = $service_tax_count + $total;



        }


    }

    public function order_confirmation_member()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $roles = Session::userdata("role");
        $visits = Session::userdata("visit");
        $en_token = Session::userdata("token");
        $promo_val = 0;

        //post data
        $_promocode = $_POST['promocode'];
        $_deliveryname = $_POST['deliveryname'];
        $_phone = $_POST['phone'];
        $_address = $_POST['desc'];

        if(empty($_deliveryname))
        {
            echo "Empty Delivery Name";

        }
        else if(empty($_phone))
        {
            echo "Empty Phone Number";

        }
        else if(empty($_address))
        {
            echo "Empty Address";

            
        }
        else if(empty($_promocode))
        {
            //original price purchases
            echo "empty promo code";

        }else
        {
            $found = $db_query->fetch_promo_code($_promocode);

            if(empty($found))
            {
                echo " promocode no found";
            }else 
            {
                //check the promotion code was actived or not 
                $begintime = $found['promotion_start'];
                $endtime = $found['promotion_end'];
                if($cur_time >= $begintime && $cur_time <= $endtime)
                {
                    //need check see the promo code have used before or not
                    $log_check = $db_query->check_log_promotion($en_token,$_promocode);

                    if($log_check > 0)
                    {
                        /** The Promo Code had been used before
                        *  Need Prompt up Error Message 
                        */
                        $promo_action = "None";
                        $promo_val = 0;
                        ECHO $promo_action;


                    }else 
                    {
                        //the promotion code was active in time
                        $rate = $found['promo_rate_type'] == "RM" || $found['promo_rate_type'] == "rm" ? 'RM'  : '%';
                        $promo_val = $found['rate_value'];
                        $promo_action = "(".$found['promotion_name']." Discount )Reduce " . $rate . $promo_val ;                
                    }

                }
                else
                {
                    //already expired
                    echo "The Promotion Code already Expired";
                    $promo_val = 0;
                    $promo_action = "NONE";


                }
            }

        }

        //fetch product item
        //list all cart 
        $data = $db_query->view_my_cart($en_token);
        if(empty($data))
        {
            echo $data;

        }else{
            $data_ex = json_decode($data);
            $p_array = $data_ex->Products ;
            $count_array = $data_ex->Count ;
            $total = 0;
            

            //print_r($data[1]['product_name']);

            foreach($p_array as $row => $val)
            {
                foreach($count_array as $row => $qualit)
                {
                    if($row == $val->product_id)
                    {
                        //echo '<br>'.$val->product_name . '<br>Price : RM '. $val->product_price .' ( '. $qualit . ' rows left ) ';
                        $total_per = $val->product_price * $qualit ;
                        $total += $total_per ;

                        /** Create array to display json format */
                        $cur_data = array("Product Name"=>$val->product_name , 
                        "Quality"=>$qualit , 
                        "Product Price (Per)"=>$val->product_price,
                        "Total"=>$total);
                        echo json_encode($cur_data);
                    }
                }

            }
            $service_tax_count = $total * 0.06 ; 
            $total_amount = $service_tax_count + $total;
            $final_total_amount = $total_amount - $promo_val;
            echo $service_tax_count;
            echo "rm " . $final_total_amount ;

        }




    }

    //new
    public function order_confirmation_visitor()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $roles = Session::userdata("role");
        $visits = Session::userdata("visit");
        $en_token = Session::userdata("token");
        $en_session = $my_functions->md5_generator($session);
        $promo_val = 0;

        //post data
        $_promocode = $_POST['promocode'];
        $_deliveryname = $_POST['deliveryname'];
        $_phone = $_POST['phone'];
        $_email = $_POST['email'];
        $_address = $_POST['desc'];


        if(empty($_deliveryname))
        {
            echo "Empty Delivery Name";

        }
        else if(empty($_phone))
        {
            echo "Empty Phone Number";

        }
        else if(empty($_email))
        {
            echo "Empty Phone Number";

        }
        else if(empty($_address))
        {
            echo "Empty Address";

            
        }
        else if(empty($_promocode))
        {
            //original price purchases
            echo "empty";

        }else
        {
            $found = $db_query->fetch_promo_code($_promocode);

            if(empty($found))
            {
                echo " promocode no found";
            }else 
            {
                //check the promotion code was actived or not 
                $begintime = $found['promotion_start'];
                $endtime = $found['promotion_end'];
                if($cur_time >= $begintime && $cur_time <= $endtime)
                {
                    //need check see the promo code have used before or not
                    $log_check = $db_query->check_log_promotion($en_session,$_promocode);

                    if($log_check > 0)
                    {
                        /** The Promo Code had been used before
                        *  Need Prompt up Error Message 
                        */
                        $promo_action = "None";
                        $promo_val = 0;
                        ECHO $promo_action;


                    }else 
                    {
                        //the promotion code was active in time
                        $rate = $found['promo_rate_type'] == "RM" || $found['promo_rate_type'] == "rm" ? 'RM'  : '%';
                        $promo_val = $found['rate_value'];
                        $promo_action = "(".$found['promotion_name']." Discount )Reduce " . $rate . $promo_val ;                
                    }

                }
                else
                {
                    //already expired
                    echo "The Promotion Code already Expired";
                    $promo_val = 0;
                    $promo_action = "NONE";
                }
            }

        }

        $data = $db_query->view_my_cart_visitor($session);
        if(empty($data))
        {
            echo "No Result Found";

        }else{

            $data_ex = json_decode($data);
            $p_array = $data_ex->Products ;
            $count_array = $data_ex->Count ;
            $total = 0;
            

            //print_r($data[1]['product_name']);

            foreach($p_array as $row => $val)
            {
                foreach($count_array as $row => $qualit)
                {
                    if($row == $val->product_id)
                    {
                        //echo '<br>'.$val->product_name . '<br>Price : RM '. $val->product_price .' ( '. $qualit . ' rows left ) ';
                        $total_per = $val->product_price * $qualit ;
                        $total += $total_per ;

                        /** Create array to display json format */
                        $cur_data = array("Product Name"=>$val->product_name , 
                        "Quality"=>$qualit , 
                        "Product Price (Per)"=>$val->product_price,
                        "Total"=>$total);
                        echo json_encode($cur_data);
                    }
                }

            }
            $service_tax_count = $total * 0.06 ; 
            $total_amount = $service_tax_count + $total;
            $final_total_amount = $total_amount - $promo_val;
            echo number_format($service_tax_count,2);
            echo "rm " . number_format($final_total_amount,2) ;

            //echo json_encode(array("Response"=>$cur_data));



        }

    }


    public function submit_order_member()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $roles = Session::userdata("role");
        $visits = Session::userdata("visit");
        $en_token = Session::userdata("token");
        $promo_val = 0;
        $_empty_cart = "";

        //post data
        $_promocode = $_POST['promocode'];
        $_deliveryname = $_POST['deliveryname'];
        $_phone = $_POST['phone'];
        $_email = $_POST['email'];
        $_address = $_POST['address'];
        $_desc = $_POST['desc'];
        $_total = $_POST['totalAmount'];
        $_tax = $_POST['tax'];
        $_promoval = $_POST['promoval']; //if none then no add into promo log

        //get member - cart listings no
        $data = $db_query->order_submit_member($_deliveryname, $_email, $_phone, $_address, $_total, $_tax, $_desc,$en_token);
        if($data == "insert")
        {
            //create order trx
            $data = $db_query->get_detail_by_token($en_token);
            $get_latest = $db_query->get_latest_inserted_row_order("orders",$en_token);
            $memberid = $data['memberid'];
            $orderid = $get_latest['order_id'];

            //add trx and update order tables
            $ordertrx_in = $db_query->create_order_trx_member($memberid,$session,$orderid);
            $upt = $db_query->update_carts($_empty_cart,$en_token);

            /**
             * --------------------------------------------------------------------------
             * CHECK PROMO CODE USED OR NOT / SHOW TRACKINGS
             * --------------------------------------------------------------------------
             * 
             */
             if($_promoval = "none")
             {
                //no used any promo code in its transactions
             }else
             {
                //used
                $inset = $db_query->insert_promo_log($orderid,$_promocode,$en_token);
             }

            $order_trx = $db_query->get_order_details($en_token);
            $de_order_trx = json_decode($order_trx);
            $data_order = $de_order_trx->Responses;

            foreach($data_order as $sub => $val)
            {
                if($val->status == 1)
                {
                    $statuses = "New Received";
                }elseif($val->status == 2)
                {
                    $statuses = "Pending";

                }elseif($val->status == 3)
                {
                    $statuses = "Delivering";

                }elseif($val->status == 4)
                {
                    $statuses = "Completed";

                }
                                    
                //Return json format
                $data_res = array("Tracking_No"=>$val->tracking_number,
                                    "Delivery_name"=>$val->delivery_name,
                                    "Amount"=>$val->amounts,
                                    "Email"=>$val->email_address,
                                    "Created"=>$val->order_created_date,
                                    "Status"=>$statuses);
                echo json_encode($data_res);
            }







        }else{
            $data_res = array("Tracking_No"=>"none",
                                "Delivery_name"=>"none",
                                "Amount"=>"none",
                                "Email"=>"none",
                                "Created"=>"none",
                                "Status"=>"none");
            echo json_encode($data_res);

        }



        
    }


    public function submit_order_visitor()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $roles = Session::userdata("role");
        $visits = Session::userdata("visit");
        $en_token = Session::userdata("token");
        $en_session = $my_functions->md5_generator($session);
        $promo_val = 0;
        $_empty_cart = "";
        $no_member = null;

        //post data
        $_promocode = $_POST['promocode'];
        $_deliveryname = $_POST['deliveryname'];
        $_phone = $_POST['phone'];
        $_email = $_POST['email'];
        $_address = $_POST['address'];
        $_desc = $_POST['desc'];
        $_total = $_POST['totalAmount'];
        $_tax = $_POST['tax'];
        $_promoval = $_POST['promoval']; //if none then no add into promo log

        //get member - cart listings no
        $data = $db_query->order_submit_member($_deliveryname, $_email, $_phone, $_address, $_total, $_tax, $_desc,$en_session);
        if($data == "insert")
        {
            //create order trx
            $get_latest = $db_query->get_latest_inserted_row_order("orders",$en_session);
            $orderid = $get_latest['order_id'];

            //add trx and update order tables
            $ordertrx_in = $db_query->create_order_trx_member($no_member,$session,$orderid);
            $upt = $db_query->update_carts($_empty_cart,$en_session);

            /**
             * --------------------------------------------------------------------------
             * CHECK PROMO CODE USED OR NOT / SHOW TRACKINGS
             * --------------------------------------------------------------------------
             * 
             */
             if($_promoval = "none")
             {
                //no used any promo code in its transactions
             }else
             {
                //used
                $inset = $db_query->insert_promo_log($orderid,$_promocode,$en_session);
             }

            $order_trx = $db_query->get_order_details($en_session);
            $de_order_trx = json_decode($order_trx);
            $data_order = $de_order_trx->Responses;

            foreach($data_order as $sub => $val)
            {
                if($val->status == 1)
                {
                    $statuses = "New Received";
                }elseif($val->status == 2)
                {
                    $statuses = "Pending";

                }elseif($val->status == 3)
                {
                    $statuses = "Delivering";

                }elseif($val->status == 4)
                {
                    $statuses = "Completed";

                }
                                    
                //Return json format
                $data_res = array("Tracking_No"=>$val->tracking_number,
                                    "Delivery_name"=>$val->delivery_name,
                                    "Amount"=>$val->amounts,
                                    "Email"=>$val->email_address,
                                    "Created"=>$val->order_created_date,
                                    "Status"=>$statuses);
                echo json_encode($data_res);
            }

        }else{
            $data_res = array("Tracking_No"=>"none",
                                "Delivery_name"=>"none",
                                "Amount"=>"none",
                                "Email"=>"none",
                                "Created"=>"none",
                                "Status"=>"none");
            echo json_encode($data_res);

        }



        
    }

    public function view_my_orders_visitor()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $roles = Session::userdata("role");
        $visits = Session::userdata("visit");
        $en_token = Session::userdata("token");
        $en_session = $my_functions->md5_generator($session);

        $order_trx = $db_query->view_order($en_session);
        print_r($order_trx);

    }

    public function view_my_orders_member()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $roles = Session::userdata("role");
        $visits = Session::userdata("visit");
        $en_token = Session::userdata("token");
        $en_session = $my_functions->md5_generator($session);

        $order_trx = $db_query->view_order($en_token);
        print_r($order_trx);

    }

    public function updates_previous_orders_member()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $roles = Session::userdata("role");
        $visits = Session::userdata("visit");
        $en_token = Session::userdata("token");
        $en_session = $my_functions->md5_generator($session);

        $data = $db_query->get_last_cart_list($en_token);
        if(!empty($data))
        {
            //echo $data;
            $udt = $db_query->update_cart($data,$en_token,$cur_time);

        }else 
        {
            
        }



    }

    public function updates_previous_orders_visitor()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();
        $cur_time = $my_functions->now();
        $session = Session::get_session_id();
        $roles = Session::userdata("role");
        $visits = Session::userdata("visit");
        $en_token = Session::userdata("token");
        $en_session = $my_functions->md5_generator($session);

        $data = $db_query->get_last_cart_list($en_session);
        if(!empty($data))
        {
            //update the lists
            //echo $data;
            $udt = $db_query->update_cart_visitor($data,$session,$cur_time);

        }else 
        {
            
        }

    }
    

    

}


?>