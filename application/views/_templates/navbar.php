<body>

    <header>
        <a href="<?= URL ?>home">
            <img src="<?= URL ?>public/img/logo/MNU-logos_black.png" id="logo-img" />
        </a>
        <nav>

            <?php
            if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 'true') {
                echo ('<h5 class="welcome">
                Welcome, you\'re logged in
            </h5>');
            } else {
                echo ('<span>
                <button class="nav-btn" onclick="pop()">Login</button>
            </span>
            <span><a href="' . URL . 'users\signup"><button class="nav-btn">Signup</button></a></span>');
            }
            ?>

        </nav>
    </header>