<?php
    session_start();

    define('URL_SERVIDOR', '../back-end/servidor.php');
    define('PAGINAS_NOMES', array(
        'home',  'desenho', 'conte', 
        'envio', 'galeria', 'sobre_nos',
        'administracao', 'parabens'
    ));

    function atualizarPaginaDados($indice)
    {
        $_SESSION['pagina_indice'] = $indice;
        $_SESSION['pagina'] = PAGINAS_NOMES[$_SESSION['pagina_indice'] - 1];
    }

    if (!isset($_SESSION['pagina']))
    {
        atualizarPaginaDados(1);
    }

    if (isset($_GET['pagina']))
    {
        atualizarPaginaDados(intval($_GET['pagina']));
    }

    if ($_SESSION['pagina_indice'] <= 4 && isset($_SESSION['cadastrado']))
    {
        header('location: ?pagina=8');   
    }

    if (isset($_POST['nome']))
    {
        echo 'teste';
        $_SESSION['nome'] = $_POST['nome'];
        $_SESSION['idade'] = $_POST['idade'];
        $_SESSION['turma'] = $_POST['turma'];
        $_SESSION['cadastro'] = 1;
        $_SESSION['cadastrado'] = 1;
        include_once URL_SERVIDOR;
        header('location: index.php');
    }

    if (isset($_FILES['desenho']))
    {
        $_SESSION['desenho'] = file_get_contents($_FILES['desenho']['tmp_name']);
    }

    if (isset($_FILES['texto']))
    {
        $_SESSION['texto'] = file_get_contents($_FILES['texto']['tmp_name']);
    }

    $nomePagina = $_SESSION['pagina'];
?>

<?php include 'header.php'; ?>

<div class="components-wrapper">
    <?php include "components/secao_$nomePagina.php"; ?>
</div>


<?php if ($_SESSION['pagina_indice'] <= 4) {?>
<div class="buttons-wrapper">
    <?php if ($_SESSION['pagina_indice'] !== 1) {?>
    <input  class="prev" 
            type="submit" 
            value="Voltar" 
            onclick="redirecionarPagina(event, <?php echo $_SESSION['pagina_indice'] - 1; ?>, false)"
    >
    <?php } ?>
    
    <?php if ($_SESSION['pagina_indice'] <= 3) {?>
    <input  class="next" 
            type="submit" 
            value="Pronto" 
            onclick="redirecionarPagina(event, <?php echo $_SESSION['pagina_indice'] + 1; ?>)"
    >
    <?php } ?>
</div>
<?php } ?>
    
<?php include 'footer.php'; ?>