<?php
$profile_pic = $_SESSION['user']->profile_picture;
$has_picture = ($profile_pic == null || $profile_pic == '' ? false : true);
$role = $_SESSION['user']->role->name;
$isRootAdmin = ($role == 'ROOT::ADMIN' ? true : false);
$user_first_name = $_SESSION['user']->first_name;
$user_initial = strtoupper($user_first_name[0]);

$dashboard_option = '<a href="' . URL . 'DashBoard"><i class="fa fa-cogs" aria-hidden="true"></i>Dashboard</a>';

$profile_pic_style = '';

if ($has_picture == true) {
    $profile_pic_style = 'style = "background-image: url(' . URL . 'DB/ProfilePics/' . $profile_pic . ')";';
}

?>

<body>
    <script>
        var toggle = false;

        function toggle_menu() {

            var drop_menu = document.getElementsByClassName('dropdown-content')[0];
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



        document.addEventListener('click', function(event) {
            if (!toggle) return;
            var profile_pic = document.getElementsByClassName('profile-pic')[0];
            var drop_menu = document.getElementsByClassName('dropdown-content')[0];
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
            <img src="<?= URL ?>public/img/logo/MNU-logos_black.png" id="logo-img" />
        </a>
        <nav>
            <div class="dropdown">
                <div class="profile-pic dropbtn" onclick="toggle_menu()" id="dropbtn" <?php echo $profile_pic_style ?>><?php if (!$has_picture) echo $user_initial ?></div>
                <div class="dropdown-content" id="menu">
                    <a href="#" class="link"><i class="fa fa-user" aria-hidden="true"></i>Account</a>
                    <?php if ($isRootAdmin) echo $dashboard_option ?>
                    <a href="<?= URL ?>users/logout" class="link"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a>
                </div>
            </div>
        </nav>
    </header>