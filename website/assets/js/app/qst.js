$(document).ready(function() {

	$('.qst-answer :checkbox').prop('checked', false);

	$('.qst-answer :checkbox').on('click', function(e) {
		console.log($(this).prop('checked'));
		if ($(this).prop('checked') == true) {
			$(this).parent().addClass('active');
		} else {
			$(this).parent().removeClass('active');
		}
	});
});
