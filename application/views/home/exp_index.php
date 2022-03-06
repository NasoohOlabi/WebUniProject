<style>
    .univ-photo {
        width: 100%;
        height: 45.8em;
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
        position: relative;
        bottom: 10em;
        color: hsl(180deg 28% 85%);
        text-align: center;
        font-family: var(--primary-font);
        font-size: 3em;
        backdrop-filter: blur(10px);
        padding: 20px;
    }

    .content {
        max-height: 90vh;
    }
</style>

<div class="content">
    <div class="univ-photo" style="background-image: url(<?= URL ?>/public/img/background.png);"></div>
    <h2>Welcome To The University of MNU</h2>
</div>