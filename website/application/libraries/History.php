<?php

class History {

	public $sess_key = 'historyclass';

	private $history;
	
	public $max_items = 15; //how many steps back can you go

	function __construct() {
		if (! session_id()) {
			@session_start();
		}
		
		$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		
		if (! isset($_SESSION[$this->sess_key])) {
			$this->history = array();
		} else {
			$this->history = $_SESSION[$this->sess_key];
		}
		
		if (! $is_ajax) {
		
			if (count($this->history) > 0) {
				//check if same as last (reload)
				if ($this->history[0] !== $this->current_url()) {
					//store current page
					array_unshift($this->history, $this->current_url());
				}
			} else {
				array_unshift($this->history, $this->current_url());
			}
		
		}
		
		//maintain item count limit
		while (count($this->history) > $this->max_items) {
			array_pop($this->history);
		}
		
		$_SESSION[$this->sess_key] = $this->history;
		
	}
	
	public function back($steps = 1, $return_url = FALSE) {
		if (isset($this->history[$steps])) {
			$url = $this->history[$steps];
		} else {
			return FALSE;
		}
		
		if ($return_url) {
			return $url;
		}
		
		header('Location: '.$url);
		die();
	}
	
	private function current_url() {
		return current_url();
	}


}

?>
