<div class="login-container">
    <h1><?= get_class($cls) ?></h1>
    <form action="" method="post" class="login-form">
        <?php
        foreach ($inputs as $val => $func) {
            if ($func == 'text') {
                text_input($val);
            } elseif ($func == 'select') {
                select_input($val, $SELECT_OPTIONS[$val], str_replace("_id", "", ucfirst($val)));
            }
        }
        ?>
        <div class="form-block">
            <button type="submit" id="submit-btn">
                Login
                <svg id="spinner" viewBox="0 0 50 50">
                    <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                </svg>
            </button>
        </div>
    </form>
</div>