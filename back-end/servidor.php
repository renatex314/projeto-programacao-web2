<?php
    require_once 'aluno.php';
    require_once 'alunos_database.php';
    require_once 'alunos_arquivos.php';

    define("PATH_GLOBAL", 'http://localhost' . str_replace('servidor.php', '', $_SERVER['REQUEST_URI']));
    define("PATH_LOCAL", str_replace('\\', '/', dirname(__FILE__) . '\\'));

    $alunosDatabase = new AlunosDatabase('localhost', 'root', '', PATH_LOCAL);
    $alunosArquivos = new AlunosArquivos(PATH_LOCAL, PATH_GLOBAL);

    if (isset($_SESSION["cadastro"]))
    {
        $novoID = $alunosDatabase->obterNovoID();
        $nome = $_SESSION['nome'];
        $idade = $_SESSION['idade'];
        $textoConteudo = $_SESSION['texto'];
        $desenhoConteudo = $_SESSION['desenho'];

        $desenhoURL = $alunosArquivos->salvarDesenho($desenhoConteudo, $novoID);
        $textoURL = $alunosArquivos->salvarTexto($textoConteudo, $novoID);

        $alunosDatabase->cadastrarAluno(new Aluno(
            $novoID,
            $nome,
            $idade,
            $desenhoURL,
            $textoURL
        ));

        unset($_SESSION['nome']);
        unset($_SESSION['idade']);
        unset($_SESSION['texto']);
        unset($_SESSION['desenho']);
        unset($_SESSION['cadastro']);
    }
    else
    {
        $listaAlunos = $alunosDatabase->obterListaAlunos();
        foreach ($listaAlunos as $aluno) $aluno->converterParaURLGlobal($alunosArquivos);

        echo json_encode($listaAlunos);
    }
?>