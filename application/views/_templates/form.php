
<?php
require_once 'component/input.php';
// function getThisFromForm(StdClass $cls) FIXME: use type annotations
function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    return $length > 0 ? substr($haystack, -$length) === $needle : true;
}
function getThisFromForm($cls)
{
    //FIXME: use prepare in basemodel
    $required_fields = $cls::SQL_Columns();
    unset($required_fields[0]);
    // print_r($required_fields);
    $inputs = [];
    foreach ($required_fields as  $field) {
        if (endsWith($field, "_id")) {
            $inputs[$field] = "select";
            // $inputs[$field.""]
        } else
            $inputs[$field] = 'text';
    }
    require '__form.php';
}
