<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$profile_pic = $_SESSION['user']->profile_picture;
// var_dump($_SESSION['user']);
$has_picture = ($profile_pic == null || $profile_pic == '' ? false : true);
$role = $_SESSION['user']->role->name;
$isRootAdmin = ($role == 'ROOT::ADMIN' ? true : false);
$user_first_name = $_SESSION['user']->first_name;
$user_initial = strtoupper($user_first_name[0]);

$dashboard_option = '<a href="' . URL . 'DashBoard"><i class="fa fa-cogs" aria-hidden="true"></i>  ' . Language::t('Dashboard') . '</a>';

$profile_pic_style = '';

// var_dump($has_picture);

if ($has_picture == true) {
    $profile_pic_style = 'style = "background-image: url(' . URL . 'DB/ProfilePics/' . $profile_pic . ')";';
}


?>

<body>
    <script>
        var toggle = false;

        var profile_pic = document.getElementsByClassName('profile-pic')[0];

        function toggle_menu() {

            var drop_menu = document.getElementsByClassName('dropdown-content<?= (Language::$direction == 'rtl') ? '-rtl' : '' ?>')[0];
            var profile_pic = document.getElementsByClassName('profile-pic')[0];

            if (!toggle) {
                drop_menu.style.display = "block";
                profile_pic.style.boxShadow = "0 3px 10px rgb(0 0 0)";
                toggle = true;
            } else {
                drop_menu.style.display = "none";
                profile_pic.style.boxShadow = "0 0 0";
                toggle = false;
            }
        }


        if (profile_pic && profile_pic.addEventListener)
            profile_pic.addEventListener('click', function(event) {
                if (!toggle) return;
                var profile_pic = document.getElementsByClassName('profile-pic')[0];
                var drop_menu = document.getElementsByClassName('dropdown-content<?= (Language::$direction != 'rtl') ? '-rtl' : '' ?>')[0];
                var ignoreClickOnMeElement = document.getElementById('menu');
                var isClickInsideElement = ignoreClickOnMeElement.contains(event.target) || profile_pic.contains(event.target);
                if (!isClickInsideElement) {
                    drop_menu.style.display = "none";
                    profile_pic.style.boxShadow = "0 0 0";
                    toggle = false;
                }
            });
    </script>
    <header>
        <a href="<?= URL ?>home" id="logo">
            <div id="logo">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="logo-img" viewBox="0 0 405 393">
                    <image x="110" y="77" width="185" height="208" xlink:href="data:img/png;base64,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" />
                </svg>
                <div id="logo-separator"></div>
                <div id="logo-text">MNU</div>
            </div>
        </a>
        <nav>
            <div class="dropdown<?= (Language::$direction == 'rtl') ? '-rtl' : '' ?>">
                <div class="profile-pic dropbtn" onclick="toggle_menu()" id="dropbtn" <?php echo $profile_pic_style ?>><?php if (!$has_picture) echo $user_initial ?></div>
                <div class="dropdown-content<?= (Language::$direction == 'rtl') ? '-rtl' : '' ?>" id="menu">

                    <a href="<?= URL ?>" class="link">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        <?= Language::t('Home') ?>
                    </a>

                    <a href="<?= URL ?>users/profile" class="link">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <?= Language::t('Account') ?>
                    </a>

                    <a href="<?= URL ?>home?lang=<?= (Language::$direction == 'ltr') ? 'ar' : 'en' ?>" class=" link">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <?= (Language::$direction == 'ltr') ? Language::t('Arabic') : Language::t('English') ?>
                    </a>

                    <?php if ($isRootAdmin) echo $dashboard_option ?>

                    <a href="<?= URL ?>users/logout" class="link">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        <?= Language::t('Logout')  ?>
                    </a>
                </div>
            </div>
        </nav>

    </header>


    <!-- <a href="#" onclick="
                    let d =new Date(); 
                    d.setTime(d.getTime() + 60 * 24 * 60 * 60 * 1000);
                    document.cookie = 'lang=<?= (Language::$direction == 'rtl') ? 'en' : 'ar' ?>;' + 'expires=' + d.toUTCString() + ';path=/' ; " class=" link">
        <i class="fa fa-user" aria-hidden="true"></i>
        <?= (Language::$direction == 'ltr') ? Language::t('Arabic') : Language::t('English') ?>
    </a> -->