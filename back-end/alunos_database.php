<?php
    //verifica se a sessão do PHP foi inicializada
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();

    //inclui as bibliotecas para serem utilizadas
    //biblioteca da classe aluno
    require_once 'aluno.php'; 
    //biblioteca da classe do banco de dados mySQL
    require_once 'database.php';

    //classe que representa um banco de dados de alunos que armazena seus dados em um arquivo .CV
    class AlunosCVDatabase 
    {
        //constante que armazena o nome do arquivo .CV
        private const ARQUIVO_CV = 'alunos.csv';
        //constante que armazena as colunas do banco de dados
        private const HEADERS = array('id', 'nome', 'idade', 'turma', 'texto_url', 'desenho_url');
        //endereço do arquivo .CV
        private $arquivoEndereco;

        function __construct(
            $localPath //Endereço local da pasta do servidor
        )
        {
            $this->arquivoEndereco = $localPath . AlunosCVDatabase::ARQUIVO_CV;
            $this->verificarArquivo();
        }

        //cadastra o aluno no banco de dados
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

        //obtém o novo ID fornecido a um aluno novo
        function obterNovoID()
        {
            $arquivo = fopen($this->arquivoEndereco, 'r');
            $linha = fgets($arquivo);
            fclose($arquivo);

            $linha = str_replace('NOVO_ID ', '', $linha);
            $linha = str_replace('\n', '', $linha);

            return intval($linha);
        }

        //atualiza o número do novo ID fornecido a um aluno novo
        function atualizarNovoID($novoID)
        {
            $arquivo = fopen($this->arquivoEndereco, 'r+');
            fputs($arquivo, "NOVO_ID $novoID" . PHP_EOL);
            fclose($arquivo);
        }

        //retorna uma lista com os alunos cadastrados
        function obterListaAlunos()
        {
            $alunos = array();
            $arquivo = fopen($this->arquivoEndereco, 'r');

            //pula a leitura das duas primeiras linhas do arquivo que não possuem cadastro de alunos
            for ($i = 0; $i < 2; $i++) fgets($arquivo);
            
            while ($dadosAluno = fgetcsv($arquivo))
            {
                array_push($alunos, new Aluno(
                    $dadosAluno[0], //id
                    $dadosAluno[1], //nome
                    $dadosAluno[2], //idade
                    $dadosAluno[3], //turma
                    $dadosAluno[5], //endereço do arquivo de texto
                    $dadosAluno[4]  //endereço do arquivo da imagem
                ));
            }

            return $alunos;
        }

        //remove o aluno com o ID especificado
        function removerAluno($id)
        {
            $arquivo = fopen($this->arquivoEndereco, 'r+');
            //pula as duas linhas que não possuem cadastro de alunos
            for ($i = 0; $i < 2; $i++) fgets($arquivo);

            //variável que indica a linha a ser removida
            $linhaRemover = '';
            //itera por cada aluno
            while ($linha = fgets($arquivo))
            {
                $idAluno = intval(explode(',', $linha)[0]);

                //verifica se o id do aluno é o mesmo id especificado
                if ($idAluno === $id)
                {
                    //indica a linha a ser removida
                    $linhaRemover = $linha;
                    break;
                }
            }

            fclose($arquivo);

            $arquivoTexto = file_get_contents($this->arquivoEndereco);
            //remova a linha referente ao aluno
            $arquivoTexto = str_replace($linhaRemover, '', $arquivoTexto);
            //atualiza o conteúdo do arquivo sem a linha
            file_put_contents($this->arquivoEndereco, $arquivoTexto);
        }

        //função que remove todos os cadastros do banco de dados
        function limparDatabase()
        {
            $novoID = $this->obterNovoID();

            $arquivo = fopen($this->arquivoEndereco, 'w');
            fputs($arquivo, "NOVO_ID $novoID" . PHP_EOL);
            fputcsv($arquivo, AlunosCVDatabase::HEADERS);
            fclose($arquivo);
        }

        /*
            função que verifica se o arquivo .CV existe
            e o cria com as configurações padrão caso contrário
        */
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

    //Classe que representa o banco de dados SQL dos alunos
    class AlunosSQLDatabase extends Database
    {
        function __construct($hostname, $username, $password, $localPath)
        {
            parent::__construct(
                $hostname, //endereço do banco de dados
                $username, //usuário do banco de dados
                $password, //senha do banco de dados
                'alunos_database', //nome do banco de dados
                $localPath . 'database-config.sql' //endereço do arquivo de configuração do banco de dados
            );

            //armazena as tabelas dentro deste objeto
            $this->initTables();
        }

        //método que armazena dados das tabelas dentro deste objeto
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

        //método que cadastra o aluno no banco de dados
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

        //método que remove o aluno com o id especificado
        function removerAluno($id)
        {
            parent::removeRow('alunos', 'id', $id);
        }

        //método que retorna um array com os alunos cadastrados
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

        //método que retorna o novo ID fornecido a um aluno novo
        function obterNovoID()
        {
            return parent::getNewId('alunos');
        }
    }

    //Classe que representa um banco de dados de alunos
    class AlunosDatabase
    {
        //tempo até a próxima verificação do banco de dados SQL no caso de falha
        const FAIL_ALLOW_TIME = 120;
        //banco de dados a ser usado (SQL ou CV)
        private $database = null;

        function __construct($hostname, $username, $password, $local_path)
        {
            //endereço local dos arquivos do servidor
            $this->localPath = $local_path;

            //verifica se o banco de dados sql falhou e não ultrapassou o tempo de verificação
            if (
                isset($_SESSION['db-fail-time']) && 
                time() - $_SESSION['db-fail-time'] < AlunosDatabase::FAIL_ALLOW_TIME
            )
            {
                $this->database = new AlunosCVDatabase($local_path);
            }
            else
            {
                //tenta se conectar ao banco de dados SQL
                try 
                {
                    $this->database = new AlunosSQLDatabase($hostname, $username, $password, $local_path);
                    
                    //sincroniza os bancos de dados SQL e CV caso tenha falhado anteriormente
                    if (isset($_SESSION['db-fail-time']))
                    {
                        $this->sincronizarBancos();
                    }


                    unset($_SESSION['db-fail-time']);
                }
                //usa o banco de dados CV caso a conexão com o banco de dados SQL falhe
                catch (mysqli_sql_exception $ignored)
                {
                    $this->database = new AlunosCVDatabase($local_path);
                    $_SESSION['db-fail-time'] = time();
                }
            }
        }

        //método que cadastra um aluno no banco de dados
        function cadastrarAluno($aluno)
        {
            return $this->database->cadastrarAluno($aluno);
        }

        //método que retorna um array com os alunos cadastrados
        function obterListaAlunos()
        {
            return $this->database->obterListaAlunos();
        }

        //método que retorna o aluno com o ID especificado
        function obterAluno($id)
        {
            foreach ($this->database->obterListaAlunos() as $aluno)
            {
                if ($aluno->getId() == $id) {
                    return $aluno;
                }
            }

            return null;
        }

        //método que remove um aluno do banco de dados
        function removerAluno($id)
        {
            $this->database->removerAluno($id);
        }

        //método que retorna o ID fornecido a um aluno novo
        function obterNovoID()
        {
            return $this->database->obterNovoID();
        }

        //método que sincroniza o banco de dados SQL com o banco de dados CV e depois limpa o banco CV
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