<?php
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();

    require_once 'aluno.php';
    require_once 'database.php';
    
    class AlunosCVDatabase 
    {
        private const ARQUIVO_CV = 'alunos.csv';
        private const HEADERS = array('id', 'nome', 'idade', 'turma', 'texto_url', 'desenho_url');
        private $arquivoEndereco;

        function __construct($localPath)
        {
            $this->arquivoEndereco = $localPath . AlunosCVDatabase::ARQUIVO_CV;
            $this->verificarArquivo();
        }

        function cadastrarAluno($aluno)
        {  
            $alunoDados = array(
                $this->obterNovoID(),
                $aluno->getNome(),
                $aluno->getIdade(),
                $aluno->getTurma(),
                $aluno->getTextoURL(),
                $aluno->getDesenhoURL()
            );
            $arquivo = fopen($this->arquivoEndereco, 'a');
            fputcsv($arquivo, $alunoDados);
            fclose($arquivo);
            
            $this->atualizarNovoID($this->obterNovoID() + 1);
        }

        function obterNovoID()
        {
            $arquivo = fopen($this->arquivoEndereco, 'r');
            $linha = fgets($arquivo);
            fclose($arquivo);

            $linha = str_replace('NOVO_ID ', '', $linha);
            $linha = str_replace('\n', '', $linha);

            return intval($linha);
        }

        function atualizarNovoID($novoID)
        {
            $arquivo = fopen($this->arquivoEndereco, 'r+');
            fputs($arquivo, "NOVO_ID $novoID" . PHP_EOL);
            fclose($arquivo);
        }

        function obterListaAlunos()
        {
            $alunos = array();
            $arquivo = fopen($this->arquivoEndereco, 'r');

            for ($i = 0; $i < 2; $i++) fgets($arquivo);
            
            while ($dadosAluno = fgetcsv($arquivo))
            {
                array_push($alunos, new Aluno(
                    $dadosAluno[0],
                    $dadosAluno[1],
                    $dadosAluno[2],
                    $dadosAluno[3],
                    $dadosAluno[5],
                    $dadosAluno[4]
                ));
            }

            return $alunos;
        }

        function removerAluno($id)
        {
            $arquivo = fopen($this->arquivoEndereco, 'r+');
            for ($i = 0; $i < 2; $i++) fgets($arquivo);

            $linhaRemover = '';
            while ($linha = fgets($arquivo))
            {
                $idAluno = intval(explode(',', $linha)[0]);

                if ($idAluno === $id)
                {
                    $linhaRemover = $linha;
                    break;
                }
            }

            fclose($arquivo);

            $arquivoTexto = file_get_contents($this->arquivoEndereco);
            $arquivoTexto = str_replace($linhaRemover, '', $arquivoTexto);
            file_put_contents($this->arquivoEndereco, $arquivoTexto);
        }

        function limparDatabase()
        {
            $novoID = $this->obterNovoID();

            $arquivo = fopen($this->arquivoEndereco, 'w');
            fputs($arquivo, "NOVO_ID $novoID" . PHP_EOL);
            fputcsv($arquivo, AlunosCVDatabase::HEADERS);
            fclose($arquivo);
        }

        private function verificarArquivo()
        {   
            if (!file_exists($this->arquivoEndereco))
            {
                $arquivo = fopen($this->arquivoEndereco, 'w');
                fputs($arquivo, 'NOVO_ID 1' . PHP_EOL);
                fputcsv($arquivo, AlunosCVDatabase::HEADERS);
                fclose($arquivo);
            }
        }
    }

    class AlunosSQLDatabase extends Database
    {
        function __construct($hostname, $username, $password, $localPath)
        {
            parent::__construct(
                $hostname, 
                $username, 
                $password, 
                'alunos_database',
                $localPath . 'database-config.sql'
            );

            $this->initTables();
        }

        function initTables()
        {
            $alunos = new Table('alunos');
            $alunos->addColumn(new Column('id', TIPO_NUMERO));
            $alunos->addColumn(new Column('nome', TIPO_TEXTO));
            $alunos->addColumn(new Column('idade', TIPO_NUMERO));
            $alunos->addColumn(new Column('turma', TIPO_TEXTO));
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
                parent::getColumn('alunos', 'turma'), $aluno->getTurma()
            );
            $row->addColumnData(
                parent::getColumn('alunos', 'texto_url'), $aluno->getTextoURL()
            );
            $row->addColumnData(
                parent::getColumn('alunos', 'desenho_url'), $aluno->getDesenhoURL()
            );
            
            return parent::pushRow('alunos', $row);
        }

        function removerAluno($id)
        {
            parent::removeRow('alunos', 'id', $id);
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
                    $row->getColumnData('turma'),
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

    class AlunosDatabase
    {
        const FAIL_ALLOW_TIME = 120;
        private $database = null;

        function __construct($hostname, $username, $password, $local_path)
        {
            $this->localPath = $local_path;

            if (
                isset($_SESSION['db-fail-time']) && 
                time() - $_SESSION['db-fail-time'] < AlunosDatabase::FAIL_ALLOW_TIME
            )
            {
                $this->database = new AlunosCVDatabase($local_path);
            }
            else
            {
                try 
                {
                    $this->database = new AlunosSQLDatabase($hostname, $username, $password, $local_path);
                    
                    if (isset($_SESSION['db-fail-time']))
                    {
                        $this->sincronizarBancos();
                    }

                    unset($_SESSION['db-fail-time']);
                }
                catch (mysqli_sql_exception $ignored)
                {
                    $this->database = new AlunosCVDatabase($local_path);
                    $_SESSION['db-fail-time'] = time();
                }
            }
        }

        function cadastrarAluno($aluno)
        {
            return $this->database->cadastrarAluno($aluno);
        }

        function obterListaAlunos()
        {
            return $this->database->obterListaAlunos();
        }

        function obterAluno($id)
        {
            foreach ($this->database->obterListaAlunos() as $aluno)
            {
                if ($aluno->getId() === $id) {
                    return $aluno;
                }
            }

            return null;
        }

        function removerAluno($id)
        {
            $this->database->removerAluno($id);
        }

        function obterNovoID()
        {
            return $this->database->obterNovoID();
        }

        function sincronizarBancos()
        {
            $bancoCV = new AlunosCVDatabase($this->localPath);
            $alunos = $bancoCV->obterListaAlunos();

            foreach ($alunos as $aluno)
            {
                $this->database->cadastrarAluno($aluno);
            }

            $bancoCV->limparDatabase();
        }
    }
?>