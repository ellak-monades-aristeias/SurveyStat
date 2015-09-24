var qst = {

	questions : 1,
	
	questionsEl : false,

	init : function() {
		qst.questionsEl = $('#questions');
		
		$('.btn-add-question').on('click', qst.addQuestion);
		$('#qst-form').on('submit', function(e) {
		
			var err = false;
		
			qst.questionsEl.find('.qst').each(function() {
				if ($(this).val() == '') {
					err = true;
					$(this).parents('.form-group').addClass('has-error');
				}
			});
			if (err == true) {
				if (! confirm('Some of the questions is left empty, click Ok to Finish and ignore those questions, or click Cancel to continue editing')) {
					e.preventDefault();
					e.stopPropagation();
				}
			}
		
		});
		
		if (qst.questionsEl.find('.question').length == 0) {
			qst.addQuestion();
		} else {
		
			//allow adding more answers
			qst.questionsEl.find('.btn-add-answer').on('click', function() {
				var qEl = $(this).parents('.question');
				
				var answer = qEl.find('.qst-answer:last').clone(true);
				answer.find(':text').val('');
				answer.insertAfter(qEl.find('.qst-answer:last'));
			
			});
		
		}
		
	},

	addQuestion : function() {
	
		var el = $('#question-proto').clone(true);
		
		el.removeClass('hidden');
		el.removeProp('id');
		
		el = qst.updateQuestionNumber(el, qst.questions);
		
		//add answer
		el.find('.btn-add-answer').on('click', function() {
			var qEl = $(this).parents('.question');
			
			var answer = qEl.find('.qst-answer:last').clone(true);
			answer.find(':text').val('');
			answer.insertAfter(qEl.find('.qst-answer:last'));
			
		});
		
		qst.questionsEl.append(el);
		
		qst.questions++;
	
	},
	
	updateQuestionNumber : function(el, newNo) {
		el.attr('data-question', newNo);
		el.find('.qst-label').prop('for', 'qst-'+newNo);
		el.find('.question-no').text(newNo);
		el.find('.qst').prop('name', 'questions['+newNo+']');
		el.find('.qst-answer input').prop('name', 'answers['+newNo+'][]');
		return el;
	}

}

$(document).ready(function() {
	qst.init();
});
