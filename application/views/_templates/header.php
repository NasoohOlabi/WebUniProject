<?php
function pageHeadTag($title, $options)
{
    $language = (isset($_COOKIE['lang'])) ? $_COOKIE['lang'] : 'en';
    if ($language == 'ar') {
        require 'application/views/Languages/Arabic.php';
    } elseif ($language == 'en') {
        require 'application/views/Languages/English.php';
    }
    require_once '__header.php';
}
