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
            $feedback=array();
            //check that user isnt registerd allready
            if ( !$user_id and email_exists($email) == false ) {
	              $user_id= wp_create_user( $email, $password, $email );
                  if ( is_wp_error($user_id)) {
                          $errortext=array();
                          $errors=$user_id->get_error_codes();
                          //if(sizeof($errors)==0){//bug in word press for empty doesnt give error codes
                          //    $errors=array('empty_username','empty_password');
                          //}
                          foreach($errors as $error){
                              $errortext[]=ErrorHandler::getError($error);   
                                                                                          
                          }
                 $feedback['error']=$errortext;
                  }//if error creating user
                  else{
                      wp_update_user( array( 'ID' => $user_id, 'display_name' => $name ) );
                      wp_set_current_user($user_id); 
                      $user=new WP_User($user_id);
                      $user->remove_role('subscriber');
                      $feedback=array('success'=>'success','text'=>'user added','id'=> $user_id);
                  }//if successful creating user
            } 
            else {
                if($user_id)
	               $feedback['error']=array('error'=>'username_exists','text'=>'שם משתמש קיים');
                else
                   $feedback['error']= array('error'=>'email_exists','text'=>'דוא"ל קיים כבר במערכת');
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
                  $errortext=array();
                  $errors=$user->get_error_codes();
                  if(sizeof($errors)==0){//bug in word press for empty doesnt give error codes
                      $errors=array('empty_username','empty_password');
                  }
                  foreach($errors as $error){
                      $errortext[]=ErrorHandler::getError($error);   
                                                                                          
                  }
                 $feedback['error']=$errortext;
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


    //handles errors for user actions
    Class ErrorHandler{
        private static $errorCodes=array(//holds the error codes of user actions and the text represent the error
                                  array('error'=>'empty_username','text'=>'חסר דוא"ל'),
                                  array('error'=>'invalid_username','text'=>'דוא"ל לא תקין'),
                                  array('error'=>'username_exists','text'=>'דוא"ל קיים כבר במערכת'),
                                  array('error'=>'empty_email','text'=>'חסר דוא"ל'),
                                  array('error'=>'invalid_email','text'=>'דוא"ל לא תקין'),
                                  array('error'=>'email_exists','text'=>'דוא"ל קיים כבר במערכת'),
                                  array('error'=>'registerfail','text'=>'כשל ברישום אנא צור קשר עם  '),
                                  array('error'=>'empty_password','text'=>'חסר סיסמא'),
                                  array('error'=>'empty_user_login','text'=>'חסר דוא"ל'),
                                  array('error'=>'existing_user_login','text'=>'דוא"ל קיים כבר במערכת  '),//the usename is the email
                                  array('error'=>'existing_user_email','text'=>'דוא"ל קיים כבר במערכת'),
                                  );
        private static $defaultError=array('error'=>'default','text'=>'שגיאה');
        //returns the error according to error code that returned from wp
       static function getError($eCode){
           $myError=self::$defaultError;//sets to defualt error
           foreach(self::$errorCodes as $errorCode){
               if($errorCode['error']==$eCode){//found wanted ocde
                    $myError=$errorCode;
                    break;
               }
           }
        return  $myError;
       }
    }
    
   




