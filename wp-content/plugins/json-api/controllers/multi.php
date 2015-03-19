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
                   $names[]=$test->post_title;
            }         
            return  ($names);
        }
        public function getQuestionsForTest(){
            global $json_api;
            $id=$json_api->query->id;
            $test=get_post_meta($id);
            $question=get_post(12);


			//$content = $test->post_content;
           // $content = apply_filters('the_content', $content);
			
			return 'hi';
        }
         
		//////////////////////////////
		////////grt welcome text for activity day
		////////////////////////////
		//public function getwelcome(){
		//	global $json_api;
         //          $id=$json_api->query->id;
		//	
		//	$day=get_post($id); 
		//	$welcome=$day->post_content;  
		//	return array('text'=>$welcome);
		//}

    }//class JSON_API_Cube_Controller







