<body>
    <div id="main-container">
        <noscript>It seems that you don't have javascript enabled for this
            site...</noscript>
        <div class="login-container">
            <div class="icon-container">
                <i class="fas fa-address-card user-icon"></i>
            </div>
            <form action="" method="post">
                <div class="form-block">
                    <input type="text" name="first_name" id="first_name" class="text-input valid-input" placeholder="First Name" required />
                    <small class="invalid-text">
                        Please enter a valid first name</small>
                </div>
                <div class="form-block">
                    <input type="text" name="last_name" id="last_name" class="text-input valid-input" placeholder="Last Name" required />
                    <small class="invalid-text">
                        Please enter a valid last name</small>
                </div>
                <div class="form-block">
                    <input type="text" name="email" id="email" class="text-input valid-input" placeholder="Email" required />
                    <small class="invalid-text">
                        Please enter a valid email</small>
                </div>

                <div class="form-block">
                    <input type="tel" name="phone" id="phone" class="text-input valid-input" placeholder="Phone Number" required />
                    <small class="invalid-text">
                        Please enter a valid phone Number</small>
                </div>

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
                    <select name="AccountType" class="form-select valid-input" aria-label="Account Type">
                        <option value="" disabled selected hidden>
                            Account Type
                        </option>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-block">
                    <input name="ProfileImg" class="file-input" type="file" id="formFile" accept="image/png, image/jpeg" />
                </div>
                <div class="form-block">
                    <button type="submit" id="submit-btn">
                        Sign Up<svg id="spinner" viewBox="0 0 50 50">
                            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="<?= URL ?>/public/js/form.js" type="text/javascript"></script>
</body>

</html>