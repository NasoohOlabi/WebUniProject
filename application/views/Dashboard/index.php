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
      <div class="image-text">
        <span class="image">
          <img src="<?= URL ?>public/img/logo/MNU-logos_black.png" alt="" />
        </span>

        <div class=" text logo-text">
          <span class="name">Username</span>
          <span class="profession">Admin Account</span>
        </div>
      </div>
    </header>

    <div class="menu-bar">
      <div class="menu">
        <ul class="menu-links">
          <li class="nav-link">
            <a href="#">
              <span class="text nav-text">Dashboard</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#">
              <span class="text nav-text">Revenue</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#">
              <span class="text nav-text">Notifications</span>
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
          <a href="#">
            <span class="text nav-text">Logout</span>
          </a>
        </li>
      </div>
    </div>
  </nav>

  <section class="home">
    <div class="text">Dashboard Sidebar</div>
  </section>
</body>

</html>