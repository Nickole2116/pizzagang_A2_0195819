<?php
    //require_once("../config/PDOConn.php");


    class Model
    {
            /* Properties */
        private $conn;

        /* Get database access */
        public function __construct( $pdo) {
            $this->conn = $pdo;
            //require_once("../libraries/my_functions.php");

        }

        /* List all users */
        public function getUsers() {
            return $this->conn->query("SELECT * FROM member");
        }

        /* List all users */
        public function getMembers() {
            return $this->conn->query("SELECT * FROM admin");
        }

        public function get_discount_listings()
        {

        }

        public function get_type_listings($types)
        {
            /*
            GET ALL TYPE LISTINGS
            */
            $data = $this->conn->prepare("SELECT product.product_id , product.product_name , product.product_description , product.product_price , product_category.category_name FROM `product_category` JOIN product ON product_category.category_id = product.category_id WHERE product_category.category_id = :types ");
            $data->bindParam(":types",$types);
            
            $data->execute();
            $res = $data->fetchAll();
            if(empty($res))
            {
                return null;
            }else{
                //$ress = array("Products"=>$res);
                //$pretty = json_encode($ress , JSON_PRETTY_PRINT);
                
                return $res;

            }

        }

        public function admin_login($username,$password,$session,$now_time)
        {
            $data = $this->conn->prepare('SELECT * FROM admin WHERE username = :un AND password_salt = :passwords ;');
            $data->bindParam(':un', $username);
            $data->bindParam(':passwords', $password);
            $data->execute();

            $count = $data->rowCount();
            if($count > 0)
            {
                //validated 
                $updated_status_account_time = self::update_log_visitor($now_time, 3, 2, $session, " ");
                return "VALID";

            }else
            {
                //failed
                return "INVALID";

            }
            
            

        }

        public function insert_log_visitor($session,$time)
        {
            try{
                $is_member = 0;
                $statuss = 2;
                $data = $this->conn->prepare("INSERT INTO visitor_log VALUES (null,:sessionid,:is_mem,null,:log_status,:modified_time)");
                $data->bindParam(':sessionid', $session);
                $data->bindParam(':is_mem', $is_member);
                $data->bindParam(':log_status', $statuss);
                $data->bindParam(':modified_time', $time);
                $data->execute();

                return "success";

            }catch(Exception $e)
            {
                $data->rollback();
                echo "Commit Failed : " . $e->getMessage();

            }
        
        }

        public function check_visitor_log($session)
        {
            $data = $this->conn->prepare("SELECT * FROM visitor_log WHERE session_ids = :session_ids");
            $data->bindParam(':session_ids', $session);
            $data->execute();

            $count = $data->rowCount();

            if($count > 0)
            {
                return "yes";

            }else 
            {
                return "no";
            }
            
            
        
        }

        public function update_log_visitor($curent_date, $status, $roles, $session, $token=null)
        {
            try{

                if(!empty($token))
                {
                    $data = $this->conn->prepare("UPDATE visitor_log SET modified_log_time = :modified_time , log_status = :log_status , is_members = :roles , acc_no = :tokens WHERE session_ids = :sessionid ;");
                    $data->bindParam(':sessionid', $session);
                    $data->bindParam(':log_status', $status);
                    $data->bindParam(':roles', $roles);
                    $data->bindParam(':tokens', $token);
                    $data->bindParam(':modified_time', $curent_date);
                    $data->execute();
                }else
                {
                    $data = $this->conn->prepare("UPDATE visitor_log SET modified_log_time = :modified_time , log_status = :log_status , is_members = :roles WHERE session_ids = :sessionid ;");
                    $data->bindParam(':sessionid', $session);
                    $data->bindParam(':log_status', $status);
                    $data->bindParam(':roles', $roles);
                    $data->bindParam(':modified_time', $curent_date);
                    $data->execute();

                }
                
                return "updated";

            }catch(Exception $e)
            {
                //echo "Commit Failed : " . $e->getMessage();
                return $e->getMessage();


            }


        }


        public function member_login($email,$password,$session, $now_time, $acc)
        {
            /**START Login 
             * ------------
             * 1. get post variables 
             * 2. validate email and password 
             * 3. login validate 
             * 4. when success, set userdata to new var - token and roles (eg. md5(token))
             * 5. updates the sessions id, token from member
             * 6. updated the is_member into 1 , acc_no = acc_no, log_status into 1 , modified_log time
            */
            require_once("../libraries/my_functions.php");

            $my_functions = new My_functions();

            $count = self::check_matched_member($email,$password);
            $token_new = $my_functions->md5_generator($acc);
            $roles = "member";
            if($count > 0)
            {
                /**successful on login */
                $updated_session_time = self::update_session_member($session, $now_time, $email, $acc, $token_new);
                $updated_status_account_time = self::update_log_visitor($now_time, 1, 1 , $session, $acc);

                if($updated_status_account_time == "updated" && $updated_session_time == "updated")
                {
                    return array("Response"=>"OK","Token"=>$token_new);
                    
                }else{
                    return array("Response"=>"Failed","Token"=>$token_new);
                }


            }else
            {
                return array("Response"=>"Failed","Token"=>$token_new);;


            }
    



        }

        public function check_existed_email($email)
        {
            $data = $this->conn->prepare("SELECT * FROM member WHERE email_address = :email");
            $data->bindParam(':email', $email);
            $data->execute();

            $count = $data->rowCount();
            
            return $count;

        }

        public function check_existed_phone($phone)
        {
            $data = $this->conn->prepare("SELECT * FROM member WHERE phonenumber = :phone");
            $data->bindParam(':phone', $phone);
            $data->execute();

            $count = $data->rowCount();
            
            return $count;

        }

        public function fetch_acc_no($email)
        {
            $data = $this->conn->prepare("SELECT * FROM member WHERE email_address = :email");
            $data->bindParam(':email', $email);
            $data->execute();
            $res = $data->fetch();

            return $res['acc_no'];
        // echo $res['acc_no'];

        }

        private function update_session_member($session, $now_time, $email, $acc_no, $token_new)
        {
            try{
                
                $data = $this->conn->prepare("UPDATE member SET token = :token , session_ids = :sessionid WHERE email_address = :email ;");
                $data->bindParam(':token', $token_new);
                $data->bindParam(':sessionid', $session);
                $data->bindParam(':email', $email);
                $data->execute();


                return "updated";

            }catch(Exception $e)
            {
                //echo "Commit Failed : " . $e->getMessage();
                return $e->getMessage();


            }


        }

        private function check_matched_member($email,$password)
        {
            $data = $this->conn->prepare('SELECT * FROM member WHERE email_address = :email AND password_salt = :passwords ;');
            $data->bindParam(':email', $email);
            $data->bindParam(':passwords', $password);
            $data->execute();
    
            $count = $data->rowCount();
            
            return $count;
    
        }

        public function member_register($session, $username, $password, $phone, $email)
        {
            /**
             * Check email and phone duplicated or not?
             * then check password strengthness
             * insert new into member table
             * update the visitor log
             * 
             */
            $my_functions = new My_functions();
            $token_new = "";


            $check_email = self::check_existed_email($email);
            $check_phone = self::check_existed_phone($phone);

            if($check_email > 0)
            {
                //DUPLICATE EMAIL
                $res = "DUPLICATE EMAIL";
            }else if($check_email == 0 && $check_phone == 0)
            {
                /** CAN DO SOMETHING REGIETER HERE  */
                //check password lengths
                $passlen = strlen($password);

                if($passlen < 8)
                {
                    $res = "INVALID PASSWORD LENGTH";
                }else{
                    /**VARIABLE NOT PROBLEM THEN */
                    $acc_num_ency = rand();
                    $token_new = $my_functions->md5_generator($acc_num_ency);

                    try{

                        $com = $this->conn->prepare('INSERT INTO member VALUES(NULL, :username, :passwords, :sessionids , :token , "" , :phone , :email, :acc);');
                        $com->bindParam(':username',$username);
                        $com->bindParam(':passwords',$password);
                        $com->bindParam(':sessionids',$session);
                        $com->bindParam(':token',$token_new);
                        $com->bindParam(':phone',$phone);
                        $com->bindParam(':email',$email);
                        $com->bindParam(':acc',$acc_num_ency);
            
                        $com->execute();
                        

                        //update log visitor 
                        $now_time = $my_functions->now();
                        $updated_status_account_time = self::update_log_visitor($now_time, 1, 1 , $session, $acc_num_ency);
                        $res = "MEMBER CREATED";



                    }catch(Exception $e)
                    {
                        return $e->getMessage();

                    }
                }
                

            }
            else if($check_phone > 0)
            {
                $res = "DUPLICATE PHONE";

            }else{
                //UNKNOWN ERROR OCCUR
                $res = "UNKNOWN";
            }

            return array("responses"=>$res,"Created"=>$my_functions->now(),"token"=>$token_new);


        }

        //member
        public function add_cart($ecc_no_en,$session_id,$list,$now_date)
        {
            $count_cart = self::get_count_cart($ecc_no_en);
            //if no cart 
            if($count_cart == 0)
            {
                try{
                    $com = $this->conn->prepare('INSERT INTO wishlist VALUES(NULL, :acc, :ses, :pids , :timess );');
                    $com->bindParam(':acc',$ecc_no_en);
                    $com->bindParam(':ses',$session_id);
                    $com->bindParam(':pids',$list);
                    $com->bindParam(':timess',$now_date);
                    $com->execute();
        
                    return " new inserted";
        
                }catch(Exception $e)
                {
                    return $e->getMessage();
        
                }

            }else
            {
                //got record 
                //get cart detail by sessions , lists
                //array push old and new
                //update lists inton function
                $lists = self::get_cart_by_accno($ecc_no_en);

                if(!empty($lists))
                {
                    $array_list = explode(',',$lists);
                    $new_obj = $list;
                    $new = array_push($array_list, $new_obj);
                    $listed = implode(',',$array_list);

                    //upodate cart
                    $res = self::update_cart($listed,$ecc_no_en,$now_date);
                    return $res;



                }else{

                    //cart no items
                    $new_obj = $list;
                    //$new = array_push($array_list, $new_obj);
                    //$listed = implode(',',$array_list);

                    //upodate cart
                    $res = self::update_cart($new_obj,$ecc_no_en,$now_date);
                    return $res;

                    
                }


            }

        }

        //visitor
        public function add_cart_visitor($session_id,$list,$now_date)
        {
            $count_cart = self::get_count_cart_session($session_id);
            //if no cart 
            if($count_cart == 0)
            {
                $null_obj = null;
                //add new cart
                try{
                    $com = $this->conn->prepare('INSERT INTO wishlist VALUES(NULL, :acc, :ses, :pids , :timess );');
                    $com->bindParam(':acc',$null_obj);
                    $com->bindParam(':ses',$session_id);
                    $com->bindParam(':pids',$list);
                    $com->bindParam(':timess',$now_date);
                    $com->execute();
        
                    return "new inserted";
        
                }catch(Exception $e)
                {
                    return $e->getMessage();
        
                }

            }else
            {
                //got record 
                //get cart detail by sessions , lists
                //array push old and new
                //update lists inton function
                $lists = self::get_cart_by_session($session_id);
                if(!empty($lists))
                {
                    $array_list = explode(',',$lists);
                    $new_obj = $list;
                    $new = array_push($array_list, $new_obj);
                    $listed = implode(',',$array_list);

                    //upodate cart
                    $res = self::update_cart_visitor($listed,$session_id,$now_date);
                    return $res;



                }else{
                    //emptty list
                    //$array_list = explode(',',$lists);
                    $new_obj = $list;
                    //$new = array_push($array_list, $new_obj);
                    //$listed = implode(',',$array_list);

                    //upodate cart
                    $res = self::update_cart_visitor($new_obj,$session_id,$now_date);
                    return $res;
                }
                


            }

        }

        public function get_cart_by_accno($accno_encrypted)
        {
            $data = $this->conn->prepare("SELECT package_ids FROM wishlist WHERE acc_no_encrypted = :acc");
            $data->bindParam(':acc',$accno_encrypted);
            $data->execute();
            $res = $data->fetch();

            return $res['package_ids'];

        }

        public function get_cart_by_session($session)
        {
            $data = $this->conn->prepare("SELECT package_ids FROM wishlist WHERE `session_id` = :sess AND `acc_no_encrypted` IS NULL");
            $data->bindParam(':sess',$session);
            $data->execute();
            $res = $data->fetch();

            return $res['package_ids'];

        }

        public function get_count_cart($ecc_no_en)
        {
            /**
             * Checking the cart existed or not
             */
            $data = $this->conn->prepare("SELECT * FROM wishlist WHERE acc_no_encrypted = :acc");
            $data->bindParam(':acc', $ecc_no_en);
            $data->execute();

            $count = $data->rowCount();
            
            return $count;

        }

        public function get_count_cart_session($session)
        {
            /**
             * Checking the cart existed or not
             */
            $data = $this->conn->prepare("SELECT * FROM wishlist WHERE `session_id` = :sess AND `acc_no_encrypted` IS NULL");
            $data->bindParam(':sess', $session);
            $data->execute();

            $count = $data->rowCount();
            
            return $count;

        }

        public function update_cart($list,$ecc_no_en,$nows)
        {
            try{
                
                $data = $this->conn->prepare("UPDATE wishlist SET package_ids = :list , updated_time = :upd_time  WHERE acc_no_encrypted = :ecc ;");
                $data->bindParam(':list', $list);
                $data->bindParam(':upd_time', $nows);
                $data->bindParam(':ecc', $ecc_no_en);
                $data->execute();


                return "updated";

            }catch(Exception $e)
            {
                //echo "Commit Failed : " . $e->getMessage();
                return $e->getMessage();


            }


        }

        public function update_cart_visitor($list,$session,$nows)
        {
            try{
                
                $data = $this->conn->prepare("UPDATE wishlist SET package_ids = :list , updated_time = :upd_time WHERE `session_id` = :sess AND `acc_no_encrypted` IS NULL");
                $data->bindParam(':list', $list);
                $data->bindParam(':sess', $session);
                $data->bindParam(':upd_time', $nows);
                $data->execute();


                return "updated";

            }catch(Exception $e)
            {
                //echo "Commit Failed : " . $e->getMessage();
                return $e->getMessage();


            }


        }




        public function make_order()
        {
            //address, phone, promocode (optional) , name
            //lists


        }

        public function view_order()
        {

        }

        public function view_my_cart()
        {

        }

        public function reduce_discount_price()
        {

        }
    }



?>