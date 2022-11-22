<?php
    define('SENHA_ADMINISTRACAO', 'acessoadministrador123');

    function senhaEstaCorreta()
    {
        return strcasecmp($_SESSION['senha_administracao'], SENHA_ADMINISTRACAO) === 0;
    }

    if (isset($_POST['senha_administracao']))
    {
        $_SESSION['senha_administracao'] = $_POST['senha_administracao'];
    }
?>

<?php if (isset($_SESSION['senha_administracao']) && !senhaEstaCorreta()) { ?>
    <p class="erro-aviso">Senha incorreta !<br>tente novamente:</p>
<?php } ?>

<?php if (!isset($_SESSION['senha_administracao']) || !senhaEstaCorreta()) { ?>
    <form action="?pagina=7" method="POST" class="login-wrapper">
        <label for="senha">Senha:</label>
        <input id="senha" name="senha_administracao" type="password" placeholder="Digite a senha de acesso...">
        <input class="botao" type="submit" value="Entrar">
    </div>
<?php } ?>

<?php if (isset($_SESSION['senha_administracao']) && senhaEstaCorreta()) {?>

    <div class="administracao-wrapper">
        <p class="administracao-texto">Lista de alunos cadastrados:</p>
        <ul class="lista-alunos"></ul>
    </div>

    <div class="visualizer-wrapper transicao escondido">
        <div id="visualizer">
            <div class="img-wrapper">
                <img src="" alt="">
            </div>
            <div class="info-wrapper">
                <div class="exit-button"></div>
                <p><br></p>
                <p id="visualizer-nome"></p>
                <p id="visualizer-idade"></p>
                <p id="visualizer-turma"></p>
                <p><br></p>
                <p>minhas férias:</p>
                <p><br></p>
                <p id="visualizer-texto"></p>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="assets/styles/visualizer.css">
    <script src="assets/scripts/visualizer.js"></script>
    <script>
        let visualizer = new Visualizer(document.querySelector('.visualizer-wrapper'));
        window.alunosUL = document.querySelector('.lista-alunos');
        window.listaAlunos = null;

        function visualizarAluno(indice) {
            visualizer.atualizarNome(window.listaAlunos[indice].nome);
            visualizer.atualizarIdade(window.listaAlunos[indice].idade);
            visualizer.atualizarTurma(window.listaAlunos[indice].turma);
            visualizer.atualizarImagem(window.listaAlunos[indice].desenhoURL);

            obterTextoAluno(window.listaAlunos[indice].textoURL)
            .then(texto => visualizer.atualizarTexto(texto));

            visualizer.exibir();
        }

        function criarLIAluno(alunoObj, i) {
            let li = document.createElement('li');
            li.id = alunoObj.id;

            li.innerHTML = `
                <p id="nome-aluno">${alunoObj.nome}</p>
                <img 
                    id="botao-visualizar" 
                    src="assets/imgs/eye.png" 
                    alt="ícone de visualização"
                    onclick="(() => visualizarAluno(${i}))()"
                >
                <img 
                    id="botao-excluir" 
                    src="assets/imgs/trash.png" 
                    alt="ícone de excluir"
                    onclick="(() => excluirAluno(${alunoObj.id}))()"
                >
            `;

            return li;
        }

        function excluirAluno(id) {
            adicionarDadoRedirecionamento('remover_id', id);
            redirecionarEnvio('?pagina=7');
        }

        function obterAluno(idAluno) {
            let listaAlunos = window.listaAlunos;

            for (var i = 0; i < listaAlunos.length; i++) {
                let aluno = listaAlunos[i];

                if (aluno.id === idAluno) return aluno;
            }

            return null;
        }

        obterListaAlunos()
        .then(lista => {
            window.listaAlunos = lista;
            
            lista.forEach((aluno, i) => alunosUL.appendChild(criarLIAluno(aluno, i)));
        });
    </script>
<?php } ?>


<link rel="stylesheet" href="assets/styles/administracao.css">