<?php

class MY_Controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//we will use the database all the time
		$this->load->database();
		
		//site config
		$this->site_conf = $this->siteconf->load_all();
		

		$this->easyhead->add_css('bootstrap.min', 'bootstrap-social', 'font-awesome.min', 'app/style');
		
		$this->easyhead->add_js('jquery.min', 'bootstrap.min');
		

		//title preffix
		$this->easyhead->set_title_prefix($this->siteconf->get('site_name').' - ');
		
		//SEO
		$this->load->config('seo', TRUE);
		$this->easyhead->set_meta('keywords', $this->config->item('keywords', 'seo'));
		
		//correct CSS classes for notifications
		$this->easynotify->class_error = 'alert alert-danger';
		$this->easynotify->class_notification = 'alert alert-success';
		$this->easynotify->class_warning = 'alert alert-warning';

		//be aware of request method via shorthand
		$this->is_post = $_SERVER['REQUEST_METHOD'] == 'POST';
		$this->is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		
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
	
	function _create_recaptcha()
	{
		$this->load->helper('recaptcha');

		// Add custom theme so we can get only image
		$options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'tank_auth'));

		return $options.$html;
	}

}

//load the base controller
require APPPATH . 'core/Base_Controller.php';

?>
