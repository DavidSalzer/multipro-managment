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
            $this->bookReferance=get_post_meta($_id,'wpcf-book-referance');
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

   
    





