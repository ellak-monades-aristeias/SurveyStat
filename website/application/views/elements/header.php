<!DOCTYPE html>
<html>
	<head>
		<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- IE -->
		<link rel="shortcut icon" type="image/x-icon" href="<?=site_url('assets/img/favicon.ico')?>" />
		<!-- other browsers -->
		<link rel="icon" type="image/x-icon" href="<?=site_url('assets/img/favicon.ico')?>" />
		<?=$this->easyhead->render();?>
		<base href="<?=base_url()?>">
		<script type="text/javascript">
			var site_url = '<?=site_url()?>';
		</script>
	</head>

	<body>
	
		<div id="fb-root"></div>
	
		<div id="main-wrapper">
			<div id="header" class="navbar navbar-default navbar-responsive" role="navigation">
			
				<div class="container">
					<div class="row">
					
						<div class="col-xs-4">
							<a href="<?=site_url()?>" class="navbar-left" id="logo">
								<h1><?=$this->siteconf->get('site_name')?></h1>
								<!--<img id="logo" src="<?=site_url('assets/img/logo.png')?>" alt="<?=$this->siteconf->get('site_name')?>">-->
							</a>
						</div>
						
						

						
						<div class="col-xs-8">

							<div class="row header-right">
								
								<div class="col-xs-7">

									<ul class="nav navbar-nav navbar-right">
										<li>
											<?=anchor('questionnaire/create', 'Create a questionnaire')?>
										</li>
									</ul>
									
								</div>
								
								
								<div class="col-xs-5">
									
								</div>
								
							</div>	
						</div>
					
					</div>
				</div>
				
			</div>
			
			<div class="container" id="content-wrapper">

				<div class="container"><?php $this->easynotify->show_all();?></div>
