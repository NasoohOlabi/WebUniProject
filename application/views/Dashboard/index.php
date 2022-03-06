<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="<?= URL ?>public/css/dashboard_style.css" />
</head>

<body>
  <nav class="sidebar">
    <header>
      <a href="<?= URL ?>home/ " style="text-decoration: none;">
        <div class="image-text">
          <span class="image">
            <img src="<?= URL ?>public/img/logo/MNU-logos_black.png" alt="" />
          </span>

          <div class=" text logo-text">
            <span class="name"><?php echo $_SESSION['user']->username ?></span>
            <span class="profession">Admin Account</span>
          </div>
        </div>
      </a>
    </header>

    <div class="menu-bar">
      <div class="menu">
        <ul class="menu-links">
          <li class="nav-link">
            <a href="#">
              <span class="text nav-text" id="dashboard">Dashboard</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#" onclick="switchTo('user')">
              <span class="text nav-text" id="user">Users</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#" onclick="switchTo('question')">
              <span class="text nav-text" id="question">Questions</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#" onclick="switchTo('exam')">
              <span class="text nav-text" id="exam">Exams</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#">
              <span class="text nav-text">Analytics</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#">
              <span class="text nav-text">Likes</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#">
              <span class="text nav-text">Wallets</span>
            </a>
          </li>
        </ul>
      </div>

      <div class="bottom-content">
        <li class="">
          <a href="<?= URL ?>users/logout">
            <span class="text nav-text">Logout</span>
          </a>
        </li>
      </div>
    </div>
  </nav>

  <section id="home">
    <div class="text" id="title">Dashboard</div>
    <div style="
    height:fit-content" id="TTTarget"></div>
  </section>
  <script>
    var URL = '<?= URL ?>'
  </script>
  <script src="<?= URL ?>public/js/dashboard.js"></script>
</body>

</html>