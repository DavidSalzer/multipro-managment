<?php
    class JSON_API_Users_Controller{
    
        public function tester(){
           header("Access-Control-Allow-Origin: *");          
            return  ('hi');
        }

        //adds a user with no permissions
        public function add_user(){
            header("Access-Control-Allow-Origin: *");
            global $json_api;
            $name=$json_api->query->username;
            $password=$json_api->query->password;
            $email=$json_api->query->email;
            $user_id = username_exists($name);
            //check that user isnt registerd allready
            if ( !$user_id and email_exists($email) == false ) {
	              $user_id= wp_create_user( $name, $password, $email );
                  $user=new WP_User($user_id);
                  $user->remove_role('subscriber');
                  $feedback=array('success','user added', $user_id);
            } 
            else {
                if($user_id)
	                $feedback=array("error","user exists");
                else
                    $feedback=array("error","email exists");
            }                      
            return $feedback;
        }
        
        //logs in as user
        public function logIn(){
            header("Access-Control-Allow-Origin: *");
            global $json_api;
            //gets the data for the log in
            $name=$json_api->query->username;
            $password=$json_api->query->password;
           
            $feedback=array();
            $creds = array();
	        $creds['user_login'] = $name;
	        $creds['user_password'] =$password;
	        $creds['remember'] = true;
	        $user = wp_signon( $creds, false );
	        if ( is_wp_error($user) )
		       $feedback['error']=$user->get_error_message();
            else
               $feedback['success']=$user;
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
             header("Access-Control-Allow-Origin: *");
            return wp_get_current_user();
        }
        //log out current user of session
        public function logOut(){
            header("Access-Control-Allow-Origin: *");
            wp_logout();
        }
               
    }//class JSON_API_users_Controller
    
   




