//make bootstrap+CI error reporting easier, set form errors automatically
//having the form errors array
function displayErrors(form) {
	if (typeof form_errors === 'undefined') {
		return false;
	}
	
	if (typeof form === 'string') {
		form = $((/^#/.test(form) ? '' : '#')+form);
	}
	
	for ( var i in form_errors) {
		$(form).find('[name="'+i+'"]').parents('.form-group').addClass('has-error');
		if ($(form).find('[name="'+i+'"]').parents('.form-group').find('p.help-text').length == 0) {
			if ($(form).find('[name="'+i+'"]:last').parent().is('label')) {
				$('<p class="help-block">'+form_errors[i]+'</p>').insertAfter($(form).find('[name="'+i+'"]:last').parent());
			} else {
				if (! $(form).find('[name="'+i+'"]:last').parent().hasClass('input-group')) {
					$('<p class="help-block">'+form_errors[i]+'</p>').insertAfter($(form).find('[name="'+i+'"]:last'));
				} else {
					$('<p class="help-block">'+form_errors[i]+'</p>').insertAfter($(form).find('[name="'+i+'"]:last').parent());
				}
			}
		}
	}
}
