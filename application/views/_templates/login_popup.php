<script src="<?= URL ?>public/js/popping.js"></script>
<div id="main-container-popup" onclick="outerClicked(event,this)">
    <noscript><?= LANGUAGE::t("It seems that you don't have javascript enabled for this
        site...") ?></noscript>
    <div class="login-container">
        <h1 style="display:none"><?= LANGUAGE::t('Login') ?></h1>
        <div class="icon-container">
            <i class="fas fa-user-circle user-icon"></i>
        </div>

        <form action="<?= URL ?>users/validate" method="post" class="login-form">
            <div class="form-block">
                <input type="text" name="username" id="username" class="text-input valid-input" placeholder="<?= LANGUAGE::t('Username') ?>" required onclick="this.dir='ltr';this.placeholder=''" />
                <small class="invalid-text">
                    <?= LANGUAGE::t('Please enter a valid username') ?></small>
            </div>
            <div class="form-block">
                <input type="password" name="password" id="password" class="text-input valid-input" placeholder="<?= LANGUAGE::t('Password') ?>" required onclick="this.dir='ltr';this.placeholder=''" />
                <small class="invalid-text"><?= LANGUAGE::t('Please enter a valid password') ?></small>
            </div>
            <div class="form-block">
                <button type="submit" id="submit-btn" class="default-form">
                    <?= LANGUAGE::t('Login') ?>
                    <svg id="spinner" viewBox="0 0 50 50">
                        <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>