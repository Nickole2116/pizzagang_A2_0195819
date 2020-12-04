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

        public function get_discount_listings_limit_5()
        {
            $data = $this->conn->prepare("SELECT * FROM `promotion` JOIN promotion_rate ON promotion.promotion_rate_id = promotion_rate.promo_rate_id ");            
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

        public function get_discount_listings_limit_visitor()
        {
            $data = $this->conn->prepare("SELECT * FROM `promotion` JOIN promotion_rate ON promotion.promotion_rate_id = promotion_rate.promo_rate_id WHERE promotion.require_roles IS NULL");            
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

        public function get_discount_listings_limit_member()
        {
            $data = $this->conn->prepare("SELECT * FROM `promotion` JOIN promotion_rate ON promotion.promotion_rate_id = promotion_rate.promo_rate_id WHERE promotion.require_roles = 'MEMBER' ;");            
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

        public function get_type_listings($types)
        {
            /*
            GET ALL TYPE LISTINGS
            */
            $data = $this->conn->prepare("SELECT product.product_id , product.product_name , product.product_description , product.product_attachs,  product.product_price , product_category.category_name FROM `product_category` JOIN product ON product_category.category_id = product.category_id WHERE product_category.category_id = :types ");
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

        public function get_user_details($token)
        {
            /*
            GET ALL TYPE LISTINGS
            */
            $data = $this->conn->prepare("SELECT * FROM `member` WHERE token = :tkn ");
            $data->bindParam(":tkn",$token);
            
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

        public function update_pizza_point($upt_point, $memberid)
        {
            try{
                
                $data = $this->conn->prepare("UPDATE `member` SET `member_pizza_point` = :val WHERE `member`.`memberid` = :mem ;");
                $data->bindParam(':val', $upt_point);
                $data->bindParam(':mem', $memberid);
                $data->execute();

                return "updated";

            }catch(Exception $e)
            {
                $data->rollback();
                echo "Commit Failed : " . $e->getMessage();

            }

        }

        public function get_all_products()
        {
            /*
            GET ALL TYPE LISTINGS
            */
            $data = $this->conn->prepare("SELECT product.product_id , product.product_name , product.product_description , product.product_attachs,  product.product_price , product_category.category_name FROM `product_category` JOIN product ON product_category.category_id = product.category_id ");            
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
                $ress = "DUPLICATE EMAIL";
                return array("responses"=>$ress,"Created"=>$my_functions->now(),"token"=>$token_new);

            }else if($check_email == 0 && $check_phone == 0)
            {
                /** CAN DO SOMETHING REGIETER HERE  */
                //check password lengths
                $passlen = strlen($password);

                if($passlen < 8)
                {
                    $limit = "INVALID PASSWORD LENGTH";
                    return array("responses"=>$limit,"Created"=>$my_functions->now(),"token"=>$token_new);

                }else{
                    /**VARIABLE NOT PROBLEM THEN */
                    $acc_num_ency = rand();
                    $token_new = $my_functions->md5_generator($acc_num_ency);

                    try{

                        $com = $this->conn->prepare('INSERT INTO member VALUES(NULL, :username, :passwords, :sessionids , :token , "" , :phone , :email, :acc, 0);');
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
                        return array("responses"=>$res,"Created"=>$my_functions->now(),"token"=>$token_new);




                    }catch(Exception $e)
                    {
                        return $e->getMessage();

                    }
                }
                

            }
            else if($check_phone > 0)
            {
                $duplicated = "DUPLICATE PHONE";
                return array("responses"=>$duplicated,"Created"=>$my_functions->now(),"token"=>$token_new);


            }else{
                //UNKNOWN ERROR OCCUR
                return array("responses"=>"UNKNOWN","Created"=>$my_functions->now(),"token"=>$token_new);

            }



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

        public function fetch_promo_code($promocode)
        {
            $data = $this->conn->prepare("SELECT * FROM `promotion` JOIN `promotion_rate` ON promotion.promotion_rate_id = promotion_rate.promo_rate_id WHERE promo_code = :cod");
            $data->bindParam(':cod', $promocode);
            $data->execute();

            $res = $data->fetch();
            return $res;

        }

        public function check_log_promotion($token_acc,$promocode)
        {
            //where acc_no
            $data = $this->conn->prepare("SELECT * FROM promotion_log WHERE promotion_code = :code AND token = :tkn ;");
            $data->bindParam(':code', $promocode);
            $data->bindParam(':tkn', $token_acc);
            $data->execute();

            $count = $data->rowCount();
            
            return $count;
        }

        public function fetch_promo_code_member($promocode)
        {
            $data = $this->conn->prepare("SELECT * FROM `promotion` JOIN `promotion_rate` ON promotion.promotion_rate_id = promotion_rate.promo_rate_id WHERE promotion.promo_code = :cod AND promotion.require_roles = 'MEMBER';");
            $data->bindParam(':cod', $promocode);
            $data->execute();

            $res = $data->fetch();
            return $res;
        }

        public function fetch_promo_code_visitor($promocode)
        {
            $data = $this->conn->prepare("SELECT * FROM `promotion` JOIN `promotion_rate` ON promotion.promotion_rate_id = promotion_rate.promo_rate_id WHERE promotion.promo_code = :cod AND promotion.require_roles IS NULL ;");
            $data->bindParam(':cod', $promocode);
            $data->execute();

            $res = $data->fetch();
            return $res;
        }

        public function view_order($token_acc)
        {
            //SELECT * FROM `product_category` JOIN product ON product_category.category_id = product.category_id
            $data = $this->conn->prepare("SELECT * FROM orders JOIN orders_trx ON orders.order_id = orders_trx.order_id WHERE orders.token = :tkn ");
            $data->bindParam(':tkn', $token_acc);

            $data->execute();
            $res = $data->fetchAll();

            return $res;

        }


        public function view_my_cart($acc_no_encry)
        {
            //get package lists
            //show package details
            $lists = self::get_cart_by_accno($acc_no_encry);
            $conv_array = explode(",",$lists);
            $count_each = array_count_values($conv_array);
            $product_ids_each = array();

            foreach($count_each as $product => $count)
            {
                //get each product id only (excluded counts)
                array_push($product_ids_each,$product);
            }

            $exploded_productid = implode(",",$product_ids_each);
            $url = urlencode($exploded_productid);
            $chg = str_replace('%2C','%27%2C%27',$url); //change , symbol to ','
            $re_url =  urldecode($chg);

            $data = $this->conn->prepare("SELECT product.product_id , product.product_attachs, product.product_name , product.product_description , product.product_price , product_category.category_name FROM `product_category` JOIN product ON product_category.category_id = product.category_id WHERE product.product_id IN ( '$re_url' ) ");
            //$data->bindParam(":types",$re_wish);
            
            $data->execute();
            $res = $data->fetchAll();
            if(empty($res))
            {
                return null;
            }else{

                //add new column inside (quanlity of product)
                $ress = array("Products"=>$res, "Count"=>$count_each);
                $pretty = json_encode($ress , JSON_PRETTY_PRINT);

                return $pretty;

            }


        }

        public function view_my_cart_visitor($session)
        {
            //get package lists
            //show package details
            $lists = self::get_cart_by_session($session);
            $conv_array = explode(",",$lists);
            $count_each = array_count_values($conv_array);
            $product_ids_each = array();

            foreach($count_each as $product => $count)
            {
                //get each product id only (excluded counts)
                array_push($product_ids_each,$product);
            }

            $exploded_productid = implode(",",$product_ids_each);
            $url = urlencode($exploded_productid);
            $chg = str_replace('%2C','%27%2C%27',$url); //change , symbol to ','
            $re_url =  urldecode($chg);

            $data = $this->conn->prepare("SELECT product.product_id , product.product_attachs, product.product_name , product.product_description , product.product_price , product_category.category_name FROM `product_category` JOIN product ON product_category.category_id = product.category_id WHERE product.product_id IN ( '$re_url' ) ");
            //$data->bindParam(":types",$re_wish);
            
            $data->execute();
            $res = $data->fetchAll();
            if(empty($res))
            {
                return null;
            }else{

                //add new column inside (quanlity of product)
                $ress = array("Products"=>$res, "Count"=>$count_each);
                $pretty = json_encode($ress , JSON_PRETTY_PRINT);

                return $pretty;

            }

            

        }

        public function get_listings_by_array($list)
        {
            //$exploded_productid = implode(",",$product_ids_each);
            $url = urlencode($list);
            $chg = str_replace('%2C','%27%2C%27',$url); //change , symbol to ','
            $re_url =  urldecode($chg);

            $data = $this->conn->prepare("SELECT product.product_id , product.product_attachs, product.product_name , product.product_description , product.product_price , product_category.category_name FROM `product_category` JOIN product ON product_category.category_id = product.category_id WHERE product.product_id IN ( '$re_url' ) ");
            //$data->bindParam(":types",$re_wish);
            
            $data->execute();
            $res = $data->fetchAll();

            return $res;

        }

        
        public function delete_one_cart_items($pids_del,$role,$acc_session_val,$nows)
        {
            if($role == "member")
            {
                //if member 
                $lists = self::get_cart_by_accno($acc_session_val);
                $conv_array = explode(",",$lists);
                $count_each = array_count_values($conv_array);
                if(array_key_exists($pids_del,$count_each))
                {
                    //check value isnt more than one?
                    $count_del_item = $count_each[$pids_del];
                    if($count_del_item > 1)
                    {
                        $latest_value = $count_del_item -1;
                        $count_each[$pids_del] = $latest_value;
                       
                    }else
                    {
                        //only one
                        //direct unset the del_item
                        unset($count_each[$pids_del]);
                    }

                    $new_list = array();
                    foreach($count_each as $val => $count)
                    {
                        for($i = 1; $i <= $count ; $i++)
                        {
                            array_push($new_list,$val);
                        }
                    }
                    $new_list_conv = implode(",",$new_list);
                    $res = self::update_cart($new_list_conv,$acc_session_val,$nows);
                    return $res;

                }else
                {
                    return "no match";
                }



            }else if($role == "visitor")
            {
                //if visitor
                
                $lists = self::get_cart_by_session($acc_session_val);
                if(empty($lists))
                {
                    echo "empty list";

                }else
                {
                    $conv_array = explode(",",$lists);
                    $count_each = array_count_values($conv_array);

                    if(array_key_exists($pids_del,$count_each))
                    {
                        //check value isnt more than one?
                        $count_del_item = $count_each[$pids_del];
                        if($count_del_item > 1)
                        {
                            $latest_value = $count_del_item -1;
                            $count_each[$pids_del] = $latest_value;
                        
                        }else
                        {
                            //only one
                            //direct unset the del_item
                            unset($count_each[$pids_del]);
                        }
                        $new_list = array();
                        foreach($count_each as $val => $count)
                        {
                            for($i = 1; $i <= $count ; $i++)
                            {
                                array_push($new_list,$val);
                            }
                        }
                        $new_list_conv = implode(",",$new_list);
                        $res = self::update_cart_visitor($new_list_conv,$acc_session_val,$nows);
                        return $res;

                    }else
                    {
                        echo "no match";
                    }
                }
                


            }else
            {
                return "Error Invalid Credential on its Action";
            }

            //return $pids_del;


        }

        public function order_submit_visitor($name, $email, $phone, $address, $total, $taxs, $desc,$en_acc_no,$sess)
        {
            require_once("../libraries/my_functions.php");
            $my_functions = new My_functions();
            $lists = self::get_cart_by_session($sess);
            if(!empty($lists))
            {
                try{
                    $ref_id_gen = uniqid();
                    $now_time = $my_functions->now(); 
                    $com = $this->conn->prepare('INSERT INTO orders VALUES(NULL, :ref, :na, :list, :email , :phone , :addresses , :noww , :total , :tax , :descri , :tokens );');
                    $com->bindParam(':ref',$ref_id_gen);
                    $com->bindParam(':na',$name);
                    $com->bindParam(':list',$lists);
                    $com->bindParam(':email',$email);
                    $com->bindParam(':phone',$phone);
                    $com->bindParam(':addresses',$address);
                    $com->bindParam(':noww',$now_time);
                    $com->bindParam(':total',$total);
                    $com->bindParam(':tax',$taxs);
                    $com->bindParam(':descri',$desc);
                    $com->bindParam(':tokens',$en_acc_no);
        
        
                    $com->execute();
                    
                    return "insert";
        
                }catch(Exception $e)
                {
                    return $e->getMessage();
        
                }
        


            }else 
            {
                echo "Error Occured : Empty" ;
            }
            return $lists;

        }


        public function order_submit_member($name, $email, $phone, $address, $total, $taxs, $desc,$en_acc_no)
        {
            require_once("../libraries/my_functions.php");
            $my_functions = new My_functions();
            $lists = self::get_cart_by_accno($en_acc_no);
            if(!empty($lists))
            {
                try{
                    $ref_id_gen = uniqid();
                    $now_time = $my_functions->now(); 
                    $com = $this->conn->prepare('INSERT INTO orders VALUES(NULL, :ref, :na, :list, :email , :phone , :addresses , :noww , :total , :tax , :descri , :tokens );');
                    $com->bindParam(':ref',$ref_id_gen);
                    $com->bindParam(':na',$name);
                    $com->bindParam(':list',$lists);
                    $com->bindParam(':email',$email);
                    $com->bindParam(':phone',$phone);
                    $com->bindParam(':addresses',$address);
                    $com->bindParam(':noww',$now_time);
                    $com->bindParam(':total',$total);
                    $com->bindParam(':tax',$taxs);
                    $com->bindParam(':descri',$desc);
                    $com->bindParam(':tokens',$en_acc_no);
        
        
                    $com->execute();
                    
                    return "insert";
        
                }catch(Exception $e)
                {
                    return $e->getMessage();
        
                }
        


            }else 
            {
                echo "Error Occured : Empty" ;
            }
            return $lists;


        }
        public function create_order_trx_member($memberid, $sessionid, $order_id)
        {
            require_once("../libraries/my_functions.php");
            $my_functions = new My_functions();

            try{
                $tracking_number = 'DOT-'.rand();
                $now_time = $my_functions->now(); 
                $status = 1;
                $com = $this->conn->prepare('INSERT INTO orders_trx VALUES(NULL, :track_no , :memberid, :session_ids , :orderid , :statusess );');
                $com->bindParam(':track_no',$tracking_number);
                $com->bindParam(':memberid',$memberid);
                $com->bindParam(':session_ids',$sessionid);
                $com->bindParam(':orderid',$order_id);
                $com->bindParam(':statusess',$status);
                
    
                $com->execute();
    
                return 1;
    
            }catch(Exception $e)
            {
                return $e->getMessage();
    
            }
    

        }

        public function get_order_details_by_ref($ref)
        {
            $data = $this->conn->prepare("SELECT * FROM orders JOIN orders_trx ON orders.order_id = orders_trx.order_id WHERE orders.ref_id = :ref ");
            $data->bindParam(':ref', $ref);
            $data->execute();
            $res = $data->fetchAll();
            return $res;

        }

        public function get_detail_by_token($token_acc)
        {
            $data = $this->conn->prepare("SELECT * FROM member WHERE token = :tkn");
            $data->bindParam(':tkn', $token_acc);
            $data->execute();

            $res = $data->fetch();
            
            return $res;

        }

    public function get_latest_inserted_row_order($tbname,$tkn=1)
    {
        if($tbname == "member")
        {
            $data = $this->conn->prepare("SELECT * FROM member WHERE email_address = :email ORDER BY memberid DESC LIMIT 1");
            $data->bindParam(':email',$tkn);
            $data->execute();
            $res = $data->fetch();
            return $res;

        }elseif($tbname == "orders")
        {
            $data = $this->conn->prepare("SELECT * FROM `orders` WHERE token = :tkn ORDER BY order_created_date DESC LIMIT 1");
            $data->bindParam(':tkn',$tkn);
            $data->execute();
            $res = $data->fetch();
            return $res;

        }elseif($tbname == "orders_trx")
        {
            $data = $this->conn->prepare("SELECT * FROM `orders_trx` ORDER BY orders_trx_id DESC LIMIT 1");
            $data->execute();
            $res = $data->fetch();
            return $res;

        }elseif($tbname == "product")
        {
            $data = $this->conn->prepare("SELECT * FROM `product` ORDER BY product_id DESC LIMIT 1");
            $data->execute();
            $res = $data->fetch();
            return $res;

        }elseif($tbname == "promotion")
        {
            $data = $this->conn->prepare("SELECT * FROM `promotion` ORDER BY promotion_id DESC LIMIT 1");
            $data->execute();
            $res = $data->fetch();
            return $res;

        }elseif($tbname == "promotion_log")
        {
            $data = $this->conn->prepare("SELECT * FROM `promotion_log` ORDER BY promotion_log_id DESC LIMIT 1");
            $data->execute();
            $res = $data->fetch();
            return $res;

        }elseif($tbname == "visitor_log")
        {
            $data = $this->conn->prepare("SELECT * FROM `visitor_log` ORDER BY modified_log_time DESC LIMIT 1");
            $data->execute();
            $res = $data->fetch();
            return $res;

        }else
        {
            return 0;
        }
        


    }

    public function insert_promo_log($order_id,$promo_code,$token)
    {
        require_once("../libraries/my_functions.php");
        $my_functions = new My_functions();

        try{
            $now_time = $my_functions->now();  
            $com = $this->conn->prepare('INSERT INTO promotion_log VALUES(NULL , :oids, :promo_code , :timess , :token );');
            $com->bindParam(':oids',$order_id);
            $com->bindParam(':promo_code',$promo_code);
            $com->bindParam(':timess',$now_time);
            $com->bindParam(':token',$token);
            $com->execute();

            return 1;

        }catch(Exception $e)
        {
            return $e->getMessage();

        }


    }

    public function update_carts($list,$ecc_no_en)
    {
        try{
            
            $data = $this->conn->prepare("UPDATE wishlist SET package_ids = :list WHERE acc_no_encrypted = :ecc ;");
            $data->bindParam(':list', $list);
            $data->bindParam(':ecc', $ecc_no_en);
            $data->execute();


            return "updated";

        }catch(Exception $e)
        {
            //echo "Commit Failed : " . $e->getMessage();
            return $e->getMessage();


        }


    }

    public function get_order_details($token_acc)
    {
        //SELECT * FROM `product_category` JOIN product ON product_category.category_id = product.category_id
        $data = $this->conn->prepare("SELECT * FROM orders JOIN orders_trx ON orders.order_id = orders_trx.order_id WHERE orders.token = :tkn ORDER BY orders.order_id DESC LIMIT 1");
        $data->bindParam(':tkn', $token_acc);
        $data->execute();
        $res = $data->fetchAll();
        $ress = array("Order"=> $token_acc,"Responses"=>$res);

        $pretty = json_encode($ress , JSON_PRETTY_PRINT);

        return $pretty;


    }

    public function get_last_cart_list($ecc_no_en)
    {
        $data = $this->conn->prepare("SELECT * FROM `orders` WHERE token = :acc ORDER BY order_id DESC LIMIT 1");
        $data->bindParam(':acc',$ecc_no_en);
        $data->execute();
        $res = $data->fetch();

        return $res['order_packages'];

    }

    /**
     * -----------------------------
     * Administrator Control
     * -----------------------------
     */

     public function get_all_spec_products()
     {
         /*
            GET ALL TYPE LISTINGS
            */
            $data = $this->conn->prepare("SELECT product.product_id , product.product_name , product.product_description , product.product_attachs,  product.product_price , product_category.category_name FROM `product_category` JOIN product ON product_category.category_id = product.category_id ");            
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

     public function get_all_orders()
     {
        $data = $this->conn->prepare("SELECT * FROM orders JOIN orders_trx ON orders.order_id = orders_trx.order_id ; ");
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

     public function get_promo_used()
     {
        $data = $this->conn->prepare("SELECT * FROM `promotion_log` JOIN promotion ON promotion_log.promotion_code = promotion.promo_code JOIN orders ON promotion_log.order_id = orders.order_id ;");
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

     public function get_all_promotion()
     {
         //SELECT * FROM `promotion` JOIN promotion_rate ON promotion.promotion_rate_id = promotion_rate.promo_rate_id
         $data = $this->conn->prepare("SELECT * FROM `promotion` JOIN promotion_rate ON promotion.promotion_rate_id = promotion_rate.promo_rate_id ; ");
         $data->execute();
         $res = $data->fetchAll();
 
         if(empty($res))
         {
             return null;
         }else{
            
             return $res;
 
         }

     }

     public function get_all_promotion_member_limit()
     {

     }

     public function get_all_promotion_public()
     {

     }

     public function insert_promotion($name,$start,$end,$code,$desc,$rateid,$require_role)
     {
        try{
            $com = $this->conn->prepare('INSERT INTO promotion VALUES(NULL , :n, :startd , :endd , :code , null , null , :qp , :descs, :rateid );');
            $com->bindParam(':n',$name);
            $com->bindParam(':startd',$start);
            $com->bindParam(':endd',$end);
            $com->bindParam(':code',$code);
            //$com->bindParam(':a',$attach);
            //$com->bindParam(':qr',NULL);
            $com->bindParam(':qp',$require_role);
            $com->bindParam(':descs',$desc);
            $com->bindParam(':rateid',$rateid);

            $com->execute();

            return "Promotion Added";

        }catch(Exception $e)
        {
            return $e->getMessage();

        }

     }

     public function insert_new_product($name,$desc,$type,$price,$catid,$attchs)
     {
        $status = 1;
        try{
            $com = $this->conn->prepare('INSERT INTO product VALUES(NULL , :n, :att , :de , :t , :pri , :cat , :ss );');
            $com->bindParam(':n',$name);
            $com->bindParam(':att',$attchs);
            $com->bindParam(':de',$desc);
            $com->bindParam(':t',$type);
            $com->bindParam(':pri',$price);
            $com->bindParam(':cat',$catid);
            $com->bindParam(':ss',$status);

            

            $com->execute();

            return "Product Added";

        }catch(Exception $e)
        {
            return $e->getMessage();

        }


     }

     public function change_status_order($status, $order_id)
     {
            try{
                $data = $this->conn->prepare("UPDATE `orders_trx` SET `status` = :status_code WHERE `orders_trx_id` = :oids ; ");
                $data->bindParam(':status_code', $status);
                $data->bindParam(':oids', $order_id);
                $data->execute();

                return "Status Updated";

            }catch(Exception $e)
            {
                $data->rollback();
                return "Commit Failed : " . $e->getMessage();

            }

     }

     public function update_product($name, $descs, $prices, $pid)
     {
        try{
            
            $data = $this->conn->prepare("UPDATE product SET product_name = :names , product_description = :descs , product_price = :prices  WHERE product_id = :pid ;");
            $data->bindParam(':names', $name);
            $data->bindParam(':descs', $descs);
            $data->bindParam(':prices', $prices);
            $data->bindParam(':pid', $pid);

            

            $data->execute();


            return "Detail Updated";

        }catch(Exception $e)
        {
            //echo "Commit Failed : " . $e->getMessage();
            return $e->getMessage();


        }
     }

     public function fetch_product_by_id($types)
     {
        $data = $this->conn->prepare("SELECT * FROM product WHERE product_id = :types ");
        $data->bindParam(":types",$types);
        
        $data->execute();
        $res = $data->fetch();
        if(empty($res))
        {
            return null;
        }else{
            //$ress = array("Products"=>$res);
            //$pretty = json_encode($ress , JSON_PRETTY_PRINT);
            
            return $res;

        }

     }

     public function fetch_promotion_by_id($types)
     {
        $data = $this->conn->prepare("SELECT * FROM promotion WHERE promotion_id = :types ");
        $data->bindParam(":types",$types);
        
        $data->execute();
        $res = $data->fetch();
        if(empty($res))
        {
            return null;
        }else{
            //$ress = array("Products"=>$res);
            //$pretty = json_encode($ress , JSON_PRETTY_PRINT);
            
            return $res;

        }

     }

     public function update_promotion($name, $descs, $codes, $pid)
     {
        try{
            
            $data = $this->conn->prepare("UPDATE promotion SET promotion_name = :names , promo_code = :codes , descriptions = :descs  WHERE promotion_id = :pid ;");
            $data->bindParam(':names', $name);
            $data->bindParam(':codes', $codes);
            $data->bindParam(':descs', $descs);
            $data->bindParam(':pid', $pid);

            

            $data->execute();


            return "Detail Updated";

        }catch(Exception $e)
        {
            //echo "Commit Failed : " . $e->getMessage();
            return $e->getMessage();


        }

        
     }

    public function get_order_by_id($oids)
    {
        $data = $this->conn->prepare("SELECT * FROM orders JOIN orders_trx ON orders.order_id = orders_trx.order_id WHERE orders.order_id = :types ;");
        $data->bindParam(":types",$oids);
        
        $data->execute();
        $res = $data->fetch();
        if(empty($res))
        {
            return null;
        }else{
            //$ress = array("Products"=>$res);
            //$pretty = json_encode($ress , JSON_PRETTY_PRINT);
            
            return $res;

        }

       
            
    }

    public function update_orders($name, $phone, $address, $desc, $orderid)
    {
        try{
            
            $data = $this->conn->prepare("UPDATE `orders` SET `delivery_name` = :names , `phone_number` = :phone , `address` = :addresss , `descriptions` = :descs  WHERE `order_id` = :pid ;");
            $data->bindParam(':names', $name);
            $data->bindParam(':phone', $phone);
            $data->bindParam(':addresss', $address);
            $data->bindParam(':descs', $desc);
            $data->bindParam(':pid', $orderid);

            

            $data->execute();


            return "Detail Updated";

        }catch(Exception $e)
        {
            //echo "Commit Failed : " . $e->getMessage();
            return $e->getMessage();


        }
        
    }

    public function delete_orders($product_id)
    {
        try{
            
            $data = $this->conn->prepare("DELETE FROM orders WHERE order_id = :pids ;");
            $data->bindParam(':pids', $product_id);

            $data->execute();

            $return = $product_id . "Had Been Deleted";

            return $return;

        }catch(Exception $e)
        {
            //echo "Commit Failed : " . $e->getMessage();
            return $e->getMessage();


        }
        

    }

    public function delete_orders_trx($product_id)
    {
        try{
            
            $data = $this->conn->prepare("DELETE FROM orders_trx WHERE order_id = :pids ;");
            $data->bindParam(':pids', $product_id);

            $data->execute();

            $return = $product_id . "Had Been Deleted";

            return $return;

        }catch(Exception $e)
        {
            //echo "Commit Failed : " . $e->getMessage();
            return $e->getMessage();


        }
        

    }

    public function delete_product($product_id)
    {
        try{
            
            $data = $this->conn->prepare("DELETE FROM product WHERE product_id = :pids ;");
            $data->bindParam(':pids', $product_id);

            $data->execute();

            $return = $product_id . "Had Been Deleted";

            return $return;

        }catch(Exception $e)
        {
            //echo "Commit Failed : " . $e->getMessage();
            return $e->getMessage();


        }
        

    }

    public function delete_promotion($product_id)
    {
        try{
            
            $data = $this->conn->prepare("DELETE FROM promotion WHERE promotion_id = :pids ;");
            $data->bindParam(':pids', $product_id);

            $data->execute();

            $return = $product_id . "Had Been Deleted";

            return $return;

        }catch(Exception $e)
        {
            //echo "Commit Failed : " . $e->getMessage();
            return $e->getMessage();


        }
        

    }

    //add repeat orders 
    public function get_previous_details($token_acc)
    {
        $data = $this->conn->prepare("SELECT * FROM orders JOIN orders_trx ON orders.order_id = orders_trx.order_id WHERE orders.token = :tkn ORDER BY orders.order_id DESC LIMIT 1");
        $data->bindParam(':tkn', $token_acc);
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

    public function get_previous_list($token_acc)
    {
        $data = $this->conn->prepare("SELECT order_packages FROM orders WHERE token = :tkn ORDER BY order_id DESC LIMIT 1;");
            $data->bindParam(':tkn', $token_acc);
            $data->execute();

            $res = $data->fetch();
            
            if(empty($res))
            {
                return null;
            }else{
                //$ress = array("Products"=>$res);
                //$pretty = json_encode($ress , JSON_PRETTY_PRINT);
                
                return $res;

            }
    }

    
//dashboard 
public function get_limit_active_record()
{
    $active_rate = "1,2,3";
    //$exploded_active = implode(",",$active_rate);
    $url = urlencode($active_rate);
    $chg = str_replace('%2C','%27%2C%27',$url); //change , symbol to ','
    $re_url =  urldecode($chg);

    $data = $this->conn->prepare("SELECT * FROM orders JOIN orders_trx ON orders.order_id = orders_trx.order_id WHERE orders_trx.status IN('$re_url') ORDER BY orders.order_id ASC LIMIT 4;");
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

public function get_uncompleted()
{
    $active_rate = "1,2,3";
    //$exploded_active = implode(",",$active_rate);
    $url = urlencode($active_rate);
    $chg = str_replace('%2C','%27%2C%27',$url); //change , symbol to ','
    $re_url =  urldecode($chg);

    $data = $this->conn->prepare("SELECT * FROM orders JOIN orders_trx ON orders.order_id = orders_trx.order_id WHERE orders_trx.status IN('$re_url') ORDER BY orders.order_id ASC ;");
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

public function get_completed()
{
    $active_rate = "4";
    //$exploded_active = implode(",",$active_rate);
    $url = urlencode($active_rate);
    $chg = str_replace('%2C','%27%2C%27',$url); //change , symbol to ','
    $re_url =  urldecode($chg);

    $data = $this->conn->prepare("SELECT * FROM orders JOIN orders_trx ON orders.order_id = orders_trx.order_id WHERE orders_trx.status IN('$re_url') ORDER BY orders.order_id ASC ;");
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

public function get_active_promotion($curent_date)
{
    
    $data = $this->conn->prepare("SELECT * FROM `promotion` WHERE promotion_start <= :cur_dates AND promotion_end >= :cur_date ;");
    $data->bindParam(":cur_dates",$curent_date);
    $data->bindParam(":cur_date",$curent_date);
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
     
public function get_packages_order($oid)
{
    $data = $this->conn->prepare("SELECT * FROM `orders` WHERE order_id = :oids ;");
    $data->bindParam(':oids',$oid);
    $data->execute();
    $res = $data->fetch();

    return $res['order_packages'];
}
     

   
    
}





?>