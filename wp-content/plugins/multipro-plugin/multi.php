<?php
    class JSON_API_Multi_Controller{
    
         
        public function getYears(){
           header("Access-Control-Allow-Origin: *");
           $yearsObj=get_terms( 'test-year');
            $years=array();
            foreach($yearsObj as $year){
                   $years[]=$year->name;
            }
            return  ( $years);
        }
        public function getTestNames(){
            header("Access-Control-Allow-Origin: *");
            $args=array( 'post_type' => 'test', 'post_per_page' => 10 );
            $testArray = get_posts( $args );
            $names=array();
            foreach($testArray as $test){
                   $names[]=array("id"=>$test->ID,"title"=>$test->post_title);
            }         
            return  ($names);
        }
        public function getTestNameForId(){
            header("Access-Control-Allow-Origin: *");
            global $json_api;
            $id=$json_api->query->id;
            return  $test=get_post($id)->post_title;
        }
        public function getQuestionsForTest(){
            header("Access-Control-Allow-Origin: *");
            global $json_api;
            //get sent arguments
            $id=$json_api->query->id;
            $userID=$json_api->query->userID;
            $testID=$json_api->query->testID;
            $numOfQuestions=$json_api->query->numberOfQuestions;
            //get the last question for user for test
            $lastQuestion = 	$this->getLastQuestion();
           
            $test=get_post_meta($testID,'questions');
            $questionArr=array();
            for($i=$lastQuestion,$j=0;$i<sizeof($test[0])&&$j<$numOfQuestions;$j++,$i++){//starts from the last question and gives the questions from that question and for the number of questions wanted
                $questionArr[]=new questionContent($test[0][$i]);
           }
           $doneQusetions=array();//the questions for test that were done allready returns this array with the data that was done on the questions
           for($i=0;$i<sizeof($test[0])&&$i<$lastQuestion;$i++){
               $doneQusetions[]=$this->get_question_behavior($userID,$test[0][$i]);
               //$doneQusetions[]=new questionContent($test[0][$i]);
           }
           
           return array("test"=>$questionArr,"done"=>$doneQusetions);//two arrays one hold the questions to be done, second the array of questions done allready
          // return array("done"=>$doneQusetions);
          
        }
        //gets for a given question the data that was done on that question
        function get_question_behavior($userID,$qid){
                      
             $user_ID =$userID;
             $question_ID=$qid;
             $args = array( 'post_type'=>'question-behavior','meta_query'=>array(array('key' =>'question','value' =>$question_ID),array('key' =>'user','value' =>$user_ID)));
             $posts=get_posts($args);//gets the last question post according to user id and test id
             $qId=$posts[0]->ID;
            // echo $qId;
             $postData=get_post_meta($qId,'wpcf-qObject');
            // echo json_encode($postData);
             if(gettype($postData[0])=='string'){
                $postData[0]=json_decode($postData[0]);
             }
                      
             $postData[0]->handler->{'handler_id'}=$qId;
             return $postData[0];
             
        }
        
        //a post call for given question handler upades data
        //@param qId - the id of the question_behavior
        //@param question - the question data to update -(all of the question would be added to the question behavior)
         public function update_question_behavior(){
             header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
              global $json_api;
              $qID=$json_api->query->qId;
              $question=$json_api->query->question;
              $question=json_decode($question);
              //$postID=get_post( (int)$qID);
              update_post_meta($qID, 'wpcf-qObject', $question);
              return get_post_meta($qID);
             
         }
         function test_qb(){
             header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
            return $this->get_question_behavior(59,26);
        }

        //gets the last question thhat the user was at for given test
         public  function getLastQuestion(){
            global $json_api;
            $userID=$json_api->query->userID;
            $testID=$json_api->query->testID;
               $post= get_user_meta($userID, 'lastquestion');//gets the id post of the last question of user 
            //check that there are actula last question posts
            if(sizeof($post)>0){          
                $args = array( 'post_type'=>'last-question','post__in' => $post,'meta_query'=>array(array('key' =>'test','value' =>$testID)));
                $posts=get_posts($args);//gets the last question post according to user id and test id
                $lastId=$posts[0]->ID;//the posts are ordered by date so the newest one is the modified question
                $lastq=get_post_meta($lastId);
                if($lastId!=false)
                    $a=$lastq["last-question"];//there is a last question
                else
                    $a[0]=0;//there is no last question
            }
            else//there are no last question posts for userid
                $a[0]=0;
           
           return $a[0];
           
        }
        public function getTests(){
            header("Access-Control-Allow-Origin: *");
            global $json_api;
             $args=array( 'post_type' => 'test', 'posts_per_page' => -1 );
            $testArray = get_posts(  $args );
            $testArr=array();
            for($i=0;$i<sizeof($testArray);$i++){
                $testArr[]=new testData($testArray[$i]->ID);
            }	
           return $testArr;       
        }
        //gets post data for post id-tester
        public function getQB(){
            return get_post_meta(1149);
           //return get_posts( 'post_type=question-behavior' );
        }
        //gets post meta for given id-tester
        public function getPQB(){
            return get_post(1106);
           //return get_posts( 'post_type=question-behavior' );
        }

        //sets behavior for test - data for questions and last question done for user
        public function set_test_behavior(){
            header("Access-Control-Allow-Origin: *");
            global $json_api;
            $user_ID = $json_api->query->userID;
            $qArr=json_decode(stripslashes($json_api->query->questionArr));//parses the array of the questions of input
            $test_id=$json_api->query->testID;
            $lastvistedQuestion=$this->set_last_question($this->findLastVisitedQuestion($qArr));
            $qb=$this->set_questions_behavior($lastvistedQuestion);
            $lastId=$this->set_last_question($this->findLastVisitedQuestion($qArr));
            return array($qb,$lastId);
            //return $lastId;
           
        }
       

        //searches the given array of questions and finds the last question that was visited
        private function findLastVisitedQuestion($arr){
            $lastId=0;
            for($i=sizeof($arr)-1;$i>=0&&$lastId==0;$i--){//run through the array from the end and if found a visited question set last to that question
                    if($arr[$i]->handler->timesvisited>0)//if there was a visit
                        $lastId=$i+1;
            }
            return $lastId;
        }

        public function set_questions_behavior($lastvistedQuestion){
           // header("Access-Control-Allow-Origin: *");
            global $json_api;
            $user_ID = $json_api->query->userID;
            $qArr=json_decode(stripslashes($json_api->query->questionArr));
            for($i=0;$i<sizeof($qArr)&&$i<$lastvistedQuestion;$i++){               
                $question = $qArr[$i];
                 if($question->handler->handler_id==-1){// if there isnt yet a question handler
                $q_ID =  $question->id;
                $isCorrect = ($question->handler->correctAnswer==$question->handler->currentAnswer)?"true":"";//insert true for correct answer
                $chosen=$question->handler->currentAnswer;//chosen answer
                $timeInQuestion=$question->handler->timeInQuestion;
                $post=array(                
                    'post_status'   => 'publish',
                    'post_type'    =>  'question-behavior',   
                    'post_title'    => 'q-behave-' . $q_ID.$user_ID          
                 );
                   $postid= wp_insert_post( $post);
                   $question->handler->handler_id=$postid;//updates in data of question the id of the question behavior for further use
                   update_post_meta($postid, 'wpcf-qObject', $question);
                   update_post_meta($postid, 'wpcf-time-in-question', $timeInQuestion);
                   update_post_meta($postid, 'wpcf-chosen-answer', $chosen);
                   update_post_meta($postid, 'wpcf-is-correct', $isCorrect);           
                   update_field( 'field_551a5a067c707', $q_ID, $postid );//connects behaviour to question(according to post id of wp);         
                   update_field( 'field_551a573d92c44', $user_ID, $postid );//connects behavior to user (according to user id on wp)
                }
                else//if there is a question handler so just update that question behavior
                    update_post_meta($question->handler->handler_id, 'wpcf-qObject', $question);
            }
           return   get_post_meta($postid,'wpcf-qObject');  
        }
        public function save_questions(){
            
        }
        public function setlast(){//tester
           return $this->set_last_question(0);
        }
       
        public function set_last_question($numOfAdded){
            //header("Access-Control-Allow-Origin: *");
            global $json_api;
            $user_id=$json_api->query->userID;                        
            $test_id=$json_api->query->testID;

            $numOfQuestion=$numOfAdded;
           
            $post=array(                
                    'post_status'   => 'publish',
                    'post_type'    =>  'last-question',   
                    'post_title'    => 'last-question'.$user_id.$test_id       
                 );
             $postid= wp_insert_post( $post);//creates a last question post
            
            update_field( 'last-question',$numOfQuestion, $postid );//connects behavior to question(according to question id on wp)
            update_field( 'test',  $test_id, $postid );//connects behavior to test (according to test id on wp)
            $a=add_metadata( 'user',$user_id,'lastquestion',  $postid);
            return array(get_post_meta($postid),get_user_meta($user_id),$a,$postid);
        }
         
        public function get_last_question(){
            header("Access-Control-Allow-Origin: *");
            global $json_api;
            $userID=$json_api->query->userID;
            $testID=$json_api->query->testID;
            $post= get_user_meta($userID, 'lastquestion');//gets the id post of the last question of user           
            $args = array( 'post_type'=>'last-question','post__in' => $post,'meta_key'=>'test','meta_value'=>$testID);
            $posts=get_posts($args);//gets the last question post according to user id and test id
            $lastId=$posts[0]->ID;//the posts are ordered by date so the newest one is the modified question
            $lastq=get_post_meta($lastId);
            if($lastId!=false)
                $a=$lastq["last-question"];//there is a last question
            else
                $a[0]=0;//there is no last question
            return  $a[0];                      
        }
        //public function get_last_question_id($userID,$testID){
        //     $post= get_user_meta($userID, 'lastquestion');//gets the id post of the last question of user
        //     $postsidstring=implode(",",$post[0]);//stringifies the id's of all last question references
        //     $posts=get_posts('post_type=last-question&post='.$postsidstring.'&meta_key=last-question&meta_value='.$testID);//gets the post with the last question of given test
        //    return   $posts;
        // }

        
    }//class JSON_API_Cube_Controller
    
    //class for holdig data for question in tests
    class questionContent{
        public $id; 
        public $question; 
        public $answers;
        public $correctAns;
        public $bookReferance;
       
        public $year;

        function __construct($_id) {
            $this->id=$_id;
            $this->question=get_post_meta($_id,'wpcf-question');
            $this->answers=get_post_meta($_id,'wpcf-answer');
            $this->correctAns=get_post_meta($_id,'wpcf-correct');
            $book = wp_get_post_terms( $_id, 'book');
            $page= get_post_meta($_id,'wpcf-book-referance');
            $this->bookReferance=array($book[0]->name,$page[0]);
            $temp=wp_get_post_terms( $_id, 'test-year');
            $this->year=$temp[0]->name;
        }        
    }//class questionContect

    class testData{
        public $id;
        public $numberOfQuestions;
        public $title;

         function __construct($_id) {
                $this->id=$_id;
                $temp=get_post_meta($_id,'questions');
                $this->numberOfQuestions=sizeof($temp[0]);
                $this->title=get_post($_id)->post_title;
        }
    }

   
    





