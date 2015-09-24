<?php

class Questionnaire_mod extends MY_Model {

	function store() {
	
		if (! $this->is_post) {return FALSE;}
		
		$answers = $this->input->post('answers');
		
		$slug = $this->_generateSlug();
		//store questionnaire
		$questionnaire = array(
			'email' => $this->input->post('email'),
			'qst_name' => $this->input->post('name'),
			'slug' => $slug,
			'report_pass' => $this->_generatePass()
		);
		$this->db->insert('questionnaires', $questionnaire);
		
		$qst_id = $this->db->insert_id();
		
		$questionnaire['id'] = $qst_id;
		
		foreach ($this->input->post('questions') as $q_no => $q) {
		
			$question = array(
				'question' => '',
				'question_number' => false,
				'answers' => array()
			);
			
			if ($q) {
				$question['question'] = $q;
				$question['question_number'] = $q_no;
			} else {
				//skip if empty question
				continue;
			}
			
			if (is_array($answers[$q_no])) {
				foreach ($answers[$q_no] as $answer_no => $answer) {
					if ($answer) {
						$answer = array(
							'answer' => $answer,
							'answer_number' => $answer_no
						);
						$question['answers'][] = $answer;
					} else {
						//skip empty answer
						continue;
					}
				}
			}
			
			//store the question
			$this->db->insert('questions', array(
				'questionnaire_id' => $qst_id,
				'question' => $question['question'],
				'question_number' => $question['question_number']
			));
			
			$question_id = $this->db->insert_id();
			
			//store the answers
			foreach ($question['answers'] as $answer) {
				$answer['question_id'] = $question_id;
				$this->db->insert('answers', $answer);
			}
		
		}
		
		return $questionnaire;
	
	}
	
	function get_by_slug($slug) {
		$res = $this->db->where('slug', $slug)->get('questionnaires');
		
		if ($res->num_rows() > 0) {
			return $res->row();
		} else {
			return FALSE;
		}
		
	}
	
	//load questions or question #n from given questionnaire
	//load question(s) for the given questionnaire_id ($qst_id)
	function load_questions($qst_id, $question_no = FALSE) {

		$this->db->where('questionnaire_id', $qst_id);
		
		
		//load a single question
		if ($question_no !== FALSE) {
			$this->db->where('question_number', $question_no);
		}
		
		$questions = $this->db->get('questions')->result();
		
		foreach ($questions as &$question) {
			$question->answers = $this->load_answers($question->id);
		}
		
		return ($question_no !== FALSE ? $questions[0] : $questions);
		
	}
	
	function question_by_id($question_id) {
		$question = $this->db->where('id', $question_id)->get('questions')->row();
		$question->answers = $this->load_answers($question->id);
		return $question;
	}
	
	function question_count($qst_id) {
		return $this->db->where('questionnaire_id', $qst_id)->count_all_results('questions');
	}
	
	function store_answer($session, $question_id, $answers) {
	
		$question = $this->question_by_id($question_id);
	
		//make sure question belongs to questionnaire id from session
		if ($question->questionnaire_id != $session->questionnaire_id) {
			$this->easynotify->add_error('Answer not saved, please try again, err #1');
			return FALSE;
		}
	
		//$answers can be text if no offered answers
		if (count($question->answers) == 0) {
			//textual answer
			$answer = array(
				'session_id' => $session->id,
				'participant_email' => $session->participant_email,
				'question_id' => $question_id,
				'answer_id' => 0,
				'answer_text' => $answers['text']
			);
			
			$this->db->insert('user_answers', $answer);
			
			return $this->db->insert_id();
			
		} else {
			//make sure selected answers belong to this question
			$err = $this->db->where('question_id !=', $question_id)->where_in('id', $answers)->count_all_results('answers');
			
			//echo $this->db->last_query(); die();
			
			if ($err > 0) {
				$this->easynotify->add_error('Answer not saved, please try again, err #2');
				return FALSE;
			}
			
			//check session?
			
			//all good, store
			$ins = array();
			
			foreach ($answers as $answer) {
				$answer_data = $this->answer_by_id($answer);
				array_push($ins, array(
					'session_id' => $session->id,
					'participant_email' => $session->participant_email,
					'question_id' => $question_id,
					'answer_id' => $answer,
					'answer_text' => $answer_data->answer
				));
			}
			
			$this->db->insert_batch('user_answers', $ins);
			
			return TRUE;
			
		}
	
	}
	
