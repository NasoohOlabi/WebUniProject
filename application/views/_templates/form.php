
<?php
require_once 'component/input.php';
// function getThisFromForm(StdClass $cls) FIXME: use type annotations
function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    return $length > 0 ? substr($haystack, -$length) === $needle : true;
}
function getThisFromForm($cls, $bm)
{
    //FIXME: use prepare in basemodel
    $required_fields = $cls::SQL_Columns();
    unset($required_fields[0]);
    // print_r($required_fields);
    $inputs = [];
    $SELECT_OPTIONS = [];
    foreach ($required_fields as  $field) {
        if (endsWith($field, "_id")) {
            $inputs[$field] = "select";
            // not solid nor Layered
            $schemaClass = ucfirst(substr($field, 0, strlen($field) - 3));
            $SELECT_OPTIONS[$field] = $bm->select([], $schemaClass);
        } else
            $inputs[$field] = 'text';
    }
    require '__form.php';
}
