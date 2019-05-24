<?php

namespace Model;

use mysql_xdevapi\Exception;
use mysqli;

class MySQL
{
    /**
     * @var mysqli $mysqli
     */
    private $mysqli;

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);
        try {
            $filename = __DIR__ . '/../Config/database.json';
            if (file_exists($filename)) {
                $contents = file_get_contents($filename);
                $config = json_decode($contents, true);

                $host = $config['host'];
                $username = $config['username'];
                $passwd = $config['passwd'];
                $dbname = $config['dbname'];

                $this->mysqli = new mysqli($host, $username, $passwd, $dbname);
            } else {
                set_error("File 'database.json' not found.");
            }
        } catch (\mysqli_sql_exception $exception) {
            set_error($exception->getMessage(), $exception->getCode());
        }
    }

    function query($sql, $multi = false)
    {
        try {
            if (!empty($sql)) {
                if ($multi) {
                    $this->mysqli->multi_query($sql);
                } else {
                    return $this->mysqli->query($sql);
                }
            }
        } catch (\mysqli_sql_exception $exception) {
            set_error($exception->getMessage(), $exception->getCode());
        }
    }

    function prepare($sql, $params)
    {
        $stmt = $this->mysqli->prepare($sql);
        foreach ($params as $k => &$param) {
            $array[] =& $param;
        }
        call_user_func_array(array($stmt, 'bind_param'), $params);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * @param \mysqli_result $mysqli_result
     * @param bool $index
     * @param int $type
     * @return mixed
     */
    function fetch_all($mysqli_result, $index = false, $type = MYSQLI_ASSOC)
    {
        $results = $mysqli_result->fetch_all($type);
        if ($index !== false) {
            $end = [];
            foreach ($results as $result) {
                $end[$result[$index]] = $result;
            }
            return $end;
        }
        return $results;
    }

    /**
     * @param string $table
     * @param TableColumn[] $columns
     * @param string $extra_sql
     * @return bool
     */
    function create_table($table, $columns, $extra_sql = '')
    {
        $result = isset_get($this->fetch_all($this->query("show tables;"), 0, MYSQLI_NUM)[$table]);

        if (!$result) {
            $sql_columns = "";
            foreach ($columns as $column) {
                $sql_columns .=
                    $column->name . " " .
                    $column->type . ($column->type_size ? "($column->type_size)" : '') . " " .
                    ($column->default ? "default " . (ctype_digit($column->default) ? $column->default : ($column->type == ColumnTypes::TIMESTAMP ? $column->default : "'$column->default'")) : '') . " " .
                    ($column->auto_increment ? 'auto_increment' : '') . " " .
                    ($column->primary_key ? 'primary key' : '') . " " .
                    ($column->not_null ? 'not null' : '') . ',';
            }
            $sql_columns = trim($sql_columns, ',');

            $sql = <<<sql
CREATE TABLE IF NOT EXISTS `$table`($sql_columns);
sql;
            $this->query($sql . $extra_sql, true);
            return true;
        }
    }
}

class TableColumn
{
    public $name;
    public $type;
    public $type_size = 0;
    public $auto_increment = false;
    public $primary_key = false;
    public $not_null = false;
    public $default = null;

    /**
     * TableColumn constructor.
     * @param string $name
     * @param string $type
     * @param int $type_size
     * @param bool $not_null
     * @param int|string|null $default
     * @param bool $auto_increment
     * @param bool $primary_key
     */
    public function __construct($name, $type, $type_size = 0, $not_null = false, $default = null, $auto_increment = false, $primary_key = false)
    {
        $this->name = $name;
        $this->type = $type;
        $this->type_size = $type_size;
        $this->auto_increment = $auto_increment;
        $this->primary_key = $primary_key;
        $this->not_null = $not_null;
        $this->default = $default;
    }
}

abstract class ColumnTypes
{
    const BIGINT = 'bigint';
    const VARCHAR = 'varchar';
    const INT = 'int';
    const TIMESTAMP = 'timestamp';
    const DATE = 'date';
}