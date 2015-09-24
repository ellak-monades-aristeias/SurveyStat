<form action="<?=current_url()?>" method="POST" id="qst-start">
	<div class="row">
	
		<div class="col-xs-12 col-xs-offset-0 col-md-8 col-md-offset-2">
			<div class="well">
			
				<h1 class="text-center"><?=$questionnaire->qst_name?></h1>
			
				<div class="form-group">
					<label class="control-label" for="pEmail">Participant email</label>
					<div class="input-group">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-envelope"></span>
						</div>
						<input type="email" class="form-control" name="email" id="pEmail" value="<?=set_value('email')?>">
						<div class="input-group-addon btn-facebook fb-login" title="Use email from your Facebook account">
							<i class="fa fa-facebook"></i>
						</div>
					</div>
				</div>
				
				<p class="text-center">
					<button type="submit" class="btn btn-primary btn-lg">
						<span class="glyphicon glyphicon-ok"></span> Start
					</button>
				</p>
				
			</div>
		</div>
	
	</div>
</form>
