<div class="login-container">
    <h1><?= get_class($cls) ?></h1>
    <form action="<?= URL . "Api/Add" ?>" method="post" class="login-form">
        <input name="schemaClass" type="text" id="schemaClass" class="text-input valid-input" style="display:none" required value="<?= get_class($cls) ?>" />
        <?php
        foreach ($inputs as $field => $func) {
            if ($func == 'text') {
                text_input($field);
            } elseif ($func == 'select') {
                select_input($field, $SELECT_OPTIONS[$field], str_replace("_id", "", ucfirst($field)));
            }
        }
        ?>
        <div class="form-block">
            <button type="submit" id="submit-btn">
                <?= $cls->CRUD_Terms['create'] . ' ' . get_class($cls) ?>
                <svg id="spinner" viewBox="0 0 50 50">
                    <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                </svg>
            </button>
        </div>
    </form>
</div>