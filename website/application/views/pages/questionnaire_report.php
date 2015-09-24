<h1>Report for <?=$questionnaire->qst_name?></h1>

<p>URL: <?= anchor("q/{$questionnaire->slug}")?></p>

<?php

for ($i = 1; $i < $question_count + 1; $i++) {
	$rep = $report[$i];
	
	echo "<h3>$i) {$rep->question}</h3>";
	
	echo '<ul class="list-group">';
	foreach ($rep->data as $r) {
		echo '<li class="list-group-item">
			<p>'.$r->answer_text.' <span class="label label-default pull-right">'.$r->totals.'</span></p>
		</li>';
	}
	echo '</ul>';
	
}


?>
