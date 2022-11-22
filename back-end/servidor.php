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
        $turma = $_SESSION['turma'];
        $textoConteudo = $_SESSION['texto'];
        $desenhoConteudo = $_SESSION['desenho'];

        $desenhoURL = $alunosArquivos->salvarDesenho($desenhoConteudo, $novoID);
        $textoURL = $alunosArquivos->salvarTexto($textoConteudo, $novoID);

        $alunosDatabase->cadastrarAluno(new Aluno(
            $novoID,
            $nome,
            $idade,
            $turma,
            $desenhoURL,
            $textoURL
        ));

        unset($_SESSION['nome']);
        unset($_SESSION['idade']);
        unset($_SESSION['texto']);
        unset($_SESSION['turma']);
        unset($_SESSION['desenho']);
        unset($_SESSION['cadastro']);
    }
    else if (isset($_SESSION['remover_id']))
    {
        $id = intval($_SESSION['remover_id']);
        $aluno = $alunosDatabase->obterAluno($id);
        $alunosArquivos->excluirArquivosAluno(
            $aluno->getTextoURL(),
            $aluno->getDesenhoURL()
        );
        $alunosDatabase->removerAluno($id);

        unset($_SESSION['remover_id']);
    }
    else
    {
        $listaAlunos = $alunosDatabase->obterListaAlunos();
        foreach ($listaAlunos as $aluno) $aluno->converterParaURLGlobal($alunosArquivos);

        echo json_encode($listaAlunos);
    }
?>