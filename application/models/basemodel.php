<?php
require_once 'application/models/core/AccessDeniedException.php';
require_once 'basemodel_util.php';
class BaseModel
{
    /**
     * Every model needs a database connection, passed to the model
     * @param object $db A PDO database connection
     */
    protected $table;
    function __construct($db, $table = 'DUAL')
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
        sessionUserHasPermissions(['read_' . $this->table]);

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
        sessionUserHasPermissions(['delete_' . $this->table]);

        $sql = "DELETE FROM " . $this->table . " WHERE id = :table_id";

        simpleLog('BaseModel::deleteById Running : "' . $sql . '"');

        $query = $this->db->prepare($sql);
        $query->execute(array(':table_id' => $id));
    }
    /**
     * delete certain record from database by the table id
     */
    public function wipeByIds(string $schemaClass, $ids)
    {
        sessionUserHasPermissions(['delete_' . strtolower($schemaClass)]);

        $ids = array_filter($ids, function ($id) {
            return is_numeric($id);
        });
        $sql_syntax = array_map(function ($id) {
            return "id = ?";
        }, $ids);
        $sql_syntax = implode(" OR ", $sql_syntax);

        $sql = "DELETE FROM `$schemaClass` WHERE $sql_syntax";


        simpleLog('BaseModel::wipeByIds Running : "' . $sql . '" Bindings : ' . json_encode($ids), 'basemodel');
        simpleLog('BaseModel::wipeByIds Running : "' . $sql . '" Bindings : ' . json_encode($ids));
        $query = $this->db->prepare($sql);
        $query->execute($ids);
    }
    /**
     * returns Option with the row with this particular id
     *
     * @param [int] $id
     * @return Option
     */
    public function getById($id, string $t = null)
    {
        $table = $t ?? $this->table;

        sessionUserHasPermissions(['read_' . $table]);

        $result = $this->select([], "$table", ["$table.id" => $id]);

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


    function insert(&$object)
    {
        $schemaClass = get_class($object);

        if (!sessionUserHasPermissions(['create_' . strtolower($schemaClass)])) {
            throw new AccessDeniedException("You Can't Create " . $schemaClass . "s");
        }

        $columns = $schemaClass::SQL_Columns();

        unset($columns[0]); // id is auto incremented

        $values =  array_map(function ($column_name) use ($object) {
            if (property_exists($object, $column_name) && isset($object->{$column_name}))
                return $object->{$column_name};
            else
                return null;
        }, $columns);


        foreach ($columns as $key => $value) {
            if ($values[$key] === null) {
                unset($columns[$key]);
                unset($values[$key]);
            }
        }

        $columns_names = implode(", ", $columns);
        $question_marks = implode(", ", array_map(function ($arg) {
            return "?";
        }, $columns));

        $values  = array_values($values);

        $sql = "INSERT INTO `$schemaClass` ($columns_names) VALUES ($question_marks)";

        simpleLog('BaseModel::insert Running : "' . $sql . '"', 'basemodel' . " | Bindings " . json_encode($values));
        $query = $this->db->prepare($sql);

        $insert_successful = $query->execute(array_values($values));

        if ($insert_successful) {
            $object->id = $this->db->lastInsertId();
            return true;
        } else {
            $error = $query->errorInfo();
            // see if anything is output here
            simpleLog('BaseModel::create' . json_encode($error), 'basemodel');
            simpleLog('BaseModel::create' . json_encode($error));
            throw new Exception($error[2]);
        }
    }

    function update(string $schemaClass, int $id, stdClass $these)
    {
        if (!sessionUserHasPermissions(['write_' . strtolower($schemaClass)])) {
            throw new AccessDeniedException("You Can't Edit " . $schemaClass . "s");
        };

        $columns = $schemaClass::SQL_Columns();

        unset($columns[0]); // id is auto incremented and un editable
        $columns = array_values($columns);


        $columns_to_update = array_filter($columns, function ($column_name) use ($these) {
            return property_exists($these, $column_name);
        });



        $values = [];
        foreach ($columns_to_update as $col) {
            $values[] = $these->{$col};
        }

        simpleLog("Updating: " . json_encode($columns_to_update) . " | With these Values: " . json_encode($values), 'basemodel');


        $sql_syntax_columns = implode(', ', array_map(function ($col) {
            return '`' . $col . '` = ?';
        }, $columns_to_update));

        $values[] = $id * 1; // * 1 for int to string conversion adhoc

        $sql = "UPDATE `$schemaClass` SET $sql_syntax_columns WHERE id = ?";

        simpleLog('BaseModel::update Running : "' . $sql . '"' . " | Bindings " . json_encode($values), 'basemodel');

        try {
            return $this->db->prepare($sql)->execute(array_values($values));
        } catch (Exception $e) {
            simpleLog('BaseModel::update ' . json_encode($e), 'basemodel');
            simpleLog('BaseModel::update ' . json_encode($e));
            throw $e;
        }
    }

    private function __select_stmt(array $columns, array $schemaClasses, array $ON_conditions = [], array $WHERE_conditions = [], array $options = [])
    {
        $wrapper = (isset($options['wrapper'])
            && is_string($options['wrapper'])
        )
            ? $options['wrapper']
            : $schemaClasses[0];

        $override = (isset($options['override'])
            && is_bool($options['override']) && $options['override']
        );

        if (!$override)
            sessionUserHasPermissions(["read_" . strtolower($wrapper)]);


        if (count($schemaClasses) == 0) throw new Exception("No Classes to select from");

        $stamp = $_SERVER['REQUEST_TIME'] . '_' . rand(0, 1000);

        simpleLog("OP::$stamp BaseModel::__select_stmt columns: " . json_encode($columns) . " schemaClasses: " . json_encode($schemaClasses) . " ON_conditions: " . json_encode($ON_conditions) . " WHERE_conditions: " . json_encode($WHERE_conditions) . " options: " . json_encode($options), "selects");


        $limit = (isset($options['limit']) && is_numeric($options['limit']))
            ? $options['limit']
            : 100;

        $limit_sql = " LIMIT $limit ";

        $columns = (count($columns) == 0)
            ? _get_classes_dotted_columns($schemaClasses)
            : $columns;

        $columns_sql = (isset($options['overwrite columns'])
            && is_string($options['overwrite columns'])
        ) ? $options['overwrite columns']
            :  implode(", ", _alias_dotted_columns($columns));

        // insert tables names with JOIN ex: `exam` JOIN `subjects`
        $tables_sql = implode(" JOIN ", array_map(function ($schemaClass) {
            return "`$schemaClass`";
        }, $schemaClasses));

        $parsed_ON_conditions = (count($ON_conditions) > 0) ?
            _parse_conditions($ON_conditions) : '';


        $parsed_WHERE_conditions = (count($WHERE_conditions) > 0) ? _parse_WHERE_conditions($WHERE_conditions) : [];

        $WHERE_conditions_sql = (isset($parsed_WHERE_conditions['prepare']))
            ? $parsed_WHERE_conditions['prepare']
            : '';

        $unsafe_bindings = (isset($parsed_WHERE_conditions['execute']))
            ? $parsed_WHERE_conditions['execute']
            : [];

        $order_by_ids = implode(", ", array_filter(array_map(function ($schemaClass) {
            return str_replace(".", "_", $schemaClass::id);
        }, $schemaClasses), function ($elem) use ($columns_sql) {
            return str_contains($columns_sql, $elem);
        }));

        $order_by_ids_sql = (strlen($order_by_ids) > 0)
            ? "ORDER BY $order_by_ids"
            : '';

        $ON_ON_conditions = (strlen($parsed_ON_conditions) > 0)
            ? "ON $parsed_ON_conditions"
            : '';
        $WHERE_WHERE_conditions = (strlen($WHERE_conditions_sql) > 0)
            ? "WHERE $WHERE_conditions_sql"
            : '';

        $sql = "SELECT $columns_sql FROM $tables_sql $ON_ON_conditions $WHERE_WHERE_conditions $order_by_ids_sql $limit_sql;";



        simpleLog("OP::$stamp BaseModel::__select_stmt Running : $sql with bindings : " . json_encode($unsafe_bindings), "selects");


        $query = $this->db->prepare($sql);
        $query->execute($unsafe_bindings);
        $lines = $query->fetchAll();


        // simpleLog("got from db >>>>>>>>>>>> " . json_encode($lines));

        $result = (isset($options['stdClass']) && $options['stdClass'])
            ? $lines
            : array_map(function ($args) use ($wrapper) {
                return new $wrapper($args,  strtolower($wrapper) . "_");
            }, $lines);


        // simpleLog("returning >>>>>>>>>>>> " . json_encode($result));

        return $result;
    }


    public function join(
        array $schemaClasses,
        array $ON_conditions = [],
        array $WHERE_conditions = [],
        bool $override = false
    ) {
        return $this->__select_stmt([], $schemaClasses, $ON_conditions, $WHERE_conditions, ['override' => $override]);
    }
    public function select(
        array $columns,
        string $schemaClass,
        array $WHERE_conditions = [],
        int $limit = 100,
        bool $override = false
    ) {
        return $this->__select_stmt($columns, [$schemaClass], [], $WHERE_conditions, ['limit' => $limit, 'override' => $override]);
    }
    public function count(string $schemaClass = null, array $ON_conditions = [], array $WHERE_conditions = [], bool $override = false)
    {
        $schemaClass = $schemaClass ?? $this->schemaClass;

        $answer =  $this->__select_stmt([], [$schemaClass], $ON_conditions, $WHERE_conditions, ['override' => $override, 'overwrite columns' => 'COUNT(*) as num', 'stdClass' => true])[0];

        return $answer->num * 1; // * 1 for type conversion
    }
}
