<?php
$username = $_SESSION['username']
?>
<div id="main-content" class="inlineBlock">

    <h1 id="welcome-header"><?= LANGUAGE::t('Welcome To The University Website') ?></h1>
    <hr>
    <p id="login-status-bar">You're logged in as <span><?php echo $username ?></span>. <a href="<?= URL ?>users/logout">Logout?</a></p>

</div>