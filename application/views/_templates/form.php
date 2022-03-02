
<?php
require_once 'component/input.php';

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    return $length > 0 ? substr($haystack, -$length) === $needle : true;
}
function stdclastoidstirng($stdClass)
{
    $columns = $stdClass::SQL_COLUMNS();
    $wanted_indexes = $stdClass->string_identifying_columns();
    $wanted_names = array_map(function ($i) use ($columns) {
        return $columns[$i];
    }, $wanted_indexes);
    $answer = [];
    foreach ($wanted_names as $prop) {
        $answer[] = $stdClass->$prop;
    }
    $answer = implode(' ', $answer);
    return $answer;
}
function getThisFromForm(Table $cls, BaseModel $bm)
{
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
            $objects = $bm->select([], $schemaClass);
            $id_indexed_objects = [];
            foreach ($objects as $value) {
                $id_indexed_objects[$value->id] = $value;
            }
            $v = array_map('stdclastoidstirng', $id_indexed_objects);
            $SELECT_OPTIONS[$field] = $v;
        } else
            $inputs[$field] = 'text';
    }
    require '__form.php';
}
