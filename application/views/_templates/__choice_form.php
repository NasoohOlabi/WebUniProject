<div class="add-form-container">
	<div class="form-block">
		<h1><?= Language::t(get_class($cls)) ?></h1>
	</div>
	<form action="<?= URL . "Api/create/" . get_class($cls) ?>" method="post" class="login-form">
		<div class="form-block" style="position: relative;">
			<input type="text" name="text" id="text" class="text-input valid-input" placeholder="<?= Language::t('Text') ?>" required style="width:calc(calc(85% - 2 * 0.75rem) - 2px);display:inline-block" />
			<input type="radio" name="is_correct" id="is_correct" class="text-input valid-input" placeholder="<?= Language::t(humanize("is_correct")) ?>" required style="width:calc(calc(15% - 0.5rem) - 2px);margin-left:0.5rem;height:100%;display:inline-block;position:absolute" onclick="radio(this)" />
			<small class="invalid-text">
				<?= Language::t("Please enter a valid Text") ?></small>
		</div>
		<?php
		if (isset($submitBtnNeeded) && $submitBtnNeeded)
			echo '
        <div class="form-block">
            <button type="submit" id="submit-btn">
                ' . Language::t($cls->get_CRUD_Terms()['create']) . ' ' . Language::t(get_class($cls)) . '
                <svg id="spinner" viewBox="0 0 50 50">
                    <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                </svg>
            </button>
        </div>'
		?>
	</form>
</div>