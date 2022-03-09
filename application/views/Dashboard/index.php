<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="<?= URL ?>public/css/dashboard_style.css" />
  <link rel="stylesheet" href="<?= URL ?>public/css/all.min.css" />
  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <a href="#" onclick="switchTo('Dashboard')">
              <span class="text nav-text" id="Dashboard">Dashboard</span>
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
            <a href="#" onclick="switchTo('exam_center')">
              <span class="text nav-text" id="exam_center">Exam Centers</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#" onclick="switchTo('subject')">
              <span class="text nav-text" id="subject">Subjects</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#" onclick="switchTo('topic')">
              <span class="text nav-text" id="topic">Topics</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#" onclick="switchTo('role')">
              <span class="text nav-text" id="role">Roles</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#" onclick="switchTo('permission')">
              <span class="text nav-text" id="permission">Permission</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#" onclick="switchTo('role_has_permission')">
              <span class="text nav-text" id="role_has_permission">Roles Permissions</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#" onclick="switchTo('student')">
              <span class="text nav-text" id="student">Students</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#" onclick="switchTo('student_took_exam')">
              <span class="text nav-text" id="student_took_exam">Exams Taken</span>
            </a>
          </li>

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
    height:fit-content" id="TTTarget">
    </div>
    <div id="chartContainer" style="height: fit-content; width: 100%;"></div>
  </section>
  <script>
    var URL = '<?= URL ?>'
  </script>
  <script src="<?= URL ?>public/js/dashboard.js"></script>
</body>

</html>