<div class="form-block">
    <select name="<?= $input_name ?>" class="form-select valid-input" aria-label="<?= $place_holder ?>">
        <option value="" disabled selected hidden>
            <?= $place_holder ?>
        </option>
        <?php
        foreach ($options as $opt) {
            echo "<option value=\"$opt\">{$ucfirst($opt)}</option>";
        }
        ?>
    </select>
</div>