	function load_answers($question_id) {
		return $this->db
		->where('question_id', $question_id)
		->order_by('answer_number')
		->get('answers')
		->result();
	}
	
	function answer_by_id($answer_id) {
		return $this->db->where('id', $answer_id)->get('answers')->row();
	}
	
	//when answer is stored this method is called
	function next_question($session) {
		
		if ($session->current_question == $this->question_count($session->questionnaire_id)) {
			//questionnaire finished
			$this->easynotify->add_notification('You have completed this questionnaire. Thanks for participating.');
			$this->update_session($session->id, array(
				'finished' => 1,
				'end_date' => date('Y-m-d H:i:s')
			));
			return FALSE;
		} else {
			//go to next question
			$question_no = $session->current_question + 1;
			$this->update_session($session->id, array(
				'current_question' => $question_no
			));
			return TRUE;
		}
		
	}
	
	//questionnaire session
	function start_session($email, $qst_id) {
	
		$email = trim($email);
	
		$current_session = $this->get_session($email, $qst_id);
	
		if ($current_session === FALSE) {
			$sess['participant_email'] = $email;
			$sess['questionnaire_id'] = $qst_id;
			$sess['start_date'] = date('Y-m-d H:i:s');
			$sess['current_question'] = 1;
			$this->db->insert('qst_sessions', $sess);
			$sess['id'] = $this->db->insert_id();
			$sess = (object)$sess;
		} else {
			$sess = $current_session;
		}
		
		$this->session->set_userdata(array('participant_email' => $sess->participant_email));
		
		return $sess;
		
	}
	
	function get_session($email, $qst_id) {
		$sess = $this->db
		->where('participant_email', $email)
		->where('questionnaire_id', $qst_id)
		->where('finished', 0)
		->get('qst_sessions');
		
		if ($sess->num_rows() == 0) {
			return FALSE;
		} else {
			return $sess->row();
		}
	}
	
	function update_session($sess_id, $data) {
		$this->db->where('id', $sess_id)->update('qst_sessions', $data);
		return $this->db->affected_rows();
	}
	
	function report($qst_id, $pass = '') {
		if ($this->db->where('id', $qst_id)->where('report_pass', $pass)->count_all_results('questionnaires') == 0) {
			$this->easynotify->add_error("Incorrect password, you don't have permissions to access this report");
			return FALSE;
		}
		
		$report = $this->db->query("SELECT question, question_number, answer_text, COUNT(*) AS totals FROM user_answers LEFT JOIN questions q ON q.id = user_answers.question_id WHERE q.questionnaire_id = $qst_id GROUP BY user_answers.answer_id, LOWER(user_answers.answer_text) ORDER BY question_number, totals DESC")->result();
		
		$res = array();
		
		foreach ($report as $r) {
			if (! isset($res[$r->question_number])) {
				$res[$r->question_number] = new stdClass;
				$res[$r->question_number]->data = array();
			}
			$res[$r->question_number]->question = $r->question;
			$res[$r->question_number]->data[] = $r;
		}
		
		$total_questions = $this->question_count($qst_id);
		
		for ($q = 1; $q < $total_questions; $q++) {
			if (! isset($res[$q])) {
				//question with textual answers
				error_log($q);
				$res[$q] = $this->db->query("SELECT a.* FROM questions q LEFT JOIN answers a ON a.question_id = q.id WHERE q.questionnaire_id = $qst AND q.question_number = $q")->result();
			}
		}
		
		return $res;
		
	}
	
	function _generateSlug() {
		$this->load->helper('string');
		do {
			$slug = random_string('alnum', 8);
		} while($this->db->where('slug', $slug)->count_all_results('questionnaires') > 0);
		return $slug;
	}
	
	function _generatePass() {
		$this->load->helper('string');
		return random_string('alnum', 12);
	}

}

?>
