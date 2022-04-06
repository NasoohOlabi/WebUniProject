<div class="form-block">
    <input name="<?= $input_name ?>" type="text" list="<?= $input_name ?>" class="text-input" />
    <datalist id="<?= $input_name ?>">
        <?php
        foreach ($SELECT_OPTIONS as $id => $val) {
            echo "<option label=\"$id\" value=\"$val\"></option>";
        }
        ?>
    </datalist>
</div>