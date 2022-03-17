<div class="form-block">
    <input type="<?= ($name == 'password') ?   'password' : (($name == 'date') ? 'date' : 'text') ?>" name="<?= $name ?>" id="<?= $name ?>" class="text-input valid-input" placeholder="<?= ucfirst($name) ?>" required />
    <small class="invalid-text">
        Please enter a valid <?= $name ?></small>
</div>