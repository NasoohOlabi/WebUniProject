<div class="form-block">
    <select name="<?= $input_name ?>" class="form-select valid-input" <?= ($place_holder) ? "aria-label=\"$place_holder\"" : '' ?>>
        <?= ($place_holder) ? "<option value=\"\" disabled selected hidden>
            $place_holder
        </option>" : '' ?>

        <?php
        foreach ($SELECT_OPTIONS as $id => $val) {
            echo "<option value=\"$id\">$val</option>";
        }
        ?>
    </select>
</div>