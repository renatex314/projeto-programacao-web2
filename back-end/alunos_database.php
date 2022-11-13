<?php
    require_once 'aluno.php';
    require_once 'database.php';
    
    class AlunosDatabase extends Database
    {
        function __construct($hostname, $username, $password, $local_path)
        {
            parent::__construct(
                $hostname, 
                $username, 
                $password, 
                'alunos_database',
                $local_path . 'database-config.sql'
            );

            $this->initTables();
        }

        function initTables()
        {
            $alunos = new Table('alunos');
            $alunos->addColumn(new Column('id', TIPO_NUMERO));
            $alunos->addColumn(new Column('nome', TIPO_TEXTO));
            $alunos->addColumn(new Column('idade', TIPO_NUMERO));
            $alunos->addColumn(new Column('texto_url', TIPO_TEXTO));
            $alunos->addColumn(new Column('desenho_url', TIPO_TEXTO));

            parent::addTable($alunos);
        }

        function cadastrarAluno($aluno)
        {
            $row = new Row();
            $row->addColumnData(
                parent::getColumn('alunos', 'nome'), $aluno->getNome()
            );
            $row->addColumnData(
                parent::getColumn('alunos', 'idade'), $aluno->getIdade()
            );
            $row->addColumnData(
                parent::getColumn('alunos', 'texto_url'), $aluno->getTextoURL()
            );
            $row->addColumnData(
                parent::getColumn('alunos', 'desenho_url'), $aluno->getDesenhoURL()
            );
            
            return parent::pushRow('alunos', $row);
        }

        function obterListaAlunos()
        {
            $listaAlunos = array();

            foreach(parent::getRows('alunos') as $row)
            {
                $aluno = new Aluno(
                    $row->getColumnData('id'),
                    $row->getColumnData('nome'),
                    $row->getColumnData('idade'),
                    $row->getColumnData('desenho_url'),
                    $row->getColumnData('texto_url')
                );

                array_push($listaAlunos, $aluno);
            }

            return $listaAlunos;
        }

        function obterNovoID()
        {
            return parent::getNewId('alunos');
        }
    }
?>