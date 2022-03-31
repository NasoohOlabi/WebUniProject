<?php

function _is_term(array $x)
{
    return (count($x) === 1 && !is_array($x[array_keys($x)[0]]))
        || (count($x) === 2 && !is_array($x[array_keys($x)[0]])
            && !is_array($x[array_keys($x)[1]])
            && isset($x['op'])
        );
}
function _parse_safe_term(array $x)
{
    // simpleLog("_parse_safe_term::no_wrap " . json_encode($no_wrap));
    $key = array_keys($x)[0];
    $val = $x[$key];
    // simpleLog(json_encode($x));
    return "$key " . (isset($x['op']) ? $x['op'] : '=') . " $val";
}
/**
 * [ [ [1=>2], [1=>3], [3=>2] ], [ 4=>5 ] ]
 * becomes
 * (1 = 2 OR 1 = 3 OR 3 = 2) AND (4 = 5)
 * ---------------------------------------------
 * [ [ [1=>2], [1=>3] ], [3=>2], [4=>5] ]
 * becomes
 * (1 = 2 OR 1 = 3) AND (3 = 2) AND (4 = 5)
 * ---------------------------------------------
 * [ [1=>2] ]
 * becomes
 * (1 = 2)
 * ---------------------------------------------
 * only single term
 * [ 1=>2 ]
 * becomes
 * (1 = 2)
 * ---------------------------------------------
 * [1=>3, 2=>4]
 * fails!!
 * ---------------------------------------------
 * [ [1=>3], [2=>4] ]
 * becomes
 * (1 = 3) AND (2 = 4)
 * ---------------------------------------------
 * [ [ [1=>3], [2=>4] ] ]
 * becomes
 * (1 = 3) OR (2 = 4)
 *
 * @param [type] $ON_conditions
 * @return void
 */
function _parse_conditions(array $ON_conditions)
{
    if (_is_term($ON_conditions))
        return _parse_safe_term($ON_conditions);
    $Acc = [];
    foreach ($ON_conditions as $value_1d) {
        if (_is_term($value_1d)) {
            $Acc[] = _parse_safe_term($value_1d);
        } elseif (is_array($value_1d)) {
            $bracket = [];
            foreach ($value_1d as $value_2d) {
                if (_is_term($value_2d)) {
                    $bracket[] = _parse_safe_term($value_2d);
                } else throw new Exception("Conditions Parse Error: parsing `" . json_encode($ON_conditions) . "`");
            }
            $Acc[] = implode(" OR ", $bracket);
        } else
            throw new Exception("Conditions Parse Error: parsing `" . json_encode($ON_conditions) . "`");
    }
    $Acc = '(' . implode(") AND (", $Acc) . ')';
    return $Acc;
}
function _parse_unsafe_term(array $x)
{
    $key = array_keys($x)[0];
    $val = $x[$key];
    // simpleLog(json_encode($x));
    return ['prepare' => "$key " . (isset($x['op']) ? $x['op'] : '=') . " ?", 'execute' => $val];
}
function _parse_WHERE_conditions(array $WHERE_conditions)
{
    $f = function (array $acc, array $v) {
        $acc['prepare'][] = $v['prepare'];
        $acc['execute'][] = $v['execute'];
        return $acc;
    };
    $answer = ['prepare' => [], 'execute' => []];
    if (_is_term($WHERE_conditions)) {
        $answer = $f($answer, _parse_unsafe_term($WHERE_conditions));
        $answer['prepare'] = '(' . implode(") AND (", $answer['prepare']) . ')';
        return $answer;
    }

    foreach ($WHERE_conditions as $value_1d) {
        if (_is_term($value_1d)) {
            $answer = $f($answer, _parse_unsafe_term($value_1d));
        } elseif (is_array($value_1d)) {
            $sub_answer = ['prepare' => [], 'execute' => []];
            foreach ($value_1d as $value_2d) {
                if (_is_term($value_2d)) {
                    $sub_answer[] = $f($sub_answer, _parse_unsafe_term($value_2d));
                } else throw new Exception("Conditions Parse Error: parsing `" . json_encode($WHERE_conditions) . "`");
            }
            $answer['prepare'][] = implode(" OR ", $sub_answer['prepare']);
            foreach ($sub_answer as $exec) {
                $answer['execute'][] = $exec;
            }
        } else
            throw new Exception("Conditions Parse Error: parsing `" . json_encode($WHERE_conditions) . "`");
    }
    $answer['prepare'] = '(' . implode(") AND (", $answer['prepare']) . ')';
    return $answer;
}
/**
 * ['Question','Role'] => ['question.id','question...','role.id',...]
 */
function _get_classes_dotted_columns(array $schemaClasses)
{
    $answer = [];
    foreach ($schemaClasses as $schemaClass) {
        $schemaClass = strtolower($schemaClass);
        foreach ($schemaClass::SQL_Dotted_Columns() as $value) {
            $answer[] = $value;
        }
    }

    simpleLog("_get_classes_dotted_columns input: " . json_encode($schemaClasses) . " answer: " . json_encode($answer));
    return $answer;
}
function _alias_dotted_columns(array $columns)
{
    return array_map(function ($value) {
        return "$value as " . str_replace('.', '_', $value);
    }, $columns);
}
