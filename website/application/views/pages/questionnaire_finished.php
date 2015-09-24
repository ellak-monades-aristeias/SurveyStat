<h1>Questionnaire finished</h1>
<p>Thanks for participating</p>


<p><?=anchor('questionnaire/create', 'Create a questionnaire')?></p>


<!--<div class="fb-share-button" data-href="<?=current_url()?>" data-layout="icon"></div>-->


<a class="btn btn-facebook" target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url())?>">
	<i class="fa fa-facebook"></i>
</a>


<a href="http://twitter.com/home?status=<?=urlencode(current_url())?>" class="btn btn-twitter" target="_blank">
	<i class="fa fa-twitter"></i>
</a>

<a href="https://plus.google.com/share?url=<?=urlencode(current_url())?>" class="btn btn-google-plus" target="_blank">
	<i class="fa fa-google-plus"></i>
</a>
