<div class="form-block">
    <select name="<?= $input_name ?>" class="form-select valid-input" aria-label="<?= $place_holder ?>">
        <option value="" disabled selected hidden>
            <?= $place_holder ?>
        </option>
        <?php
        foreach ($options as $opt) {
            $v1 = '';
            $v2 = '';
            foreach ($opt as $v) {
                if ($v1 == '')
                    $v1 = $v;
                elseif ($v2 == '') {
                    $v2 = $v;
                    break;
                }
            }
            echo "<option value=\"$v1\">" . ucfirst($v2) . "</option>";
        }
        ?>
    </select>
</div>