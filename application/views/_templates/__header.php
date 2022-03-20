<!DOCTYPE html>
<html lang="<?= $language ?>" dir="<?= LANGUAGE::$direction ?>">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= URL ?>public/css/all.min.css" />
    <link rel="stylesheet" href="<?= URL ?>public/css/style.css" />
    <link rel="stylesheet" href="<?= URL ?>public/css/util.style.css" />
    <!-- <script src="https://use.fontawesome.com/f392b27f2a.js"></script> -->
    <script src="<?= URL ?>public/js/Main.js"></script>
    <?= (isset($options['Swal']) && $options['Swal']) ?  '<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>' : '' ?>
    <?= (isset($options['noform_util']) && $options['noform_util']) ?  '' : '<script src="' . URL . 'public/js/form_util.js"></script>'  ?>
    <?= (isset($options['noform']) && $options['noform']) ?  '' : '<script src="' . URL . 'public/js/form.js"></script>'  ?>
    <?= (LANGUAGE::$direction == 'rtl') ? '<style>:root{--opp-dir:left;--norm-dir:right;}</style>' : '' ?>
</head>