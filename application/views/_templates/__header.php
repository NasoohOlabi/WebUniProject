<!DOCTYPE html>
<html lang="<?= Language::$lang ?>" dir="<?= Language::$direction ?>">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= URL ?>public/css/all.min.css" />
    <link rel="stylesheet" href="<?= URL ?>public/css/style.css" />
    <link rel="stylesheet" href="<?= URL ?>public/css/util.style.css" />
    <script src="<?= URL ?>public/js/Main.js"></script>
    <?= (isset($options['Swal']) && $options['Swal']) ?  '<script src="' . URL . 'public/js/sweetalert2.js"></script>' : '' ?>
    <?= (isset($options['noform_util']) && $options['noform_util']) ?  '' : '<script src="' . URL . 'public/js/form_util.js"></script>'  ?>
    <?= (isset($options['noform']) && $options['noform']) ?  '' : '<script src="' . URL . 'public/js/form.js"></script>'  ?>
    <?= (Language::$direction == 'rtl') ? '<style>:root{--opp-dir:left;--norm-dir:right;}</style>' : '' ?>
</head>