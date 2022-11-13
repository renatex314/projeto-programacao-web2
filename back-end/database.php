<?php
    define('TIPO_TEXTO', 'STRING');
    define('TIPO_NUMERO', 'NUMBER');

    class Column
    {
        private $name;
        private $type;

        function __construct($name, $type)
        {
            $this->name = $name;
            $this->type = $type;
        }

        function getName()
        {
            return $this->name;
        }

        function getType()
        {
            return $this->type;
        }

    }

    class Row
    {
        private $data;

        function __construct()
        {
            $this->data = array();
        }

        function addColumnData($column, $data)
        {
            $value = strcmp($column->getType(), TIPO_TEXTO) == 0 ? "'" . $data . "'" : $data;
            $this->data[$column->getName()] = $value;
        }

        function getColumnData($columnName)
        {
            $value = $this->data[$columnName];
            $value = is_string($value) ? trim($value, '\'') : $value;
        
            return $value;
        }

        function getColumnNamesArray()
        {
            return array_keys($this->data);
        }

        function getColumnValuesArray()
        {
            return array_values($this->data);
        }

        function getData()
        {
            return $this->data;
        }
    }

    class Table 
    {
        private $name;
        private $columns;

        function __construct($name)
        {
            $this->name = $name;
            $this->columns = array();
        }

        function addColumn($column)
        {
            array_push($this->columns, $column);
        }

        function getName()
        {
            return $this->name;
        }

        function getColumns()
        {
            return $this->columns;
        }

    }

    class Database 
    {
        private $databaseName;
        private $tables;
        private $connection;

        function __construct($hostname, $username, $password, $databaseName, $config_file_path)
        {
            $this->connection = mysqli_connect(
                $hostname,
                $username,
                $password
            );

            $this->tables = array();
            $this->databaseName = $databaseName;

            mysqli_set_charset($this->connection, 'utf8mb4');
            $this->checkDatabaseExistence($config_file_path);
        }

        private function databaseExists()
        {
            $query = mysqli_query(
                $this->connection, 
                "SELECT SCHEMA_NAME 
                    FROM INFORMATION_SCHEMA.SCHEMATA 
                 WHERE SCHEMA_NAME = '$this->databaseName';
                "
            );

            if (mysqli_fetch_array($query))
            {
                return true;
            }

            return false;
        }

        private function checkDatabaseExistence($config_file_path)
        {
            if (!$this->databaseExists())
            {
                $query_string = file_get_contents($config_file_path);
                mysqli_multi_query($this->connection, $query_string);

                while (mysqli_more_results($this->connection))
                {
                    mysqli_next_result($this->connection);
                }
            }
            else
            {
                mysqli_select_db($this->connection, $this->databaseName);
            }
        }

        function addTable($table)
        {
            array_push($this->tables, $table);
        }

        function getTable($tableName)
        {
            foreach ($this->tables as $table)
            {
                if ($table->getName() == $tableName) return $table;
            }

            return null;
        }

        function getColumn($tableName, $columnName)
        {
            foreach ($this->tables as $table)
            {
                if ($table->getName() === $tableName)
                {
                    foreach ($table->getColumns() as $column)
                    {
                        if ($column->getName() === $columnName)
                        {
                            return $column;
                        }
                    }
                }
            }

            return null;
        }

        function pushRow($tableName, $row)
        {
            $query_str = "INSERT INTO $tableName(";

            $columnNames = $row->getColumnNamesArray();
            $columnValues = $row->getColumnValuesArray();

            $columnsCount = count($columnNames);

            for ($i = 0; $i < $columnsCount - 1; $i++)
            {
                $query_str .= $columnNames[$i] . ', ';
            }

            $query_str .= $columnNames[$columnsCount - 1] . ') VALUES (';
        
            for ($i = 0; $i < $columnsCount - 1; $i++)
            {
                $query_str .= $columnValues[$i] . ', ';
            }

            $query_str .= $columnValues[$columnsCount - 1] . ');';

            mysqli_query($this->connection, $query_str);
            return $this->connection->insert_id;
        }

        function removeRow($tableName, $idFieldName, $id)
        {
            mysqli_query($this->connection, "DELETE FROM $tableName WHERE $idFieldName LIKE $id");
        }

        function getRows($tableName)
        {
            $rows = array();
            $columns = $this->getTable($tableName)->getColumns();

            $query_str = "SELECT * FROM $tableName;";
            $query = mysqli_query($this->connection, $query_str);
            
            while($rowData = mysqli_fetch_array($query))
            {
                $row = new Row();

                for ($i = 0; $i < count($columns); $i++)
                {
                    $row->addColumnData(
                        $columns[$i],
                        $columns[$i]->getType() == TIPO_TEXTO ? $rowData[$i] : (int) $rowData[$i]
                    );
                }

                array_push($rows, $row);
            }

            return $rows;
        }

        protected function getNewId($table)
        {
            $query = mysqli_query($this->connection, "
                SELECT AUTO_INCREMENT
                FROM INFORMATION_SCHEMA.TABLES 
                WHERE TABLE_SCHEMA = '$this->databaseName'
                AND TABLE_NAME = '$table'
            ");
            
            return $query->fetch_object()->AUTO_INCREMENT;
        }

        function __destruct()
        {
            mysqli_close($this->connection);
        }

    }
?>