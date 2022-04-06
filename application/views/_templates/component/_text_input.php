<div class="form-block">
    <input type="<?= ($name == 'password') ?   'password' : 'text' ?>" name="<?= $name ?>" id="<?= $name ?>" class="text-input valid-input" placeholder="<?= ucfirst($name) ?>" required />
    <small class="invalid-text">
        Please enter a valid <?= $name ?></small>
</div>