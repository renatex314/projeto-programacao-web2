<?php
    //constante que armazena a senha necessária para entrar na área de administração
    //OBS: a senha atual é 'acessoadministrador123'
    define('SENHA_ADMINISTRACAO', '0bd5010bde0d9ac0e7ca15fc5dc84b27');

    //função que verifica se a senha digitada é igual a senha de acesso
    function senhaEstaCorreta()
    {
        return strcasecmp($_SESSION['senha_administracao'], SENHA_ADMINISTRACAO) === 0;
    }

    //verifica se o usuário digitou uma senha para acessar a área de administração
    if (isset($_POST['senha_administracao']))
    {
        //criptografa a senha
        $_SESSION['senha_administracao'] = md5($_POST['senha_administracao']);
    }
?>

<?php //exibe uma mensagem caso a senha tenha sido digitada incorretamente ?>
<?php if (isset($_SESSION['senha_administracao']) && !senhaEstaCorreta()) { ?>
    <p class="erro-aviso">Senha incorreta !<br>tente novamente:</p>
<?php } ?>

<?php //exibe o form para realizar o acesso caso o usuário ainda não tenha feito?>
<?php if (!isset($_SESSION['senha_administracao']) || !senhaEstaCorreta()) { ?>
    <form action="?pagina=7" method="POST" class="login-wrapper">
        <label for="senha">Senha:</label>
        <input id="senha" name="senha_administracao" type="password" placeholder="Digite a senha de acesso...">
        <input class="botao" type="submit" value="Entrar">
    </form>
<?php } ?>

<?php //exibe a área de administração caso a senha esteja correta ?>
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
        //cria o objeto visualizer para poder visualizar os dados do aluno
        let visualizer = new Visualizer(document.querySelector('.visualizer-wrapper'));
        //armazena o elemento referente a lista de alunos
        window.alunosUL = document.querySelector('.lista-alunos');
        //define a variável responsável por armazenar a lista de alunos no banco de dados.
        window.listaAlunos = null;

        //função que exibe os dados do aluno por meio do visualizer
        function visualizarAluno(indice) {
            visualizer.atualizarNome(window.listaAlunos[indice].nome);
            visualizer.atualizarIdade(window.listaAlunos[indice].idade);
            visualizer.atualizarTurma(window.listaAlunos[indice].turma);
            visualizer.atualizarImagem(window.listaAlunos[indice].desenhoURL);

            obterTextoAluno(window.listaAlunos[indice].textoURL)
            .then(texto => {
                visualizer.atualizarTexto(texto);
                visualizer.exibir();
            });
        }

        //função que cria um elemento LI para ser colocado no elemento referente a lista de alunos
        function criarLIAluno(alunoObj, indiceAluno) {
            let li = document.createElement('li');
            li.id = alunoObj.id;
  
            li.innerHTML = `
                <p id="nome-aluno">${alunoObj.nome}</p>
                <img 
                    id="botao-visualizar" 
                    src="assets/imgs/eye.png" 
                    alt="ícone de visualização"
                    onclick="visualizarAluno(${indiceAluno})"
                >
                <img 
                    id="botao-excluir" 
                    src="assets/imgs/trash.png" 
                    alt="ícone de excluir"
                    onclick="excluirAluno(${alunoObj.id})"
                >
            `;

            return li;
        }

        //função que remove um aluno cadastrado
        function excluirAluno(id) {
            adicionarDadoRedirecionamento('remover_id', id);
            redirecionarEnvio('?pagina=7');
        }

        //função que obtem os dados de um aluno
        function obterAluno(idAluno) {
            let listaAlunos = window.listaAlunos;

            for (var i = 0; i < listaAlunos.length; i++) {
                let aluno = listaAlunos[i];

                if (aluno.id === idAluno) return aluno;
            }

            return null;
        }

        //obtem a lista de alunos cadastrados e os insere no elemento UL da lista de alunos
        obterListaAlunos()
        .then(lista => {
            window.listaAlunos = lista;
            
            lista.forEach((aluno, i) => alunosUL.appendChild(criarLIAluno(aluno, i)));
        });
    </script>
<?php } ?>


<link rel="stylesheet" href="assets/styles/administracao.css">