<div class="form-block choice-container">
    <input type="radio" name="q-<?= $question_id ?>" value="c-<?= $choice_id ?>" class="question-choice" <?= $reviewMode ? "disabled" : "" ?> <?= $selected ? 'checked' : "" ?>>
    <span class="choice-text"><?= $choice_text ?></span> <?= (($reviewMode) ? ($is_correct ? "✅" : "❌") : ' ') ?>
    <br>
</div>