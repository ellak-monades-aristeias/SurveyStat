<div class="row">
	<div class="col-xs-12 col-md-7">
		<h4><?=$questionnaire->qst_name?></h4>
	</div>
	
	<div class="col-xs-12 col-md-5">
		<br>
		<p>Participant email: <b><?=$this->session->userdata('participant_email')?></b></p>
	</div>
</div>



<br>




<!-- current question -->

<h3 class="qst-question"><?=$question->question?></h3>

<form action="<?=current_url()?>" method="POST">

	<?php if (count($question->answers) > 0): ?>
	
		<?php foreach ($question->answers as $answer): ?>
		
		<label class="qst-answer">
			<input type="checkbox" name="selected[]" value="<?=$answer->id?>"> <?= $answer->answer?>
		</label>
		
		<?php endforeach; ?>
	
	<?php else: ?>
	
	<textarea name="selected[text]" class="form-control"></textarea>
	
	<?php endif; ?>
	
	<input type="hidden" name="question_id" value="<?=$question->id?>">
	<input type="hidden" name="questionnaire_id" value="<?=$questionnaire->id?>">
	<input type="hidden" name="participant_email" value="<?=$session->participant_email?>">
	
	<p class="text-right">
		<button type="submit" class="btn btn-primary btn-lg">
			<span class="glyphicon glyphicon-ok"></span> Next
		</button>
	</p>

</form>

<?php /*var_dump($question);*/ ?>
