<body>
    <div id="main-container">
        <noscript>It seems that you don't have javascript enabled for this
            site...</noscript>
        <div style="min-width:375px" class="login-container">
            <button class="remove-div-x-btn" onclick="window.location = ourURL + 'dashboard/'">
                <i style="font-size:x-large" class="fa fa-close"></i>
            </button>

            <div class="icon-container">
                <i class="fa fa-address-card user-icon"></i>
            </div>
            <div class="form-block">
                <?php
                if (isset($_SESSION['msg'])) {
                    if ($_SESSION['success']) {
                        echo '<h1>' . $_SESSION['msg'] . '</h1>';
                        unset($_SESSION['msg']);
                        unset($_SESSION['success']);
                    } else {
                        echo '<h1>' . $_SESSION['msg'] . '</h1>';
                        unset($_SESSION['msg']);
                        unset($_SESSION['success']);
                    }
                }
                ?>
            </div>
            <form action="<?= URL ?>users/register" method="post" enctype="multipart/form-data">
                <div class="form-block">
                    <input type="text" name="first_name" id="first_name" class="text-input valid-input" placeholder="First Name" required />
                    <small class="invalid-text">
                        Please enter a valid first name</small>
                </div>
                <div class="form-block">
                    <input type="text" name="middle_name" id="middle_name" class="text-input valid-input" placeholder="Middle Name" />
                    <small class="invalid-text">
                        Please enter a valid middle name</small>
                </div>
                <div class="form-block">
                    <input type="text" name="last_name" id="last_name" class="text-input valid-input" placeholder="Last Name" required />
                    <small class="invalid-text">
                        Please enter a valid last name</small>
                </div>
                <div class="form-block">
                    <input type="text" name="username" id="username" class="text-input valid-input" placeholder="Username" required />
                    <small class="invalid-text">
                        Please enter a valid username</small>
                </div>

                <!-- <div class="form-block">
                    <input type="tel" name="phone" id="phone" class="text-input valid-input" placeholder="Phone Number" required />
                    <small class="invalid-text">
                        Please enter a valid phone Number</small>
                </div> -->

                <div class="form-block">
                    <input type="password" name="password" id="password" class="text-input valid-input" placeholder="Password" required />
                    <small class="invalid-text">
                        Please enter a valid password</small>
                </div>
                <div class="form-block">
                    <input type="password" name="confirm-password" id="confirm-password" class="text-input valid-input" placeholder="Confirm Password" required />
                    <small class="invalid-text"> Doesn't match password</small>
                </div>
                <div class="form-block">
                    <?php
                    require_once 'application/views/_templates/component/input.php';
                    $roles = $database->select([], 'Role');
                    $select_options = [];
                    foreach ($roles as $role) {
                        $select_options[$role->id] = $role->name;
                    }
                    select_input('role_id', $select_options);
                    ?>
                </div>
                <div class="form-block">
                    <input name="ProfileImg" class="file-input" type="file" id="formFile" accept="image/png, image/jpeg" />
                </div>
                <div class="form-block">
                    <button type="submit" id="submit-btn" class="default-form">
                        Sign Up<svg id="spinner" viewBox="0 0 50 50">
                            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                        </svg>
                    </button>
                </div>
            </form>

        </div>
    </div>
    <!-- <script src="<?= URL ?>/public/js/form.js" type="text/javascript"></script> -->
</body>

</html>