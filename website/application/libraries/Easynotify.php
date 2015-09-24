<?php

class Easynotify {

	public $errors = array(), $notifications = array(), $warnings = array();
	
	public $class_error = 'en-error';
	public $class_notification = 'en-notify';
	public $class_warning = 'en-warning';

	function __construct() {
		$this->CI = &get_instance();
		$this->CI->load->library('session');
		$this->load_session_data();
	}
	
	function add_error($e) {
		$this->errors[] = $e;
		$this->store_session();
	}
	
	function add_notification($e) {
		$this->notifications[] = $e;
		$this->store_session();
	}
	
	function add_warning($e) {
		$this->warnings[] = $e;
		$this->store_session();
	}
	
	function last_error() {
		return end($this->errors);
	}
	
	function last_notification() {
		return end($this->notifications);
	}
	
	function last_warning() {
		return end($this->warnings);
	}
	
	function errors() {
		return $this->errors;
	}
	
	function warnings() {
		return $this->warnings;
	}
	
	function notifications() {
		return $this->notifications;
	}
	
	function show_errors($return = FALSE) {
		$res = '';
		foreach ($this->errors as $e){
			$res .= "<p class='{$this->class_error}'>$e</p><br>";
		}
		
		$this->clear_errors();
		$this->store_session();
		
		if ($return) {return $res;}
		echo $res;
	}
	
	function show_warnings($return = FALSE) {
		$res = '';
		foreach ($this->warnings as $e){
			$res .= "<p class='{$this->class_warning}'>$e</p><br>";
		}
		
		$this->clear_warnings();
		$this->store_session();
		
		if ($return) {return $res;}
		echo $res;
	}
	
	function show_notifications($return = FALSE) {
		$res = '';
		foreach ($this->notifications as $e){
			$res .= "<p class='{$this->class_notification}'>$e</p><br>";
		}
		
		$this->clear_notifications();
		$this->store_session();
		
		if ($return) {return $res;}
		echo $res;
	}
	
	function show_all($return = FALSE) {
		$res = $this->show_errors(TRUE) . $this->show_warnings(TRUE) . $this->show_notifications();
		if ($return) {return $res;}
		echo $res;
	}
	
	//returns TRUE if there are any messages/errors/notifications, otherwise FALSE
	function has_any() {
		if (count($this->errors) + count($this->warnings) + count($this->notifications) == 0) {return FALSE;}
		return TRUE;
	}
	
	function clear_errors() {
		$this->errors = array();
		$this->store_session();
	}
	
	function clear_warnings() {
		$this->warnings = array();
		$this->store_session();
	}
	
	function clear_notifications() {
		$this->notifications = array();
		$this->store_session();
	}
	
	//load everything from session
	private function load_session_data() {
		//if exists - load, otherwise create a session object
		//var_dump($this->CI->session->userdata('easynotify'));
		if (($data = $this->CI->session->userdata('easynotify')) != FALSE) {
			$this->warnings = $data->warnings;
			$this->errors = $data->errors;
			$this->notifications = $data->notifications;
		} else {
			$this->store_session();
		}
	}
	
	private function store_session() {
		$data = new stdClass;
		$data->warnings = $this->warnings;
		$data->errors = $this->errors;
		$data->notifications = $this->notifications;
		$this->CI->session->set_userdata('easynotify', $data);
	}

}

?>
