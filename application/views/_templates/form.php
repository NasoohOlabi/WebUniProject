
<?php
require_once 'component/input.php';
function TileForThis($the_one, $sub_cls, $bm)
{
    $parent_class_name = get_class($the_one);
    $not_has_Nor_the_one = array_values(array_filter(explode('_has_', strtolower($sub_cls)), function ($table) use ($parent_class_name) {
        return $table != strtolower($parent_class_name);
    }));
    $the_other = (count($not_has_Nor_the_one) == 1) ? ucfirst($not_has_Nor_the_one[0]) : false;
    $schemaClass = implode('_', array_map('ucfirst', explode('_', $sub_cls)));
    $objects = ($the_other)
        ? $bm->join([$the_other, $sub_cls], [$the_other::id => $sub_cls::access(strtolower($the_other) . '_id')])
        : $bm->select([], $schemaClass);

    $id_indexed_objects = [];
    foreach ($objects as $value) {
        $id_indexed_objects[$value->id] = $value;
    }
    $v = array_map('stdclastoidstirng', $id_indexed_objects);
    $SELECT_OPTIONS = $v;

    $cls = $the_one;
    require '__tiles_for_this.php';
}
function Permissions_Tiles($cls, $bm)
{
    $parent_class_name = 'Role';
    $sub_cls = 'Permission';

    $schemaClass = implode('_', array_map('ucfirst', explode('_', $sub_cls)));
    $objects =  $bm->select([], $schemaClass);

    $id_indexed_objects = [];
    foreach ($objects as $value) {
        $id_indexed_objects[$value->id] = $value;
    }
    $v = array_map('stdclastoidstirng', $id_indexed_objects);

    $SELECT_OPTIONS = $v;

    $CONTEXT_TILES_IDs = array_map(function ($permission) {
        return ['id' => $permission->id, 'name' => $permission->name];
    }, $bm->join(
        ['Permission', 'Role_Has_Permission', 'Role'],
        [[Role_Has_Permission::permission_id => Permission::id], [Role_Has_Permission::role_id => Role::id]],
        [Role::id => $cls->id]
    ));

    require 'component/__Permissions__tiles.php';
}
function PageForThis(Table $cls, BaseModel $bm, array $omit = [],$submitBtnNeeded = true)
{
    FormForThis($cls, $bm, $omit);
    if (count($cls->dependents) > 0) {
        // $dep = $cls->dependents;
        // if (is_array($dep) && count($dep) == 1) {
        //     foreach ($dep as $key => $value) {
        //         $row->{strtolower($key) . 's'} = $Model->select([], $key, [], [$key::access($value) => $row->id]);
        //     }
        // } else {
        //     $row->{strtolower($dep) . 's'} = $Model->select([], $dep, [], [$dep::access(strtolower(get_class($row)) . '_id') => $row->id]);
        // }
        foreach ($cls->dependents as $dep) {
            $sub_cls = $dep;
            if ($dep === 'Student_Exam' || $dep === 'Student_Exam_Has_Question') {
                continue;
            } elseif (is_array($dep) && count($dep) == 1) {
                // if (is_array($dep) && count($dep) == 1) {
                foreach ($dep as $key => $value) {
                    $sub_cls = $key;
                    require '__pageForThis1.php';
                    FormForThis(new $key(), $bm, [$value]);
                    require '__pageForThis2.php';
                }
            } else {
                if (str_contains(strtolower($sub_cls), '_has_')) {
                    TileForThis($cls, $sub_cls, $bm);
                } else {
                    require '__pageForThis1.php';
                    FormForThis(new $sub_cls(), $bm, [strtolower(get_class($cls)) . "_id"]);
                    require '__pageForThis2.php';
                }
            }
        }
    }
}
function stdclastoidstirng($stdClass)
{
    $columns = $stdClass::SQL_COLUMNS();
    $wanted_names = $stdClass->identifying_fields;
    $answer = [];
    foreach ($wanted_names as $prop) {
        $answer[] = $stdClass->$prop;
    }
    $answer = implode(' ', $answer);
    return $answer;
}
function FormForThis(Table $cls, BaseModel $bm, array $omit = [])
{
    $required_fields = $cls::SQL_Columns();
    unset($required_fields[0]);
    // print_r($required_fields);
    $inputs = [];
    $SELECT_OPTIONS = [];
    foreach ($required_fields as  $field) {
        if (in_array($field, $omit)) continue;
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
        } elseif ($field === 'profile_picture') {
            $inputs[$field] = 'profile_picture';
        } elseif (str_contains($field, 'date')) {
            $inputs[$field] = 'date';
        } else
            $inputs[$field] = 'text';
    }
    require '__form.php';
}
