<div id="main-content" class="inlineBlock">

    <h1 id="welcome-header"><?= Language::t('Welcome To The University Website') ?></h1>
    <hr>
    <p id="login-status-bar">You're logged in as <span><?php echo $username ?></span>. <a href="<?= URL ?>users/logout">Logout?</a></p>
    <?php if ($content) require_once $content ?>
</div>