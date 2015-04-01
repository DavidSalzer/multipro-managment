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
            $testArray = get_posts( 'post_type=test' );
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
            $id=$json_api->query->id;
            $test=get_post_meta($id,'questions');
            $questionArr=array();
            foreach($test[0] as $qid){
                $questionArr[]=new questionContent($qid);
           }	
           return $questionArr;
        }
        public function getTests(){
            header("Access-Control-Allow-Origin: *");
            global $json_api;
            $testArray = get_posts( 'post_type=test' );
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
        public function set_questions_behavior(){
            header("Access-Control-Allow-Origin: *");
            global $json_api;
            $user_ID = get_current_user_id();
            $qArr=json_decode(stripslashes($json_api->query->questionArr));
            for($i=0;$i<sizeof($qArr);$i++){
                $question = $qArr[$i];
                $q_ID =  $question->id;
                $isCorrect = ($question->handler->correctAnswer==$question->handler->currentAnswer)?"true":"";//insert true for correct answer
                $chosen=$question->handler->currentAnswer;//chosen answer
                $timeInQuestion=$question->handler->timeInQuestion;
                $post=array(                
                    'post_status'   => 'publish',
                    'post_type'    =>  'question-behavior',   
                    'post_title'    => 'q-behave-' . $q_ID          
                 );
                   $postid= wp_insert_post( $post);
                   update_post_meta($postid, 'wpcf-time-in-question', $timeInQuestion);
                   update_post_meta($postid, 'wpcf-chosen-answer', $chosen);
                   update_post_meta($postid, 'wpcf-is-correct', $isCorrect);           
                   update_field( 'field_551a5a067c707', $q_ID, $postid );//connects behaviour to question(according to post id of wp);         
                   update_field( 'field_551a573d92c44', $user_ID, $postid );//connects behavior to user (according to user id on wp)
            }
           return   get_post_meta($postid);  
        }
        public function set_last_question(){
            header("Access-Control-Allow-Origin: *");
            global $json_api;
            $user_ID = get_current_user_id();
        }

        
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

   
    





