<?php
    //arquivo que contém as classes utilizadas para representar um banco de dados

    //define as váriaveis globais que representam tipos de dados do banco de dados
    define('TIPO_TEXTO', 'STRING');
    define('TIPO_NUMERO', 'NUMBER');

    //classe que representa uma coluna
    class Column
    {
        private $name; //nome da coluna
        private $type; //tipo da coluna

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

    //classe que representa uma linha com dados na tabela
    class Row
    {
        private $data; //variável que contém o conjunto de dados

        function __construct()
        {
            $this->data = array();
        }

        //método que adiciona um dado a linha, associando-o ao nome de sua coluna no array $data
        function addColumnData($column, $data)
        {
            //valor formatado para ser armazenado, caso seja texto é adicionado aspas a ele
            $value = strcmp($column->getType(), TIPO_TEXTO) == 0 ? "'" . $data . "'" : $data;
            //associa o dado ao nome de sua coluna
            $this->data[$column->getName()] = $value;
        }

        //método que retorna um dado da linha associado ao nome de sua coluna
        function getColumnData($columnName)
        {
            //obtem o dado
            $value = $this->data[$columnName];
            //verifica se é texto, se for retira as aspas adicionais
            $value = is_string($value) ? trim($value, '\'') : $value;
        
            return $value;
        }

        //método que retorna o nome das colunas da linha
        function getColumnNamesArray()
        {
            return array_keys($this->data);
        }

        //método que retorna uma lista com os dados da linha
        function getColumnValuesArray()
        {
            return array_values($this->data);
        }

        //método que retorna o array de dados
        function getData()
        {
            return $this->data;
        }
    }

    //classe que representa uma tabela
    class Table 
    {
        //nome da tabela
        private $name;
        //lista de colunas na tabela
        private $columns;

        function __construct($name)
        {
            $this->name = $name;
            $this->columns = array();
        }

        //método que adiciona uma coluna
        function addColumn($column)
        {
            array_push($this->columns, $column);
        }

        //método que retorna o nome da tabela
        function getName()
        {
            return $this->name;
        }

        //método que retorna as colunas armazenadas
        function getColumns()
        {
            return $this->columns;
        }

    }

    //classe que representa um banco de dados mySQL
    //utiliza a extensão mysqli para realizar a interação com o banco de dados
    class Database 
    {
        //nome do banco de dados
        private $databaseName;
        //lista com as tabelas do banco de dados
        private $tables;
        //variável que armazena a conexão com o banco de dados
        private $connection;

        function __construct(
            $hostname,          //endereço do banco de dados 
            $username,          //nome de usuário do banco de dados
            $password,          //senha do banco de dados
            $databaseName,      //nome do banco de dados
            $config_file_path   //endereço do arquivo de configuração do banco de dados
        )
        {
            //realize a conexão com o banco de dados
            $this->connection = mysqli_connect(
                $hostname,
                $username,
                $password
            );

            //verifia se a conexão foi bem sucedida
            if ($this->connection === false)
            {
                return false;
            }

            $this->tables = array();
            $this->databaseName = $databaseName;

            //configura a codificação a ser utilizada pela extensão mysqli
            mysqli_set_charset($this->connection, 'utf8mb4');
            //verifica a existência do banco de dados e o cria caso não exista
            $this->checkDatabaseExistence($config_file_path);
        }

        //método que retorna true caso o banco de dados exista e false caso contrário
        private function databaseExists()
        {
            //query para ser realizada para o mySQL
            $query = mysqli_query(
                $this->connection, 
                "SELECT SCHEMA_NAME 
                    FROM INFORMATION_SCHEMA.SCHEMATA 
                 WHERE SCHEMA_NAME = '$this->databaseName';
                "
                /*
                    verifica se há algum banco de dados com o 
                    mesmo nome na tabela com nomes de banco de dados no mySQL
                */
            );

            //verifica se houve pelo menos um retorno
            if (mysqli_fetch_array($query))
            {
                return true;
            }

            return false;
        }

        //método que verifica se o banco de dados existe e o cria caso não exista
        private function checkDatabaseExistence($config_file_path)
        {
            //verifica se o banco de dados não existe
            if (!$this->databaseExists())
            {
                //obtem o arquivo de configuração do banco de dados
                $query_string = file_get_contents($config_file_path);
                //envia a query para o mySQL
                mysqli_multi_query($this->connection, $query_string);

                /*
                    OBS: este código é necessário para limpar os resultados da
                    query anterior para que não afetem as próximas queries
                */
                //verifica se há resultados da query anterior
                while (mysqli_more_results($this->connection))
                {
                    //itera a cada query até que não exista mais queries pendentes
                    mysqli_next_result($this->connection);
                }
            }
            else
            {
                //seleciona o banco de dados para ser utilizado no mySQL
                mysqli_select_db($this->connection, $this->databaseName);
            }
        }

        //método que adiciona uma tabela ao array tables
        function addTable($table)
        {
            array_push($this->tables, $table);
        }

        //método que retorna a tabela com o mesmo nome que o parâmetro tableName
        function getTable($tableName)
        {
            //itera em cada tabela
            foreach ($this->tables as $table)
            {
                //se tiver o mesmo nome, retorna a tabela
                if ($table->getName() === $tableName) return $table;
            }

            return null;
        }

        //método que retorna uma coluna
        function getColumn(
            $tableName, //nome da tabela 
            $columnName //nome da coluna
        )
        {
            $table = $this->getTable($tableName);
            //itera por cada coluna da tabela
            foreach ($table->getColumns() as $column)
            {
                //retorna a coluna caso tenha o mesmo nome
                if ($column->getName() === $columnName)
                {
                    return $column;
                }
            }

            return null;
        }

        //método que adiciona uma linha ao banco de dados
        function pushRow($tableName, $row)
        {
            //constrói a query para ser executada no banco de dados
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
            //constrói a query para ser executada no banco de dados

            //executa a query
            mysqli_query($this->connection, $query_str);
            return $this->connection->insert_id; //retorna o id da linha adicionada
        }

        //remove uma linha onde o campo do id tenha o mesmo valor do parametro id
        function removeRow($tableName, $idFieldName, $id)
        {
            mysqli_query($this->connection, "DELETE FROM $tableName WHERE $idFieldName LIKE $id");
        }

        //retorna as linhas salvas no banco de dados
        function getRows($tableName)
        {
            //array de linhas
            $rows = array();
            //array de colunas
            $columns = $this->getTable($tableName)->getColumns();

            //query para retornar todas as linhas
            $query_str = "SELECT * FROM $tableName;";
            //executa a query
            $query = mysqli_query($this->connection, $query_str);

            //itera por cada linha
            while($rowData = mysqli_fetch_array($query))
            {
                //cria uma nova linha
                $row = new Row();

                //itera por cada coluna
                for ($i = 0; $i < count($columns); $i++)
                {
                    //adiciona um dado a linha
                    $row->addColumnData(
                        //coluna associada ao dado
                        $columns[$i],
                        //dado formatado, caso seja um número converte para inteiro
                        $columns[$i]->getType() === TIPO_TEXTO ? $rowData[$i] : (int) $rowData[$i]
                    );
                }

                //adiciona a linha ao array de linhas
                array_push($rows, $row);
            }

            //retorna o array de linhas
            return $rows;
        }

        //retorna o novo ID a ser fornecido a uma linha a ser adicionada à tabela
        protected function getNewId($table)
        {
            //envia a query para o mySQL
            $query = mysqli_query($this->connection, "
                SELECT AUTO_INCREMENT
                FROM INFORMATION_SCHEMA.TABLES 
                WHERE TABLE_SCHEMA = '$this->databaseName'
                AND TABLE_NAME = '$table'
            ");
            
            //retorna o novo ID
            return $query->fetch_object()->AUTO_INCREMENT;
        }

        //fecha a conexão com o banco de dados ao destruir a classe
        function __destruct()
        {
            mysqli_close($this->connection);
        }

    }
?>