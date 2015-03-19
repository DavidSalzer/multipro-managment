<?php
    class JSON_API_Multi_Controller{
    
        public function getYears(){
           $yearsObj=get_terms( 'test-year');
            $years=array();
            foreach($yearsObj as $year){
                   $years[]=$year->name;
            }
            return  ( $years);
        }
        public function getTestNames(){
            $testArray = get_posts( 'post_type=test' );
            $names=array();
            foreach($testArray as $test){
                   $names[]=array("id"=>$test->ID,"title"=>$test->post_title);
            }         
            return  ($names);
        }
        public function getTestNameForId(){
            global $json_api;
            $id=$json_api->query->id;
            return  $test=get_post($id)->post_title;
        }
        public function getQuestionsForTest(){
            global $json_api;
            $id=$json_api->query->id;
            $test=get_post_meta($id,'questions');
            $questionArr=array();
            foreach($test[0] as $qid){
                $questionArr[]=new questionContent($qid);
           }	
           return $questionArr;
        }
         
		

    }//class JSON_API_Cube_Controller
    
    //class for holdig data for question in tests
    class questionContent{
        public $id; 
        public $question; 
        public $answers;
        public $correctAns;

        function __construct($_id) {
            $this->id=$_id;
            $this->question=get_post_meta($_id,'wpcf-question');
            $this->answers=get_post_meta($_id,'wpcf-answer');
            $this->correctAns=get_post_meta($_id,'wpcf-correct');
        }        
    }//class questionContect





