<?php
$isLoggedIn = isset($_SESSION['loggedIn']) ? $_SESSION['loggedIn'] : false;
if ($isLoggedIn) {
    $role = $_SESSION['user']->role->name;
    $username = $_SESSION['username'];
}
switch ($role) {
    case 'TestAdmin':
        echo $role;

        break;

    default:
        # code...
        break;
}
?>

<script>
    var role = "<?= $role ?>";

    switch (role) {
        case "TestAdmin":
            const payload = {
                'limit': 1e9
            };
            try {
                fetch(`<?= URL ?>Api/read/subject`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload),
                    }).then(response => response.text())
                    .then(txt => {
                        // console.log(txt);
                        try {
                            let subjects_list = JSON.parse(txt)
                            console.log("subjects fetched")
                            console.log(subjects_list[0]);

                            const select = document.getElementById("subjects-list");

                            var def_text = (select.length ? "Select a subject" : "You don't have any subjects yet");

                            document.getElementById('default').innerText = def_text;

                            for (const subject of subjects_list) {
                                const name = subject['name'];
                                select.options[select.options.length] = new Option(name, name);
                            }

                        } catch (error) {
                            console.log('so close')
                            console.log(error)
                        }
                    })
            } catch (error) {
                console.log('ops!')
                console.log(error)
            }
            break;

        default:
            break;
    }

    console.log(role);
</script>

<div id="main-content" class="inlineBlock">

    <h1 id="welcome-header"><?= LANGUAGE::t('Welcome To The University Website') ?></h1>
    <hr>
    <p id="login-status-bar">You're logged in as <span><?php echo $username ?></span>. <a href="<?= URL ?>users/logout">Logout?</a></p>
    <div class="user-content">
        <h2>Generate Random Questions:</h2>

        <select name="subject" id="subjects-list" class="form-select">
            <option id="default" value="" disabled selected></option>
        </select>

        <div class="form-block">
            <button type="submit">Generate</button>
        </div>

    </div>
</div>