<div class="quiz-question-container">
	<div id="game">
		<h3 id="question"><?= $question->text  ?></h3>

		<?php
		foreach ($question->answers as $key => $choice) {
			echo "<div class=\"quiz-choice-container\">
							<p class=\"quiz-choice-prefix\">$key+1</p>
							<p class=\"quiz-choice-text\" data-number=\"$key+1\">$choice->text</p>
						</div>";
		}
		?>
	</div>
</div>