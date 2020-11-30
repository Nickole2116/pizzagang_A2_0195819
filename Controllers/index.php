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

    public function login_members()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $my_functions = new My_functions();
        $session_loads = new Session();

        if(empty($_POST['email_members']))
        {
            //when blank email inputs 
            $sendback = array("Response"=>"Empty Email",
                                            "Messages"=>"Invalid Login Credential"
                                            );
            

        }else if(empty($_POST['password_members']))
        {
            //when blank password inouts 
            $sendback = array("Response"=>"Empty password",
                                            "Messages"=>"Invalid Login Credential"
                                            );

        }else
        {
            $email_member = $_POST['email_members'];
            $password_member = $_POST['password_members'];
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
                        //echo $token ;
                        Session::set_userdata("role","member");
                        Session::set_userdata("token",$token);
                                
                                        //RETURN THE STATUS MESSAGE
                        $sendback = array("Response"=>$token,
                                          "Messages"=>"Success for User Login");



                    }else{
                        /** IF THE USER CREDENTIAL WAS NOT MATCHED */
                        $sendback = array("Response"=>"Failed to Fetch Token. Login Failed",
                                            "Messages"=>"Invalid Login Credential"
                                            );


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

    public function get_all_available_pizza_limit()
    {
        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $data = $db_query->get_discount_listings_limit_5();

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

    public function get_all_prod()
    {

        $conn = PDOConnection::getConnection();
        $db_query = new Model($conn);
        $data = $db_query->get_all_products();

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

        $result = $db_query->delete_one_cart_items($delete_item,"visitor",$session,$cur_time);

        if($result != "updated")
        {
            $res = array(
                "Response"=>"No Result Found"
                
            );


        }else
        {
            $res = array(
                "Response"=>$result
                
            );
        }

        echo json_encode($res);


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
        if($data == "empty")
        {

        }else{

            $data_ex = json_decode($data);
            $p_array = $data_ex->Products ;
            $count_array = $data_ex->Count ;
            $total = 0;
            $return_array = array();
            

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
                        $cur_data = array("Product_id"=>$val->product_id,
                        "Product_name"=>$val->product_name , 
                        "Product_desc"=>$val->product_description ,
                        "Product_image"=>$val->product_attachs ,
                        "Quality"=>$qualit , 
                        "Product_price_per"=>$val->product_price,
                        "Total"=>$total);
                        array_push($return_array,$cur_data);
                        

                        
                    }
                }

                

            }
            echo json_encode($return_array);
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
                $status_promo =  "promocode no found";
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
                        $cur_data = array("Product_Name"=>$val->product_name , 
                        "Quality"=>$qualit , 
                        "Product_Price_Per"=>$val->product_price,
                        "Total"=>$total,
                    );
                        //echo json_encode($cur_data);
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
        $return_array = array();

        //post data
        $_promocode = $_POST['promocode'];
        $_deliveryname = $_POST['deliveryname'];
        $_phone = $_POST['phone'];
        $_email = $_POST['email'];
        $_address = $_POST['desc'];


        if(empty($_deliveryname))
        {
            $return_array = array("responses"=> "Empty Delivery Name");

        }
        else if(empty($_phone))
        {
            $return_array = array("responses"=> "Empty Phone number");

        }
        else if(empty($_email))
        {
            $return_array = array("responses"=> "Empty email address");


        }
        else if(empty($_address))
        {
            $return_array = array("responses"=> "Empty address");


            
        }
        else if(empty($_promocode))
        {
            //original price purchases
            $return_array = array("responses"=> "Empty Promotion Code");


        }else
        {
            $found = $db_query->fetch_promo_code($_promocode);

            if(empty($found))
            {
                $status_promo =  "promocode no found";
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
                        $status_promo = $promo_action;


                    }else 
                    {
                        //the promotion code was active in time
                        $rate = $found['promo_rate_type'] == "RM" || $found['promo_rate_type'] == "rm" ? 'RM'  : '%';
                        $promo_val = $found['rate_value'];
                        $promo_action = "(".$found['promotion_name']." Discount )Reduce " . $rate . $promo_val ; 
                        $status_promo = $promo_action;               
                    }

                }
                else
                {
                    //already expired
                    $status_promo =  "The Promotion Code already Expired";
                    $promo_val = 0;
                    $promo_action = "NONE";
                }
            }

        }

        $data = $db_query->view_my_cart_visitor($session);
        if(empty($data))
        {
            $return_array = array("responses"=> "No Result Found");


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
                        $cur_data = array("Product_Name"=>$val->product_name , 
                        "Quality"=>$qualit , 
                        "Product_Price_Per"=>$val->product_price,
                        "Total"=>$total,
                        "status_promo"=>$status_promo,
                        "reduce"=>$promo_val);

                        array_push($return_array,$cur_data);

                        
                    }
                }

            }


            $service_tax_count = $total * 0.06 ; 
            $total_amount = $service_tax_count + $total;
            $final_total_amount = $total_amount - $promo_val;
            //echo number_format($service_tax_count,2);
            //echo "rm " . number_format($final_total_amount,2) ;

            //echo json_encode(array("Response"=>$cur_data));



        }

        echo json_encode($return_array);


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


        //get member - cart listings no - visitor site
        $data = $db_query->order_submit_visitor($_deliveryname, $_email, $_phone, $_address, $_total, $_tax, $_desc,$en_session,$session);
        if($data == "insert")
        {
            //create order trx
            $get_latest = $db_query->get_latest_inserted_row_order("orders",$en_session);
            $orderid = $get_latest['order_id'];

            //add trx and update order tables
            $ordertrx_in = $db_query->create_order_trx_member($no_member,$session,$orderid);
            $upt = $db_query->update_cart_visitor($_empty_cart,$session,$cur_time);

            /**
             * --------------------------------------------------------------------------
             * CHECK PROMO CODE USED OR NOT / SHOW TRACKINGS
             * --------------------------------------------------------------------------
             * 
             */
             if($_promoval == "none")
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
        $_empty = array();

        $order_trx = $db_query->view_order($en_session);
        if(!empty($order_trx))
        {
            echo json_encode($order_trx);

        }else
        {
            $_empty = array("response"=>"No Result Found");
            echo json_encode($_empty);
        }
        

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

    public function get_count_cart_visitor()
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

        $list = $db_query->get_cart_by_session($session);

        if(empty($list))
        {
            $count = 0;
        }else{
            $array = explode(",",$list);
            $count = count($array);

        }

        

        $return = array("counts"=>$count);
        echo json_encode($return);

    }

    public function view_orders_details()
    {
        //get GET variables 
        $ref_id = $_GET['id'];

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

        if(empty($ref_id))
        {
            $return = array("response"=>"Record Not Found");
            
        }else 
        {
            //get variable existed 
            //check 
            $return = $db_query->get_order_details_by_ref($ref_id);
            $p_array = explode(",",$return[0]['order_packages']);
            $count_array = array_count_values($p_array);
            //print_r($count_array);
            //print_r($return);
            $each_productsc = array();
            $format = array();

            foreach($count_array as $count => $value)
            {

                //echo $count;
                array_push($each_productsc,$count);
                

            }
            //print_r($each_productsc);
            $emplode = implode(',',$each_productsc);

            $product_details = $db_query->get_listings_by_array($emplode);
            //echo print_r($product_details);

            foreach($count_array as $count => $value)
            {
                foreach($product_details as $row => $element )
                {
                    //echo $element['product_id'];
                    if($count == $element['product_id'])
                    {
                        $merge = array("product_id"=>$element['product_id'],
                                        "product_attachs"=>$element['product_attachs'],
                                        "product_name"=>$element['product_name'],
                                        "product_description"=>$element['product_description'],
                                        "product_price"=>$element['product_price'],
                                        "category_name"=>$element['category_name'],
                                        "quality"=>$value

                        );
                        array_push($format,$merge);
                    }
                }
            }

            $count_array = count($format);

            


            echo '<!DOCTYPE html>
            <html>
                <head>
                    <title>Orders | MEMBER PANEL</title>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                    <link rel="preconnect" href="https://fonts.gstatic.com"> 
                    <link href="https://fonts.googleapis.com/css2?family=Alatsi&family=Caveat:wght@700&display=swap" rel="stylesheet">
                    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet">
                    <link rel="stylesheet" href="http://localhost/Assignment2_pizzagang/inc/css/bootstrap.min.css">
                    <script src="http://localhost/Assignment2_pizzagang/inc/js/bootstrap.min.js" ></script>
                    <meta http-equiv="Content-Type" content="text/html" charset="utf=8">
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                </head>
                <body>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12 bg-dark" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                                <div style="text-align: center;padding: 15px;color:whitesmoke;font-weight: bolder;">
                                    <div style="float: left;">
                                        <a href="../"><button style="background:none;border: none;margin-top: 5px;"><svg width="30px" height="30px" viewBox="0 0 20 20" class="bi bi-arrow-left" fill="white" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                                      </svg></button></a>
                                    </div>
                                    <h3>My Orders</h3>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-lg-3" ></div>
                            <div class="col-12 col-sm-12 col-lg-6" >
                                <div style="text-align: center;padding: 15px;color:black;font-weight: bolder;">
                                    
                                    <br>
                                     <div style="border-radius:15px;background:white;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);padding:5px">
                                     <h5>'.$return[0]['tracking_number'].'<h5>
                                     <div style="border-radius:15px;background: gold;padding: 10px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.15), 0 6px 20px 0 rgba(231, 231, 231, 0.14);">'.
                                        '<span><svg width="1em" height="1em" viewBox="0 0 18 18" class="bi bi-person-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'.
                                        '<path d="M13.468 12.37C12.758 11.226 11.195 10 8 10s-4.757 1.225-5.468 2.37A6.987 6.987 0 0 0 8 15a6.987 6.987 0 0 0 5.468-2.63z"/>'.
                                        '<path fill-rule="evenodd" d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z"/>'.
                                        '</svg> &nbsp;'.$return[0]['delivery_name'].'</span><br><br><span style="padding-left: 10px;padding-right: 10px;font-size: 9px;">'.$return[0]['address'].'</span>'.
                                        '<br><br><small>'.$return[0]['email_address'].' | '.$return[0]['phone_number'].'</small><br><br><h2>'.$return[0]['status'].' </h2><br>

                                     </div>
                                     ';
                                     echo '
                                    
                                </div>
                                <br>
                                <h4>Total Amount</h4>
                                <span>RM '.$return[0]['amounts'].'</span>
                                <br><br>
                                <h4>Services Tax</h4>
                                <span>RM '.$return[0]['taxs'].'</span>
                                <br><br>
                                <div style="width:100%;height:2px;background:gold;"></div>
                                
                                <div class="container">
                                    <div class="row">
                                    ';
                                    for($i = 0 ; $i < $count_array; $i++)
                                    {
                                        echo '<div class="col-12 col-lg-3 col-xs-3 col-sm-4" style="padding:5px;">'.
                                                '<br><img src="http://localhost/Assignment2_pizzagang/Controllers/upload/'.$format[$i]['product_attachs'].'" width="100%" style="border-radius: 15px;">
                                                </div>
                                                <div class="col-12 col-lg-9 col-xs-9 col-sm-8">
                                                <br><br>
                                                <h5>'.$format[$i]['product_name'].' x '.$format[$i]['quality'].'</h5></div>';
                                    }
                                    
                    echo '  
                                        
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    </body>
                    </html>

            ';

        }

        


    }

    function upload_file()
    {
        if (($_FILES['my_file']['name']!="")){
            // Where the file is going to be stored
             $target_dir = "./upload/";
             $file = $_FILES['my_file']['name'];
             $path = pathinfo($file);
             $filename = $path['filename']."_"."LOAD";
             $ext = $path['extension'];
             $temp_name = $_FILES['my_file']['tmp_name'];
             $path_filename_ext = $target_dir.$filename.".".$ext;
             
            // Check if file already exists
            if (file_exists($path_filename_ext)) {
             echo "Sorry, file already exists.";
            }else{
             move_uploaded_file($temp_name,$path_filename_ext);
             echo "Congratulations! File Uploaded Successfully.";
             echo $path_filename_ext;
            }
        }
    }
    

    

}


?>