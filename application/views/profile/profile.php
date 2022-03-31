<?php
$profile_pic = $_SESSION['user']->profile_picture;
$has_picture = ($profile_pic == null || $profile_pic == '' ? false : true);


$user_first_name = $_SESSION['user']->first_name;
$user_initial = strtoupper($user_first_name[0]);

$profile_pic_style = '';

if ($has_picture == true) {
    $profile_pic_style = 'style = "background-image: url(' . URL . 'DB/ProfilePics/' . $profile_pic . ')";';
}

$first_name = $user_first_name ? $user_first_name : "";
$last_name = $_SESSION['user']->last_name ? $_SESSION['user']->last_name : "";
$username = $_SESSION['user']->username ? $_SESSION['user']->username : "";
$middle_name = $_SESSION['user']->middle_name ? $_SESSION['user']->middle_name : "";

$user_id = $_SESSION['user']->id;
$_POST['id'] = $user_id;

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $("#file").change(function() {
            var length = this.files.length;
            if (!length) {
                return false;
            }
            changeBackground(this);
        });
        $('.text-input').on('focusin', function() {
            console.log("here");
            $(this).parent().find('label').addClass('active');
        });

        $('.text-input').on('focusout', function() {
            $(this).parent().find('label').removeClass('active');
        });
    });

    // Creating the function
    function changeBackground(img) {
        var file = img.files[0];
        var imagefile = file.type;
        var match = ["image/jpeg", "image/png", "image/jpg"];
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
            alert("Invalid File Extension");
        } else {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(img.files[0]);
        }

        function imageIsLoaded(e) {
            $('#prof-pic').css({
                'background': "none",
                'background-image': "url(" + e.target.result + ")",
                'border': "1px solid white"
            });

        }
    }

    function sssubmit() {

        console.log("Submitting");

        let payload = {};

        inputs = document.getElementsByClassName("text-input");

        Array.from(inputs).forEach(input => {
            input.value ? payload[input.id] = input.value : null;
        });

        try {
            fetch(`<?= URL ?>Api/update/user`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(payload),
                }).then(response => response.text())
                .then(txt => {
                    console.log(txt);
                    try {
                        const successful_insert = JSON.parse(txt)
                        console.log(successful_insert)
                    } catch (error) {
                        console.log('so close')
                        console.log(error)
                    }
                })
        } catch (error) {
            console.log('ops!')
            console.log(error)
        }
    }
</script>

<style>
    #prof-pic {
        width: 2.5em;
        height: 2.5em;
        margin: 0px auto;
        font-size: 3em;
        position: relative;
        z-index: 0;
    }

    #file {
        width: inherit;
        height: inherit;
        position: absolute;
        opacity: 0;
        border-radius: 50%;
        cursor: pointer;
    }

    .fa-camera {
        position: absolute;
        font-size: 0.4em;
        left: 3.7em;
        top: 3.7em;
        background-color: #2196f3;
        border-radius: 50%;
        z-index: 100;
        width: 1em;
        height: 1em;
        padding: 0.2em;
        cursor: default;
    }

    label {
        position: relative;
        top: 2.2em;
        left: -0.4em;
        font-size: 1em;
        margin: 10px;
        padding: 0 10px;
        -webkit-transition: top .2s ease-in-out, font-size .2s ease-in-out;
        transition: top .2s ease-in-out, font-size .2s ease-in-out;
        visibility: hidden;
        color: #3f51b5;
        font-family: "Helvetica";
        font-weight: 600;
    }

    input[type=text]:focus {
        outline: none;
    }

    .active {
        top: -0.1em;
        font-size: 0.7em !important;
        left: -1em;
        visibility: visible !important;
    }

    .valid-input:focus {
        box-shadow: 0 0 0 0.1rem rgb(0 123 255 / 25%);
    }

    .form-block,
    .login-form {
        margin: 0;
    }

    .login-container {
        margin: 1% auto;
        padding: 2% 3%;
    }

    .form-block button {
        margin: 1em auto;
    }

    #main-content {
        justify-content: center;
    }
</style>

<div id="main-content" class="inlineBlock">

    <div class="login-container">
        <div class="form-block">
            <div class="profile-pic dropbtn" id="prof-pic">
                <input id="file" type="file" title="Profile Picture" />
                <?php echo $profile_pic_style ?><?php if (!$has_picture) echo $user_initial ?>
                <i class="fa fa-camera" aria-hidden="true"></i>
            </div>
        </div>
        <form method="post" class="login-form">
            <div class="form-block">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="text-input valid-input" placeholder="Username" value="<?php echo $username ?>" />
                <small class="invalid-text">
                    Please enter a valid username</small>
            </div>
            <div class="form-block">
                <label for="password">Password</label>
                <input type="text" name="password" id="password" class="text-input valid-input" placeholder="Password" />
                <small class="invalid-text">
                    Please enter a valid password</small>
            </div>
            <div class="form-block">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" class="text-input valid-input" placeholder="First_name" value="<?php echo $first_name ?>" />
                <small class="invalid-text">
                    Please enter a valid first_name</small>
            </div>
            <div class="form-block">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="text-input valid-input" placeholder="Last_name" value="<?php echo $last_name ?>" />
                <small class="invalid-text">
                    Please enter a valid last_name</small>
            </div>
            <div class="form-block">
                <label for="middle_name">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" class="text-input valid-input" placeholder="Middle_name" value="<?php echo $middle_name ?>" />
                <small class="invalid-text">
                    Please enter a valid middle_name</small>
            </div>
            <div class="form-block">
                <button type="submit" id="submit-btn" onclick="sssubmit()">
                    Save Changes <svg id="spinner" viewBox="0 0 50 50">
                        <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

</div>