<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct() {
        parent::__construct();
		$this->load->model('master_model');
    }

	public function index()
	{
		$this->data['title']="";
		$this->data['banner_text'] = $this->master_model->get_banner_text()->banner_text;
		$this->load->view('templates/header' , $this->data);
		$this->data['groups'] = $this->master_model->get_groups();
		$this->data['sub_groups'] = $this->master_model->get_sub_groups();
		$this->data['question_levels'] = $this->master_model->get_question_levels();
		$this->data['languages'] = $this->master_model->get_languages();
		// $this->data['questions'] = $this->master_model->get_questions();
		// $this->data['answer_options'] = $this->master_model->get_answer_options();
		$this->load->view('home', $this->data);
		$this->load->view('templates/footer');
	}

	public function quiz($page , $group , $sub_group , $question_level){
		// $this->data['title']="Quiz page";
		$per_page = 10;
		$start = ($page -1 ) * $per_page;
		$question_answers_list = array();
		$questions =  $this->master_model->get_questions($per_page ,$start , $group , $sub_group , $question_level);
		foreach( json_decode($questions) as $q){
			$question_answers_list[$q->question_id]  = (object)[ "question"=>$q, "answers"=> json_decode($this->master_model->get_answer_options_by_question_id($q->question_id))];
		}
		// var_dump($questions);
		print  json_encode($question_answers_list);				
		// print $questions;				
	}


	public function pages_count( $group="" , $sub_group="" ,  $level="" ){
		$rows_per_page="10"; 
		$pages_count = $this->master_model->get_pages_count($rows_per_page , $group , $sub_group);
		print json_encode($pages_count);
	}

}
