<?php

class MY_Model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function error($e) {
		$error = new stdClass;
		$error->message = $e;
		$error->error = 1;
		return $error;
	}
	
	//standard create/insert function
	function insert($table, $data = array()) {
		
		$this->db->insert($table, $data);
		
		$data['id'] = $this->db->insert_id();
		
		return (object)$data;
		
	}
	
	function updateTable($table, $id, $data = array()) {
		$this->db
		->where('id', $id)
		->update($table, $data);
		return $this->db->affected_rows() > 0;
	}
	
	function deleteFrom($table, $id = FALSE) {
		
		if (! $id) {
			if (isset($this->data)) {
				$id = $this->data;
			} else {
				return FALSE;
			}
		}
		
		$this->db->where('id', $id)->delete($table);
		return $this->db->affected_rows();
		
	}
	
	function load($table, $id) {
		if ($this->db->where('id', $id)->count_all_results($table) > 0) {
			$this->data = $this->db->where('id', $id)->get($table)->row();
			return TRUE;
		}
		return FALSE;
	}
	
	function _filterArray($arr, $field) {
		$res = array();
		foreach ($arr as $v) {
		
			if (is_object($v)) {$v = (array)$v;}
		
			if (isset($v[$field])) {
				$res[] = $v[$field];
			}
		}
		return $res;
	}
	
	function _email($to, $subject, $body, $from = FALSE, $attachments = FALSE) {
	
		$this->load->library('email');
		
		if ($from) {
			$this->email->from($from);
		} else {
			$this->email->from($this->siteconf->get('admin_email'));
		}
		
		if (is_array($to)) {
			if (isset($to['to'])) {
				if (is_array($to['to'])) {
					$to['to'] = implode(',', $to['to']);
				}
				$this->email->to($to['to']);
			}
			
			if (isset($to['bcc'])) {
				if (is_array($to['bcc'])) {
					$to['bcc'] = implode(',', $to['bcc']);
				}
				$this->email->bcc($to['bcc']);
			}
			
			if (isset($to['cc'])) {
				if (is_array($to['cc'])) {
					$to['cc'] = implode(',', $to['cc']);
				}
				$this->email->cc($to['cc']);
			}
			
			if (! isset($to['to']) && ! isset($to['bcc']) && ! isset($to['cc'])) {
				//it's an array of emails "to"
				if (is_array($to)) {$to = implode(',', $to);}
				$this->email->to($to);
			}
			
		} else {
			//most likely a string email
			$this->email->to($to);
		}
		$this->email->subject($subject);
		$this->email->message($body);
		
		//attachments
		if ($attachments) {
			if (is_array($attachments)) {
				foreach($attachments as $a) {
					$this->email->attach($a);
				}
			} else {
				$this->email->attach($attachments);
			}
		}
		
		$this->email->send();
	
	}

}

?>
