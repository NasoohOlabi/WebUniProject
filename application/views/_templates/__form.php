<div class="add-form-container">
    <div class="form-block">
        <h1><?= get_class($cls) ?></h1>
    </div>
    <form action="<?= URL . "Api/create/" . get_class($cls) ?>" method="post" class="login-form">
        <?php
        foreach ($inputs as $field => $func) {
            if ($func === 'text') {
                text_input($field);
            } elseif ($func === 'profile_picture') {
                picture_input();
            } elseif ($func === 'date') {
                date_input($field);
            } elseif ($func === 'select') {
                select_input($field, $SELECT_OPTIONS[$field], str_replace("_id", "", ucfirst($field)));
            }
        }
        ?>
        <div class="form-block">
            <button type="submit" id="submit-btn">
                <?= $cls->get_CRUD_Terms()['create'] . ' ' . get_class($cls) ?>
                <svg id="spinner" viewBox="0 0 50 50">
                    <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                </svg>
            </button>
        </div>
    </form>
</div>