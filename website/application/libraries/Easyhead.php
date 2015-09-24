<?php

//v1.0
//julijan

class Easyhead {

	private $css, $js, $meta = array(), $custom = '';
	
	public $title, $title_prefix, $title_suffix;
	
	public $css_dir = 'assets/css', $js_dir = 'assets/js';
	
	function __construct() {
		
		
		$this->ci = &get_instance();
		$this->ci->load->library('session');
		
		$load_data = $this->ci->session->flashdata('easyhead-data');
		
		//load data if stored
		if (is_object($load_data)) {
			
			$this->css = $load_data->css;
			$this->js = $load_data->js;
			$this->custom = $load_data->custom;
			$this->meta = $load_data->meta;
			$this->title = $load_data->title;
			$this->title_prefix = $load_data->title_prefix;
			$this->title_suffix = $load_data->title_suffix;
		} else {
			$this->js = array();
			$this->css = array();
		}
		
	}

	/*
	 * Add a CSS file(s) to be loaded
	 * $css - array/string
	 * returns void
	 * */
	function add_css() {
		
		if ( ! isset($this->css)) {$this->css = array();}
		
		for ($i=0; $i<func_num_args(); $i++){
			$css = func_get_arg($i);
			if (is_array($css)) {
				$this->css = array_merge($this->css, $css);
			} else {
				$this->css[] = $css;
			}
		}
	}
	
	/*
	 * Add a JS file(s) to be loaded
	 * $js - array/string
	 * returns void
	 * */
	function add_js() {
		if ( ! isset($this->js)) {$this->js = array();}
		
		for ($i=0; $i<func_num_args(); $i++){
			$js = func_get_arg($i);
			if (is_array($js)) {
				$this->js = array_merge($this->js, $js);
			} else {
				$this->js[] = $js;
			}
		}
	}
	
	// adds both css/jss, useful if same name/path for both files so you can do it with one call
	function add_both($name) {
		$this->add_css($name);
		$this->add_js($name);
	}

	
	/*
	 * Remove a CSS from the CSS array
	 * $css - array/string
	 * returns void
	 * */
	function remove_css($css) {
		$css = (array)$css;
		$this->css = array_diff($this->css, $css);
	}
	
	/*
	 * Remove a JS file from the JS array
	 * $js - array/string
	 * returns void
	 * */
	function remove_js($js) {
		$js = (array)$js;
		$this->js = array_diff($this->js, $js);
	}
	
	/*
	 * Set a meta tag name => val
	 * $k - meta name you want to set (charset meta works too)
	 * $v - meta value
	 * */
	function set_meta($k, $v) {
		$this->meta[$k] = $v;
	}
	
	/*
	 * Unset a meta tag
	 * */
	function unset_meta($k) {
		unset($this->meta[$k]);
	}
	
	/*
	 * Append a custom string to the header
	 * */
	function add_custom($str) {
		$this->custom .= $str;
	} 
	
	
	/*
	 * Renders the meta tags as string
	 * */
	function render_meta() {
		$meta = '';
		foreach ($this->meta as $k => $v){
			if ($k == 'charset') {
				$meta .= "<meta charset=\"$v\" />\n";
			} else {
				$meta .= "<meta name=\"$k\" content=\"$v\" />\n";
			}
		}
		
		return $meta;
	}
	
	
	/*
	 * Set the title (title tags will be automatically set)
	 * $title - string
	 * returns void
	 * */
	function set_title($title) {
		$this->title = $title;
	}
	
	/*
	 * Set the title suffix, also include the separator
	 * $suffix - string
	 * returns void
	 * */
	function set_title_suffix($suffix) {
		$this->title_suffix = $suffix;
	}
	
	/*
	 * Set the title prefix, also include the separator
	 * $prefix - string
	 * returns void
	 * */
	function set_title_prefix($prefix) {
		$this->title_prefix = $prefix;
	}
	
	
	/*
	 * Store flash if want to pass for next reload
	 * */
	function store_flash() {
		$data = new stdClass;
		$data->css = $this->css;
		$data->js = $this->js;
		$data->meta = $this->meta;
		$data->custom = $this->custom;
		$data->title = $this->title;
		$data->title_prefix = $this->title_prefix;
		$data->title_suffix = $this->title_suffix;

		$this->ci->session->set_flashdata('easyhead-data', $data);
	}
	
	
	/*
	 * Renders the header string including title (with prefix and suffix, and the CSS/JS loading tags)
	 * returns string
	 * */
	function render() {
	
		//make sure we don't load any files twice
		$this->css = array_unique($this->css);
		$this->js = array_unique($this->js);
	
		$res = "<title>{$this->title_prefix}{$this->title}{$this->title_suffix}</title>\n" .
				$this->render_meta();
		
		if (isset($this->css)) {
			foreach ($this->css as $css){
				$res .= '<link rel="StyleSheet" type="text/css" href="'.base_url($this->css_dir . "/$css.css").'">'."\n";
			}
		}
		
		if (isset($this->js)) {
			foreach ($this->js as $js){
				$res .= '<script type="text/javascript" src="'.base_url($this->js_dir . "/$js.js").'"></script>'."\n";
			}
		}
		
		$res .= $this->custom;
		
		return $res;
	}

}

?>
