<?php
    //inicia a sessão para manter os dados através das páginas
    session_start();

    //constante que armazena o endereço do servidor
    define('URL_SERVIDOR', '../back-end/servidor.php');
    //array que armazena uma lista das páginas disponíveis para acesso
    define('PAGINAS_NOMES', array(
        'home',  'desenho', 'conte', 
        'envio', 'galeria', 'sobre_nos',
        'administracao', 'parabens'
    ));

    //atualiza os dados referentes a qual página está sendo exibida atualmente
    function atualizarPaginaDados($indice)
    {
        $_SESSION['pagina_indice'] = $indice;
        $_SESSION['pagina'] = PAGINAS_NOMES[$_SESSION['pagina_indice'] - 1];
    }

    //verifica se a opção de remover um usuário pelo id está definida
    if (isset($_POST['remover_id']))
    {
        //diz para o servidor que é para remover um aluno com o ID especificado pelo $_SESSION['remover_id']
        $_SESSION['remover_id'] = $_POST['remover_id'];
        include_once URL_SERVIDOR; //chama o servidor
        unset($_POST['remover_id']); //desativa a opção de remover um usuário pelo iD
        header('location: ?pagina=7'); //redireciona para a página 7
    }

    //verifica se os dados referentes a página que será exibida atualmente estão definidos
    if (isset($_SESSION['pagina']))
    {
        //define os dados da página como sendo a pagina especificada pelo método GET
        atualizarPaginaDados(intval($_GET['pagina']));
    } 
    else 
    {
        //define como sendo a primeira página caso os dados da página não estejam definidos
        atualizarPaginaDados(1);
    }

    //verifica se a opção de refazer o processo do cadastro está definido
    if (isset($_POST['refazer']))
    {
        //desativa a opção que diz que o cadastro já foi realizado
        unset($_SESSION['cadastrado']);
        unset($_POST['refazer']);
    }

    //redireciona para a página de parabéns caso o aluno já tenha sido cadastrado
    if ($_SESSION['pagina_indice'] <= 4 && isset($_SESSION['cadastrado']))
    {
        header('location: ?pagina=8');   
    }

    //cadastra um novo aluno caso os dados do aluno estejam sendo fornecidos pelo método POST
    if (isset($_POST['nome']))
    {
        $_SESSION['nome'] = $_POST['nome'];
        $_SESSION['idade'] = $_POST['idade'];
        $_SESSION['turma'] = $_POST['turma'];
        $_SESSION['cadastro'] = 1; //indica para o servidor que é preciso realizar o cadastro de um aluno
        $_SESSION['cadastrado'] = 1; //indica que um aluno já foi cadastrado
        include_once URL_SERVIDOR; //chama o servidor
        header('location: index.php'); //redireciona para a página inicial
    }

    //verifica se o desenho do aluno está sendo enviado para ser armazenado e o salva na sessão atual
    if (isset($_FILES['desenho']))
    {
        $_SESSION['desenho'] = file_get_contents($_FILES['desenho']['tmp_name']);
    }

    //verifica se o texto do aluno está sendo enviado para ser armazenado e o salva na sessão atual
    if (isset($_FILES['texto']))
    {
        $_SESSION['texto'] = file_get_contents($_FILES['texto']['tmp_name']);
    }

    //define uma variável que possui o nome da página atual
    $nomePagina = $_SESSION['pagina'];
?>

<?php 
    //elemento do cabeçalho do site
    include 'header.php'; 
?>

<!-- DIV que onde os elementos da página atual se localizam-->
<div class="components-wrapper">
    <?php 
        //inclui o componente referente a página atual
        include "components/secao_$nomePagina.php"; 
    ?>
</div>


<?php 
    //inclui os botões de próximo e anterior caso o indíce da página seja menor ou igual a 4
    if ($_SESSION['pagina_indice'] <= 4) {
?>
<div class="buttons-wrapper">
    <?php //inclui o botão de anterior caso a página atual não seja a primeira?>
    <?php if ($_SESSION['pagina_indice'] !== 1) {?>
    <input  class="prev" 
            type="submit" 
            value="Voltar" 
            onclick="redirecionarPagina(event, <?php echo $_SESSION['pagina_indice'] - 1; ?>, false)"
    >
    <?php } ?>
    
    <?php //inclui o botão de anterior caso a página atual não seja a quarta ou maior?>
    <?php if ($_SESSION['pagina_indice'] <= 3) {?>
    <input  class="next" 
            type="submit" 
            value="Pronto" 
            onclick="redirecionarPagina(event, <?php echo $_SESSION['pagina_indice'] + 1; ?>)"
    >
    <?php } ?>
</div>
<?php } ?>
    
<?php

    //inclui o menu de acessibilidade
    include './components/acessibilidade.php';

    //inclui o rodapé da página
    include 'footer.php'; 
?>