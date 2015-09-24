<?php 

class Siteconf extends MY_Model {

	var $table_name = 'site_conf';

	function load_all() {
		$data = $this->db->get($this->table_name)->result();
		$conf = array();
		foreach ($data as $row) {
			$conf[$row->key] = $row->value;
		}
		
		return $conf;
	}
	
	function get($key = '') {
		if ($this->setting_exists($key)) {
			return $this->db->where('key', $key)->get($this->table_name)->row()->value;
		} else {
			return '';
		}
	}
	
	function set($key, $val = '') {
	
		if (is_string($key)) {
			if ($this->setting_exists($key)) {
				return $this->db->where('key', $key)->set('value', $val)->update($this->table_name);
			} else {
				$conf['key'] = $key;
				$conf['value'] = $val;
				return $this->db->insert($this->table_name, $conf);
			}
		} else {
			if (! is_array($key)) {return FALSE;}
			
			//set using array
			foreach ($key as $key => $val) {
				if (is_string($key)) {
					$this->set($key, $val);
				}
			}
			
			return TRUE;
			
		}

	}
	
	function setting_exists($key) {
		return $this->db->where('key', $key)->count_all_results($this->table_name);
	}

}

?>
