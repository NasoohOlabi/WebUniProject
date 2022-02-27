<script src="<?= URL ?>public/js/popping.js"></script>
<div id="main-container-popup" onclick="outerClicked(event,this)">
    <noscript>It seems that you don't have javascript enabled for this
        site...</noscript>
    <div class="login-container" onclick="innerClicked(event,this)">
        <div class="icon-container">
            <i class="fas fa-user-circle user-icon"></i>
        </div>

        <form action="" method="post" class="login-form">
            <div class="form-block">
                <input type="text" name="email" id="email" class="text-input valid-input" placeholder="Email" required />
                <small class="invalid-text">
                    Please enter a valid email</small>
            </div>
            <div class="form-block">
                <input type="password" name="password" id="password" class="text-input valid-input" placeholder="Password" required />
                <small class="invalid-text">Please enter a valid password</small>
            </div>
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
</div>