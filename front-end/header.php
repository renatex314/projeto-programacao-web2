<?php
    function gerarNomeClasse($nomePagina) {
        $paginaAtualIndice = $_SESSION['pagina_indice'];
        $paginaIndice = array_search($nomePagina, PAGINAS_NOMES) + 1;

        if ($paginaIndice < $paginaAtualIndice)
        {
            return 'selected';
        }

        if ($paginaIndice == $paginaAtualIndice)
        {
            return 'present';
        }
    }
?>

<!DOCTYPE html>

<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/styles/pagina.css">
        <link rel="stylesheet" href="assets/styles/reponsive.css">
        <script src="assets/scripts/requisicoes.js"></script>
        <title>Paint</title>
    </head>

    <body>
        <div class="container">
            <header class="cabecalho">
                <a class="menlogo" href="#">
                    <img class="logo" src="assets/logo.png">
                </a>
                <div class="menu" >
                    <ul class="opcoes">
                        <li class="item <?php echo gerarNomeClasse('desenho');?>">
                            <a class="link" href="?pagina=1">Desenhe suas férias</a>
                        </li>
                        <div class="conector <?php echo gerarNomeClasse('desenho');?>"></div>
                        <li class="item <?php echo gerarNomeClasse('conte');?>">
                            <a class="link" href="?pagina=1">Conte suas Ferias</a>
                        </li>
                        <div class="conector <?php echo gerarNomeClasse('conte');?>"></div>
                        <li class="item <?php echo gerarNomeClasse('envio');?>">
                            <a class="link" href="?pagina=1">Enviar</a>
                        </li>
                        <div class="separator"></div>
                        <li class="item">
                            <a class="botao" href="?pagina=5">Galeria</a>
                        </li>
                        <div class="separador-pequeno invisivel"></div>
                        <li class="item">
                            <a class="botao" href="?pagina=6">Sobre nós</a>
                        </li>
                        <div class="separador-pequeno invisivel"></div>
                        <li class="item">
                            <a class="botao" href="?pagina=7">Administração</a>
                        </li>
                    </ul>
                </div>
            </header>