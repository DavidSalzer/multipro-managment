<?php
    class JSON_API_Users_Controller{
    
        public function tester(){
           header("Access-Control-Allow-Origin: *");          
            return  ('hi');
        }

        //adds a user with no permissions
        public function add_user(){
            header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
            header("Access-Control-Allow-Credentials : true");
            global $json_api;
            $name=$json_api->query->username;
            $password=$json_api->query->password;
            $email=$json_api->query->email;
            $user_id = username_exists($name);
            //check that user isnt registerd allready
            if ( !$user_id and email_exists($email) == false ) {
	              $user_id= wp_create_user( $email, $password, $email );
                  wp_update_user( array( 'ID' => $user_id, 'display_name' => $name ) );
                   wp_set_current_user($user_id); 
                  $user=new WP_User($user_id);
                  $user->remove_role('subscriber');
                  $feedback=array('success'=>'success','text'=>'user added','id'=> $user_id);
            } 
            else {
                if($user_id)
	                $feedback=array('error'=>"error",'text'=>"user exists");
                else
                    $feedback=array('error'=>"error",'text'=>"email exists");
            }                      
            return $feedback;
        }
        
        //logs in as user
        public function logIn(){
           header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
            header("Access-Control-Allow-Credentials : true");
            header('p3p: CP="NOI ADM DEV PSAi COM NAV OUR OTR STP IND DEM"');
            global $json_api;
            //gets the data for the log in
            $email=$json_api->query->email;
            $password=$json_api->query->password;
           
            $feedback=array();
            $creds = array();
	        $creds['user_login'] = $email;
	        $creds['user_password'] =$password;
	        $creds['remember'] = true;
	        $user = wp_signon( $creds, false );
	        if ( is_wp_error($user) ){
	              $feedback['error']='error';
                 $feedback['text']=$user->get_error_message();
	        }
            else{
                wp_set_current_user($user->ID); 
                $feedback['success']='success';
               $feedback['user']=$user;
            }
             
            return $feedback;
        }

        public function getUsers(){
            return get_users();
        }
        //checks if user is logged in
        public function check_is_logged_in(){
            header("Access-Control-Allow-Origin: *");
            return is_user_logged_in();
        }
        //gets the current user that is logged in
        public function getCurrent(){
            // header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
            header("Access-Control-Allow-Credentials : true");
            header('p3p: CP="NOI ADM DEV PSAi COM NAV OUR OTR STP IND DEM"');
             $logged=(is_user_logged_in())?(wp_get_current_user()):false;//check if user logged in
            return $logged;
        }
        //log out current user of session
        public function logOut(){
            header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
            header("Access-Control-Allow-Credentials : true");
            wp_logout();
        }
               
    }//class JSON_API_users_Controller
    
   




