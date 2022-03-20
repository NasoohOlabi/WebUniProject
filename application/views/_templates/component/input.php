<?php
function text_input(string $name)
{
    require '_text_input.php';
}

function select_input(string $input_name, array $SELECT_OPTIONS, string $place_holder = null)
{
    require '_select_input.php';
}
function datalist_input(string $input_name, array $SELECT_OPTIONS, string $place_holder = null)
{
    require '_datalist_input.php';
}
function date_input(string $name)
{
    require '_date_input.php';
}
function picture_input()
{
    require '_picture_input.php';
}
