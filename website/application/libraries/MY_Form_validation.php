<?php

class MY_Form_validation extends CI_Form_validation
{

    public function error_array()
    {
        return $this->_error_array;
    }
    
    //set form errors via javascript
    //requires bootstrap.formerrors.js & easyhead
    public function notify($form_id) {
		$this->CI->easyhead->add_js('app/bootstrap.formerrors');
		$this->CI->easyhead->add_custom('<script type="text/javascript">
			var form_errors = '.json_encode($this->error_array()).';
			window.onload = function() {
			
				displayErrors("'.$form_id.'");
			
			}
		</script>');
    
    }
    
}

?>
