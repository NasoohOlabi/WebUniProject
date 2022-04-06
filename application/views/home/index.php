<style>
    .univ-photo {
        width: 100%;
        height: calc(100vh - var(--navbar-height));
        min-height: 500px;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        filter: blur(2px);
    }

    .univ-photo div {
        z-index: 2;
        position: absolute;
    }

    .content h2 {
        position: absolute;
        top: 10rem;
        color: white;
        text-align: center;
        font-family: var(--primary-font);
        font-size: 3em;
        backdrop-filter: blur(10px);
        padding: 20px 0;
        margin: auto;
        width: 100vw;
    }

    .content {
        height: calc(100vh - var(--navbar-height));
    }
</style>

<div class="content">
    <div class="univ-photo" style="background-image: url(<?= URL ?>/public/img/background.png);"></div>
    <h2><?= Language::t('Welcome To The University of MNU') ?></h2>
</div>

<?php
if (isset($_SESSION['flash_message'])) {
    $msg = $_SESSION['flash_message'];
    echo "<script>
                addLoadEvent(()=>{Swal.fire({
                  position: 'top-end',
                  icon: 'info',
                  title: '" . Language::t($msg) . "',
                  showConfirmButton: false,
                  timer: 2000
                })})
              </script>";
    unset($_SESSION['flash_message']);
}
?>