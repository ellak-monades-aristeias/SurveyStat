<?php

class Questionnaire extends Public_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('questionnaire_mod');
	}
	

	function index() {
		redirect('questionnaire/create');
	}
	
	function create() {
	
		if ($this->is_post) {
		
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('email', 'email', 'valid_email|required');
			$this->form_validation->set_rules('name', 'question list name', 'required');
			
			if ($this->form_validation->run()) {
				if (($qst = $this->questionnaire_mod->store()) !== FALSE) {
					$this->easynotify->add_notification('Your questionaire was sucessfully created, we have sent you the questionnaire link to your email address. The URL for your new questionnaire is:<br>'.site_url('q/'.$qst['slug']));
					
					$this->_email(set_value('email'), 'Questionnaire created', 'Questionnaire '.set_value('name').' has been created. The URL is '.site_url('q/'.$qst['slug']) . '. To see answers to your questionnaire you can use this <a href="'.site_url('report/'.$qst['slug'].'/'.$qst['report_pass']).'">link</a>.');
					
					redirect('q/'.$qst['slug']);
					
				}
			} else {
				$this->easyhead->add_js('app/questionnaire');
				$this->easyhead->set_title('Create a questionnaire');
				$this->form_validation->notify('qst-form');
				
				$this->load->view('elements/header');
				$this->load->view('pages/questionnaire_create');
				$this->load->view('elements/footer');
			}
		
		} else {
			$this->easyhead->add_js('app/questionnaire');
			$this->easyhead->set_title('Create a questionnaire');
			
			$this->load->view('elements/header');
			$this->load->view('pages/questionnaire_create');
			$this->load->view('elements/footer');
		}
	
	}
	
	
	function show($slug = '', $question = 1) {
		if (! $slug) {show_404();}
		$data['questionnaire'] = $this->questionnaire_mod->get_by_slug($slug);
		if (! $data['questionnaire']) {show_404();}
		
		$this->easyhead->set_title($data['questionnaire']->qst_name);
		$this->easyhead->add_css('app/questionnaire');
		$this->easyhead->add_js('app/qst');
		
		$data['question'] = $this->questionnaire_mod->load_questions($data['questionnaire']->id, $question);
		
		//session started, continue
		$session = $this->questionnaire_mod->get_session($this->session->userdata('participant_email'), $data['questionnaire']->id);
		
		//participant email not specified
		if ($session === FALSE) {
			
			//back to first question if not started the session
			if ($question > 1) {
				redirect("q/$slug");
			}
			
			if (! $this->is_post) {
				$this->easyhead->add_js('app/login');
				$this->load->view('elements/header');
				$this->load->view('pages/questionnaire_start', $data);
				$this->load->view('elements/footer');
			} else {
			
				//start a new questionnaire
			
				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('email', 'Participant email', 'required|valid_email');
				
				if ($this->form_validation->run()) {
					//questionnaire start session
					$this->questionnaire_mod->start_session($this->input->post('email'), $data['questionnaire']->id);
					redirect("q/$slug");
				} else {
				
					$this->easynotify->add_warning('Please supply a valid email address');
				
					$this->form_validation->notify('qst-start');
					
					$this->load->view('elements/header');
					$this->load->view('pages/questionnaire_start', $data);
					$this->load->view('elements/footer');
					
				}
			
				
			}
			
		} else {
			
			if (! $this->is_post) {
				//usually the first question, possibly continued session
				$data['session'] = $session;
				
				if ($question != $session->current_question) {
					redirect("q/$slug/".$session->current_question);
				}
				
				$this->load->view('elements/header');
				$this->load->view('pages/questionnaire', $data);
				$this->load->view('elements/footer');
			} else {
				
				//store user answer and update session question #
				$res = $this->questionnaire_mod->store_answer($session, $this->input->post('question_id'), $this->input->post('selected'));
				
				if ($res != FALSE) {
					//answer stored, go to next question
					$res = $this->questionnaire_mod->next_question($session);
					
					if ($res === TRUE) {
						redirect("q/$slug/".($session->current_question + 1));
					} else {
						//finished
						$this->load->view('elements/header');
						$this->load->view('pages/questionnaire_finished', $data);
						$this->load->view('elements/footer');
					}
					
				} else {
					//error, reload
					redirect(current_url());
				}
				
			}
			

		}
		
		//$data['questions'] = $this->questionnaire_mod->load_questions($data['questionnaire']->id);
		
		
		

		
	}
	
	//report for a questionnaire
	function report($slug = '', $pass = '') {
	
		$data['questionnaire'] = $this->questionnaire_mod->get_by_slug($slug);
		
		if ($data['questionnaire'] === FALSE) {show_404();}
		
		$data['report'] = $this->questionnaire_mod->report($data['questionnaire']->id, $pass);
		$data['question_count'] = $this->questionnaire_mod->question_count($data['questionnaire']->id);
	
		$this->load->view('elements/header');
		$this->load->view('pages/questionnaire_report', $data);
		$this->load->view('elements/footer');
	}

}

?>
