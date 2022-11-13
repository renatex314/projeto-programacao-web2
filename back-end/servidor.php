<?php
    require_once 'aluno.php';
    require_once 'alunos_database.php';
    require_once 'alunos_arquivos.php';

    define("PATH_GLOBAL", 'http://localhost' . str_replace('servidor.php', '', $_SERVER['REQUEST_URI']));
    define("PATH_LOCAL", str_replace('\\', '/', dirname(__FILE__) . '\\'));

    $alunosDatabase = new AlunosDatabase('localhost', 'root', '', PATH_LOCAL);
    $alunosArquivos = new AlunosArquivos(PATH_LOCAL, PATH_GLOBAL);

    if ($_SERVER["REQUEST_METHOD"] === 'GET')
    {
        $listaAlunos = $alunosDatabase->obterListaAlunos();
        foreach ($listaAlunos as $aluno) $aluno->converterParaURLGlobal($alunosArquivos);

        echo json_encode($listaAlunos);
    }

    if ($_SERVER["REQUEST_METHOD"] === 'POST')
    {
        $novoID = $alunosDatabase->obterNovoID();
        $nome = $_POST['nome'];
        $idade = $_POST['idade'];
        $textoEndereco = $_FILES['texto']['tmp_name'];
        $desenhoEndereco = $_FILES['desenho']['tmp_name'];

        $desenhoURL = $alunosArquivos->salvarDesenho($desenhoEndereco, $novoID);
        $textoURL = $alunosArquivos->salvarTexto($textoEndereco, $novoID);

        $alunosDatabase->cadastrarAluno(new Aluno(
            $novoID,
            $nome,
            $idade,
            $desenhoURL,
            $textoURL
        ));
        
        echo $novoID;
    }
?>