<?php
    //inclui as bibliotecas para serem utilizadas
    require_once 'aluno.php'; //biblioteca da classe Aluno
    require_once 'alunos_database.php'; //biblioteca que contém as classes do banco de dados de alunos
    require_once 'alunos_arquivos.php'; //biblioteca que contém a classe responsável pelo gerenciamento dos arquivos

    //define as variáveis globais que representam a localização do da pasta do servidor
    //pasta global (aberta para acesso remoto)
    define("PATH_GLOBAL", 'http://localhost' . str_replace('servidor.php', '', $_SERVER['REQUEST_URI']));
    //pasta local (aberta para acesso do servidor)
    define("PATH_LOCAL", str_replace('\\', '/', dirname(__FILE__) . '\\'));

    //banco de dados de alunos
    $alunosDatabase = new AlunosDatabase('localhost', 'root', '', PATH_LOCAL); 
    //gerenciador de arquivos
    $alunosArquivos = new AlunosArquivos(PATH_LOCAL, PATH_GLOBAL); 

    //verifica se a opção de cadastrar está ativa
    if (isset($_SESSION["cadastro"])) 
    {
        //obtem os dados do aluno
        $novoID = $alunosDatabase->obterNovoID();
        $nome = $_SESSION['nome'];
        $idade = $_SESSION['idade'];
        $turma = $_SESSION['turma'];
        $textoConteudo = $_SESSION['texto'];
        $desenhoConteudo = $_SESSION['desenho'];

        //salva os arquivos e retorna a URL deles
        $desenhoURL = $alunosArquivos->salvarDesenho($desenhoConteudo, $novoID);
        $textoURL = $alunosArquivos->salvarTexto($textoConteudo, $novoID);

        //cadastra um novo aluno no banco de dados
        $alunosDatabase->cadastrarAluno(new Aluno(
            $novoID,
            $nome,
            $idade,
            $turma,
            $desenhoURL,
            $textoURL
        ));

        //apaga os dados das variáveis para sinalizar que o cadastro foi realizado
        unset($_SESSION['nome']);
        unset($_SESSION['idade']);
        unset($_SESSION['texto']);
        unset($_SESSION['turma']);
        unset($_SESSION['desenho']);
        unset($_SESSION['cadastro']);
    }
    else if (isset($_SESSION['remover_id'])) //verifica se a opção de remover aluno está ativa
    {
        //obtem o id do aluno a ser removido
        $id = intval($_SESSION['remover_id']);
        //obtem a url dos arquivos e exclui eles
        $aluno = $alunosDatabase->obterAluno($id);
        $alunosArquivos->excluirArquivosAluno(
            $aluno->getTextoURL(),
            $aluno->getDesenhoURL()
        );
        //remove o aluno do banco de dados
        $alunosDatabase->removerAluno($id);

        //apaga a variavel remover_id para indicar que já foi removido
        unset($_SESSION['remover_id']);
    }
    else
    {
        //obtem a lista de alunos
        $listaAlunos = $alunosDatabase->obterListaAlunos();
        //converte a url local dos arquivos dos alunos para url global
        foreach ($listaAlunos as $aluno) $aluno->converterParaURLGlobal($alunosArquivos);

        //exibe a lista de alunos em formato JSON
        echo json_encode($listaAlunos);
    }
?>