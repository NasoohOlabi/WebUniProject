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

        simpleLog('Running : "' . $sql . '"');

        $query->execute();
        return $query->fetchAll();
    }

    /**
     * delete certain record from database by the table id
     */
    public function deleteById($id)
    {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :table_id";

        simpleLog('Running : "' . $sql . '"');

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
        $schemaClass = get_class($this->table);
        $result = $this->select([], ["$this->table"], ['id' => $id]);

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

        simpleLog('Running : "' . $sql . '"');

        $this->db->prepare($sql)->execute($bindings);
        return new Either\Result();
    }

    function experimental_insert($object)
    {
        $schemaClass = get_class($object);
        $columns = $schemaClass::SQL_Columns();

        unset($columns['id']); // it's auto incremented

        $values = implode(", ", array_map(function ($column_name) use ($object) {
            return $object[$column_name];
        }, $columns));
        $columns_names = implode(", ", $columns);

        $sql = "INSERT INTO `$schemaClass`($columns_names) VALUES ($values)";
        simpleLog('Running : "' . $sql . '"');
        return $this->db->prepare($sql)->execute();
    }

    private static function _is_term($x)
    {
        return is_array($x) && count($x) == 1 && !is_array($x[array_keys($x)[0]]);
    }
    private static function _parse_term($x)
    {
        $key = array_keys($x)[0];
        return "$key = $x[$key]";
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
     * @param [type] $conditions
     * @return void
     */
    private static function _parse_conditions_from_cnf($conditions)
    {
        if (!is_array($conditions)) return;
        // parsing the conditions 
        if (BaseModel::_is_term($conditions))
            return BaseModel::_parse_term($conditions);
        $Acc = [];
        foreach ($conditions as $value_1d) {
            if (BaseModel::_is_term($value_1d)) {
                $Acc[] = BaseModel::_parse_term($value_1d);
            } elseif (is_array($value_1d)) {
                $bracket = [];
                foreach ($value_1d as $value_2d) {
                    if (BaseModel::_is_term($value_2d)) {
                        $bracket[] = BaseModel::_parse_term($value_2d);
                    } else throw new Exception("Conditions Parse Error: parsing `" . json_encode($conditions) . "`");
                }
                $Acc[] = implode(" OR ", $bracket);
            } else
                throw new Exception("Conditions Parse Error: parsing `" . json_encode($conditions) . "`");
        }
        $Acc = '(' . implode(") AND (", $Acc) . ')';
        return $Acc;
    }
    private static function _parse_conditions($conditions)
    {
        $answer = BaseModel::_parse_conditions_from_cnf($conditions);
        return $answer;
    }
    private static function _alias_schemaClass_columns_with_table_name($argument)
    {
        if (is_string($argument)) {
            // columns with their aliases ex: exam.id as exam_id
            $schemaClass = $argument;
            $columns = [];
            // ie: exam, topic, question
            $model = strtolower($schemaClass);
            foreach ($model::SQL_Columns() as  $value) {
                $columns[] = "{$model}.$value as {$model}_$value";
            }

            return implode(', ', $columns);
        } elseif (is_array($argument)) {
            // columns with their aliases ex: exam.id as exam_id
            $columns = [];
            $schemaClasses = $argument;
            foreach ($schemaClasses as $model) {
                $model = strtolower($model);
                foreach ($model::SQL_Columns() as  $value) {
                    $columns[] = "{$model}.$value as {$model}_$value";
                }
            }
            return implode(', ', $columns);
        }
    }

    /**
     * joins the model table with models from $schemaClasses based on conditions in $conditions
     * @example $conditions = [EXAM::SUBJECT_ID => Subject::ID]
     *
     * @param [tablenames] $schemaClasses
     * @param [phpModelClasses' constants] $conditions
     * @return SQL_answer
     */
    public function join($schemaClasses, $conditions, $wrapper = null)
    {
        if ($wrapper == null)
            $wrapper = $schemaClasses[0];
        // columns with their aliases ex: exam.id as exam_id
        $columns = BaseModel::_alias_schemaClass_columns_with_table_name($schemaClasses);


        // insert tables names with JOIN ex: `exam` JOIN `subjects`
        $tables = implode(" JOIN ", array_map(function ($model) {
            return "`$model`";
        }, $schemaClasses));


        $conditions = BaseModel::_parse_conditions($conditions);



        $order = implode(", ", array_map(function ($model) {
            return str_replace(".", "_", $model::id);
        }, $schemaClasses));

        $sql = "SELECT $columns FROM $tables ON $conditions ORDER BY $order;";

        simpleLog("Running : $sql");

        $query = $this->db->prepare($sql);
        $query->execute();
        $lines = $query->fetchAll();

        return (count($lines)) ?
            array_map(function ($args) use ($wrapper) {
                return new $wrapper($args,  strtolower($wrapper) . "_");
            }, $lines) :
            new Exception("No Exams available");
    }

    public function select($columns, $schemaClass, $conditions = null)
    {
        if (!is_string($schemaClass)) throw new Exception("Invalid table name $schemaClass should be string.");

        if (is_array($columns) && count($columns) == 0)
            $columns = "*";

        if ($conditions == null) {
            $sql = "SELECT $columns FROM $schemaClass;";
        } else {
            $conditions = BaseModel::_parse_conditions($conditions);

            $sql = "SELECT $columns FROM $schemaClass WHERE $conditions ;";
        }
        simpleLog("Running : $sql");

        $query = $this->db->prepare($sql);
        $query->execute();
        $lines = $query->fetchAll();
        $lines = array_map(function ($args) use ($schemaClass) {
            return new $schemaClass($args);
        }, $lines);
        if (count($lines))
            return $lines;
        else
            new Exception("No records match $sql");
    }
    public function count($schemaClass = null, $conditions = null)
    {
        if ($schemaClass == null) $schemaClass = $this->table;
        if (!is_string($schemaClass)) throw new Exception("Invalid table name $schemaClass should be string.");

        if ($conditions == null) {
            $sql = "SELECT COUNT(*) as num FROM $schemaClass;";
        } else {
            $conditions = BaseModel::_parse_conditions($conditions);
            $sql = "SELECT COUNT(*) as num FROM $schemaClass WHERE $conditions ;";
        }
        simpleLog("Running : $sql");

        $query = $this->db->prepare($sql);
        $query->execute();
        $lines = $query->fetchAll();
        return $lines[0]->num * 1;
    }
}
