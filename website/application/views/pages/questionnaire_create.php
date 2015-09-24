<h1>New questionnaire</h1>
<br>
<form action="<?=current_url()?>" method="POST" id="qst-form">

	<div class="form-group">
	
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<label for="qEmail" class="control-label">Email</label>
				<div class="input-group">
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-envelope"></span>
					</span>
					<input type="text" class="form-control" name="email" id="qEmail" value="<?=set_value('email')?>">
				</div>
			</div>
			
			<div class="col-xs-12 col-md-6">
			
				<label for="qName" class="control-label">Question list name</label>
				<div class="input-group">
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-file"></span>
					</span>
					<input type="text" class="form-control" name="name" id="qName" value="<?=set_value('name')?>">
				</div>
			
			</div>
		</div>
	
	</div>
	
	<br>
	
	<div id="questions">
	
		<?php if ($this->input->post('questions')): ?>
		
		<?php foreach ($this->input->post('questions') as $q_no => $q): ?>
		<div class="question well" data-question="<?=$q_no?>">
		
			<div class="form-group<?= trim($q) == '' ? ' has-error' : ''?>">
				<label class="control-label" for="qst-<?=$q_no?>" class="qst-label control-label"><b>Question <span class="question-no"><?=$q_no?></span></b></label>
				<textarea class="form-control qst" name="questions[<?=$q_no?>]"><?=$q?></textarea>
			</div>
			
			<br>
			
			<p><b>Answers:</b></p>
			
			<div class="qst-answers">
				<?php $answers = $this->input->post('answers'); $answers = $answers[$q_no]; foreach($answers as $a_no => $a): ?>
				<div class="form-group qst-answer">
					<input type="text" class="form-control" name="answers[<?=$q_no?>][]" value="<?=$a?>">
				</div>
				<?php endforeach; ?>
			</div>
			
			<br>
			
			<button type="button" class="btn btn-success btn-sm btn-add-answer">
				<span class="glyphicon glyphicon-plus"></span> Add answer
			</button>
		
		</div>
		
		<?php endforeach; ?>
		
		<script type="text/javascript">
			qst.questions = <?=count($this->input->post('questions')) + 1?>;
		</script>

		<?php endif; ?>
	
	</div>
	
	<p class="text-right">
		<button type="button" class="btn btn-primary btn-add-question">
			<span class="glyphicon glyphicon-plus"></span> Add question
		</button>
		
		&nbsp;&nbsp;&nbsp;
		
		<button type="submit" class="btn btn-success btn-lg">
			<span class="glyphicon glyphicon-ok"></span> Finish
		</button>
		
	</p>
	

</form>

<div class="question well hidden" id="question-proto" data-question="">

	<div class="form-group">
		<label class="control-label" for="qst-1" class="qst-label control-label"><b>Question <span class="question-no">1</span></b></label>
		<textarea class="form-control qst" name="questions[1]"></textarea>
	</div>
	
	<br>
	
	<p><b>Answers:</b></p>
	
	<div class="qst-answers">
		<div class="form-group qst-answer">
			<input type="text" class="form-control" name="answers[1][]">
		</div>
	</div>
	
	<br>
	
	<button type="button" class="btn btn-success btn-sm btn-add-answer">
		<span class="glyphicon glyphicon-plus"></span> Add answer
	</button>

</div>
