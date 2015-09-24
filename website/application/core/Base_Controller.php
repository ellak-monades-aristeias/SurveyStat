<?php

//Provides basic ACL control

class Public_Controller extends MY_Controller {

	function __construct() {
		parent::__construct();
		if (! isset($this->user_data)) {
			$this->user_data = array();
		}
	}

}

class Users_Controller extends MY_Controller {

	function __construct() {
		parent::__construct();
		if (! $this->is_user) {
			redirect('auth/login');
		}
		
	}

}

class Admin_Controller extends MY_Controller {

	function __construct() {
		parent::__construct();
		if (! isset($this->user_data)) {redirect('auth/login');}
		if ($this->user_data['role'] != 'admin') {
			redirect('login');
			die();
		}

	}

}

?>
