<?php
require_once './application/libs/util/Option.php';
require_once './application/libs/util/log.php';
class BaseModel
{
    /**
     * Every model needs a database connection, passed to the model
     * @param object $db A PDO database connection
     */
    protected $table;
    function __construct($db, $table)
    {
        try {
            $this->db = $db;
            $this->table = $table;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    /**
     * Get all records from database
     */
    public function getAll()
    {
        $sql = "SELECT * FROM " . $this->table;
        $query = $this->db->prepare($sql);

        simpleLog('BaseModel::getAll Running : "' . $sql . '"');

        $query->execute();
        return $query->fetchAll();
    }

    /**
     * delete certain record from database by the table id
     */
    public function deleteById($id)
    {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :table_id";

        simpleLog('BaseModel::deleteById Running : "' . $sql . '"');

        $query = $this->db->prepare($sql);
        $query->execute(array(':table_id' => $id));
    }
    /**
     * returns Option with the row with this particular id
     *
     * @param [int] $id
     * @return Option
     */
    public function getById($id)
    {
        $result = $this->select([], "$this->table", ["$this->table.id" => $id]);

        if (count($result) == 0) {
            throw new Exception("id $id doesn't exist");
        }

        return  $result[0];
    }

    /**
     * get the DESC description of the table
     * @large{Warning: not optimized!!!!}
     * @return array of stdClass {
     *  [Field] => string <br/>
     *  [Type] => sql_type
     *  [Null] => YES|NO
     *  [Key] => PRI|...
     *  [Default] => 
     *  [Extra] => auto_increment|...
     * 
     */
    public function columns()
    {
        $sql = "DESC `" . $this->table . "`";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * Takes an array of column names with values to set for each record.
     * if you add more key=>value pairs than what's needed they will be ommited.
     * if you add less key=>value pairs than what's needed the function will return false as failed operation
     *
     * @param ["sqlColumnName"=>value] $dict
     * @return Option
     */
    function insert($dict)
    {
        $col = $this->columns();
        $bindings = array();
        $columns_names = [];
        $values = [];
        foreach ($col as $index => $column_description) {
            if ($column_description->Field == "id")
                continue;

            if (!isset($dict["{$column_description->Field}"])) {
                $s = "you forgot {$column_description->Field}";
                return new Either\Err($s);
            } else {
                $columns_names[] = `{$column_description->Field}`;
                $values[] = ":{$column_description->Field}";
                $bindings[":" . $column_description->Field] = $dict[$column_description->Field];
            }
        }
        $columns_names = implode(", ", $columns_names);
        $values = implode(", ", $values);

        $sql = "INSERT INTO `$this->table`($columns_names) VALUES ($values)";

        simpleLog('BaseModel::insert Running : "' . $sql . '"');

        $this->db->prepare($sql)->execute($bindings);
        return new Either\Result();
    }
    function experimental_insert($object)
    {
        $schemaClass = get_class($object);
        $columns = $schemaClass::SQL_Columns();

        unset($columns['id']); // it's auto incremented

        $values = implode(", ", array_map(function ($column_name) use ($object) {
            return $object->$column_name;
        }, $columns));
        $columns_names = implode(", ", $columns);

        $sql = "INSERT INTO `$schemaClass` ($columns_names) VALUES ($values)";
        simpleLog('BaseModel::experimental_insert Running : "' . $sql . '"');
        return $this->db->prepare($sql)->execute();
    }
    private static function _is_term($x)
    {
        return is_array($x) && count($x) == 1 && !is_array($x[array_keys($x)[0]]);
    }
    private static function _parse_safe_term(array $x, array $no_wrap)
    {
        $key = array_keys($x)[0];
        $val = $x[$key];
        if (!in_array($key, $no_wrap))
            $key = '"' . $key . '"';
        if (!in_array($val, $no_wrap))
            $val = '"' . $val . '"';
        return "$key = $val";
    }
    /**
     * [[[1=>2],[1=>3],[3=>2]],[4=>5]]
     * becomes
     * (1 = 2 OR 1 = 3 OR 3 = 2) AND (4 = 5)
     * ---------------------------------------------
     * [[[1=>2],[1=>3]],[3=>2],[4=>5]]
     * becomes
     * (1 = 2 OR 1 = 3) AND (3 = 2) AND (4 = 5)
     * ---------------------------------------------
     * [[1=>2]]
     * becomes
     * (1 = 2)
     * ---------------------------------------------
     * only single term
     * [1=>2]
     * becomes
     * (1 = 2)
     * ---------------------------------------------
     * [1=>3,2=>4]
     * fails!!
     * ---------------------------------------------
     * [[1=>3],[2=>4]]
     * becomes
     * (1 = 3) AND (2 = 4)
     *
     * @param [type] $safe_conditions
     * @return void
     */
    private static function _parse_conditions(array $safe_conditions, array $no_wrap)
    {
        if (BaseModel::_is_term($safe_conditions))
            return BaseModel::_parse_safe_term($safe_conditions, $no_wrap);
        $Acc = [];
        foreach ($safe_conditions as $value_1d) {
            if (BaseModel::_is_term($value_1d)) {
                $Acc[] = BaseModel::_parse_safe_term($value_1d, $no_wrap);
            } elseif (is_array($value_1d)) {
                $bracket = [];
                foreach ($value_1d as $value_2d) {
                    if (BaseModel::_is_term($value_2d)) {
                        $bracket[] = BaseModel::_parse_safe_term($value_2d, $no_wrap);
                    } else throw new Exception("Conditions Parse Error: parsing `" . json_encode($safe_conditions) . "`");
                }
                $Acc[] = implode(" OR ", $bracket);
            } else
                throw new Exception("Conditions Parse Error: parsing `" . json_encode($safe_conditions) . "`");
        }
        $Acc = '(' . implode(") AND (", $Acc) . ')';
        return $Acc;
    }
    private static function _parse_unsafe_term(array $x)
    {
        $key = array_keys($x)[0];
        $val = $x[$key];
        return ['prepare' => "$key = ?", 'execute' => $val];
    }
    private static function _parse_unsafe_conditions(array $unsafe_conditions)
    {
        $f = function (array $acc, array $v) {
            $acc['prepare'][] = $v['prepare'];
            $acc['execute'][] = $v['execute'];
            return $acc;
        };
        $answer = ['prepare' => [], 'execute' => []];
        if (BaseModel::_is_term($unsafe_conditions))
            return $f($answer, BaseModel::_parse_unsafe_term($unsafe_conditions));

        foreach ($unsafe_conditions as $value_1d) {
            if (BaseModel::_is_term($value_1d)) {
                $answer =  $f($answer, BaseModel::_parse_unsafe_term($value_1d));
            } elseif (is_array($value_1d)) {
                $sub_answer = ['prepare' => [], 'execute' => []];
                foreach ($value_1d as $value_2d) {
                    if (BaseModel::_is_term($value_2d)) {
                        $sub_answer[] = $f($sub_answer, BaseModel::_parse_unsafe_term($value_2d));
                    } else throw new Exception("Conditions Parse Error: parsing `" . json_encode($unsafe_conditions) . "`");
                }
                $answer['prepare'][] = implode(" OR ", $sub_answer['prepare']);
                foreach ($sub_answer as $exec) {
                    $answer['execute'][] = $exec;
                }
            } else
                throw new Exception("Conditions Parse Error: parsing `" . json_encode($unsafe_conditions) . "`");
        }
        $answer['prepare'] = '(' . implode(") AND (", $answer['prepare']) . ')';
        return $answer;
    }
    /**
     * ['Question','Role'] => ['question.id','question...','role.id',...]
     */
    private static function _get_classes_dotted_columns(array $schemaClasses)
    {
        $answer = [];
        foreach ($schemaClasses as $schemaClass) {
            $schemaClass = strtolower($schemaClass);
            foreach ($schemaClass::SQL_Columns() as  $value) {
                $answer[] = $schemaClass . '.' . $value;
            }
        }
        return $answer;
    }
    private static function _alias_dotted_columns(array $columns)
    {
        return array_map(function ($value) {
            return "$value as " . str_replace('.', '_', $value);
        }, $columns);
    }
    public function join(array $schemaClasses, array $safe_conditions, array $unsafe_conditions = null, string $wrapper = null)
    {
        if ($wrapper == null)
            $wrapper = $schemaClasses[0];

        $columns = BaseModel::_get_classes_dotted_columns($schemaClasses);

        // insert tables names with JOIN ex: `exam` JOIN `subjects`
        $tables = implode(" JOIN ", array_map(function ($schemaClass) {
            return "`$schemaClass`";
        }, $schemaClasses));
        if (count($safe_conditions) > 0)
            $safe_conditions = BaseModel::_parse_conditions($safe_conditions, $columns);

        if (count($unsafe_conditions) > 0) {
            $unsafe_conditions_parsed = BaseModel::_parse_unsafe_conditions($unsafe_conditions);
            $unsafe_sql_string = $unsafe_conditions_parsed['prepare'];
            $unsafe_bindings = $unsafe_conditions_parsed['execute'];
        }

        $order = implode(", ", array_map(function ($schemaClass) {
            return str_replace(".", "_", $schemaClass::id);
        }, $schemaClasses));

        $column_names_aliased_string = implode(", ", BaseModel::_alias_dotted_columns($columns));
        if (count($unsafe_conditions) > 0) {
            if (count($safe_conditions) > 0)
                $sql = "SELECT $column_names_aliased_string FROM $tables ON $safe_conditions WHERE $unsafe_sql_string ORDER BY $order;";
            else
                $sql = "SELECT $column_names_aliased_string FROM $tables WHERE $unsafe_sql_string ORDER BY $order;";
        } else {
            if (count($safe_conditions) > 0)
                $sql = "SELECT $column_names_aliased_string FROM $tables ON $safe_conditions ORDER BY $order;";
            else
                $sql = "SELECT $column_names_aliased_string FROM $tables ORDER BY $order;";
        }

        simpleLog("BaseModel::join Running : $sql");

        $query = $this->db->prepare($sql);
        $query->execute($unsafe_bindings);
        $lines = $query->fetchAll();

        return (count($lines)) ?
            array_map(function ($args) use ($wrapper) {
                return new $wrapper($args,  strtolower($wrapper) . "_");
            }, $lines) :
            new Exception("No Exams available");
    }
    public function select(array $columns, string $schemaClass, $safe_conditions = null, array $unsafe_conditions = null, array $Literal_SQL_Columns = null)
    {
        if ($Literal_SQL_Columns != null && isset($Literal_SQL_Columns['overwrite'])) {
            $columns_string = $Literal_SQL_Columns['overwrite'];
            if (count($columns) == 0) {
                $columns_array = BaseModel::_get_classes_dotted_columns([$schemaClass]);
            } else {
                $columns_array = $columns;
            }
        } else {
            if (count($columns) == 0) {
                $columns_string = '*';
                $columns_array = BaseModel::_get_classes_dotted_columns([$schemaClass]);
            } else {
                $columns_string = implode(', ', $columns);
                $columns_array = $columns;
            }
        }

        if ($unsafe_conditions != null && count($unsafe_conditions) > 0) {
            $unsafe_conditions_parsed = BaseModel::_parse_unsafe_conditions($unsafe_conditions);
            $unsafe_sql_string = $unsafe_conditions_parsed['prepare'];
            $unsafe_bindings = $unsafe_conditions_parsed['execute'];
        }

        if ($safe_conditions == null || count($safe_conditions) == 0) {
            if ($unsafe_conditions == null || count($unsafe_conditions) == 0) {
                $sql = "SELECT $columns_string FROM $schemaClass;";
            } else {
                $sql = "SELECT $columns_string FROM $schemaClass WHERE $unsafe_sql_string;";
            }
        } else {
            $safe_conditions = BaseModel::_parse_conditions($safe_conditions, $columns_array);
            if ($unsafe_conditions == null || count($unsafe_conditions) == 0) {
                $sql = "SELECT $columns_string FROM $schemaClass WHERE $safe_conditions;";
            } else {
                $sql = "SELECT $columns_string FROM $schemaClass WHERE $safe_conditions AND $unsafe_sql_string;";
            }
        }
        simpleLog("BaseModel::select Running : ($sql)");

        $query = $this->db->prepare($sql);

        if ($unsafe_conditions == null || count($unsafe_conditions) == 0) {
            $query->execute();
        } else {
            $query->execute($unsafe_bindings);
        }

        $lines = $query->fetchAll();
        if (!isset($Literal_SQL_Columns['stdClass']))
            return array_map(function ($args) use ($schemaClass) {
                return new $schemaClass($args);
            }, $lines);
        else
            return $lines;
    }
    public function count($schemaClass = null, $safe_conditions = null, array $unsafe_conditions = null)
    {
        if ($schemaClass == null) $schemaClass = $this->table;
        $lines = $this->select([], $schemaClass, $safe_conditions, $unsafe_conditions, ['overwrite' => 'COUNT(*) as num', 'stdClass' => true]);
        return $lines[0]->num * 1; // * 1 for type conversion
    }
}
