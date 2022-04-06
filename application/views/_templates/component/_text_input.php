<div class="form-block">
    <input type="<?= ($name == 'password') ?   'password' : 'text' ?>" name="<?= $name ?>" id="<?= $name ?>" class="text-input valid-input" placeholder="<?= Language::t(humanize($name)) ?>" required />
    <small class="invalid-text">
        <?= Language::t("Please enter a valid $name") ?></small>
</div>