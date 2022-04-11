<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="icon" href="<?= URL ?>public/img/logo/favicon.png" type="image/ico">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="<?= URL ?>public/css/dashboard_style.css" />
  <link rel="stylesheet" href="<?= URL ?>public/css/all.min.css" />
  <script src="<?= URL ?>public/js/Main.js"></script>
  <script src="<?= URL ?>public/js/canvasjs.js"></script>
  <script src="<?= URL ?>public/js/sweetalert2.js"></script>
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
            <span class="name"><?php echo (isset($_SESSION['user'])) ? $_SESSION['user']->username : 'John Doe' ?></span>
            <span class="profession"><?= ((isset($_SESSION['user']) && isset($_SESSION['user']->role)) ? $_SESSION['user']->role->name : 'Admin Account') ?></span>
          </div>
        </div>
      </a>
    </header>

    <?php
    if (isset($_SESSION['flash_message'])) {
      $msg = $_SESSION['flash_message'];
      echo "<script>
                Swal.fire({
                  position: 'top-end',
                  icon: 'success',
                  title: '$msg',
                  showConfirmButton: false,
                  timer: 1000
                })
              </script>";
      unset($_SESSION['flash_message']);
    }
    ?>


    <div class="menu-bar">
      <div class="menu">
        <ul class="menu-links">
          <?php
          if (isset($_SESSION['user']) && sessionUserHasRole('ROOT::ADMIN'))
            echo '
          <li class="nav-link">
            <a href="#" onclick="switchTo(\'Dashboard\',event)">
              <span class="text nav-text" id="Dashboard">Dashboard</span>
            </a>
          </li>';

          if (isset($_SESSION['user']) && sessionUserHasPermissions(['read_user']))
            echo '
          <li class="nav-link">
            <a href="#" onclick="switchTo(\'user\',event)">
              <span class="text nav-text" id="user">Users</span>
            </a>
          </li>';

          if (isset($_SESSION['user']) && sessionUserHasPermissions(['read_question']))
            echo '
          <li class="nav-link">
            <a href="#" onclick="switchTo(\'question\',event)">
              <span class="text nav-text" id="question">Questions</span>
            </a>
          </li>';

          if (isset($_SESSION['user']) && sessionUserHasPermissions(['read_exam']))
            echo '
          <li class="nav-link">
            <a href="#" onclick="switchTo(\'exam\',event)">
              <span class="text nav-text" id="exam">Exams</span>
            </a>
          </li>';

          if (isset($_SESSION['user']) && sessionUserHasPermissions(['read_exam_center']))
            echo '
          <li class="nav-link">
            <a href="#" onclick="switchTo(\'exam_center\',event)">
              <span class="text nav-text" id="exam_center">Exam Centers</span>
            </a>
          </li>';

          if (isset($_SESSION['user']) && sessionUserHasPermissions(['read_subject']))
            echo '
          <li class="nav-link">
            <a href="#" onclick="switchTo(\'subject\',event)">
              <span class="text nav-text" id="subject">Subjects</span>
            </a>
          </li>';

          if (isset($_SESSION['user']) && sessionUserHasPermissions(['read_topic']))
            echo '
          <li class="nav-link">
            <a href="#" onclick="switchTo(\'topic\',event)">
              <span class="text nav-text" id="topic">Topics</span>
            </a>
          </li>';

          if (isset($_SESSION['user']) && sessionUserHasPermissions(['read_role']))
            echo '
          <li class="nav-link">
            <a href="#" onclick="switchTo(\'role\',event)">
              <span class="text nav-text" id="role">Roles</span>
            </a>
          </li>';

          if (isset($_SESSION['user']) && sessionUserHasPermissions(['read_permission']))
            echo '<li class="nav-link">
            <a href="#" onclick="switchTo(\'permission\',event)">
              <span class="text nav-text" id="permission">Permissions</span>
            </a>
          </li>';

          if (isset($_SESSION['user']) && sessionUserHasPermissions(['read_student']))
            echo '
          <li class="nav-link">
            <a href="#" onclick="switchTo(\'student\',event)">
              <span class="text nav-text" id="student">Students</span>
            </a>
          </li>';

          if (isset($_SESSION['user']) && sessionUserHasPermissions(['read_student_exam']))
            echo '
          <li class="nav-link">
            <a href="#" onclick="switchTo(\'student_exam\',event)">
              <span class="text nav-text" id="student_exam">Upcoming Exams</span>
            </a>
          </li>';

          if (isset($_SESSION['user']) && sessionUserHasPermissions(['read_student_exam']))
            echo '
          <li class="nav-link">
            <a href="#" onclick="switchTo(\'student_exam\',event)">
              <span class="text nav-text" id="student_exam">Exams Taken</span>
            </a>
          </li>';
          ?>
        </ul>
      </div>

      <div class="bottom-content top-shadowed">
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
    height:fit-content" id="JS-App-Root">
    </div>
    <div style="position:relative;width:65px;top:10px;height:10px;background-color:var(--body-color);z-index:20"></div>
    <div id="chartContainer" style="height: fit-content; width: 100%;">
    </div>
  </section>
  <script>
    var URL = '<?= URL ?>'
    var permissions = <?= json_encode($_SESSION['user']->permissions) ?>
  </script>
  <script src="<?= URL ?>public/js/dashboard.js"></script>
</body>

</html>