<?php
    //função que verifica se uma opção de acessibilidade está ativada
    function opcaoHabilitada($nomeOpcao)
    {
        if (isset($_SESSION[$nomeOpcao]) && $_SESSION[$nomeOpcao] === 'ativado')
        {
            return 'checked';
        }

        return '';
    }

    //função que atualiza o estado de uma função
    function atualizarOpcao($nome, $ativado)
    {
        if ($ativado)
        {
            $_SESSION[$nome] = 'ativado';
        } else {
            unset($_SESSION[$nome]);
        }
    }

    //função que verifica se o usuário escolheu uma das opções de acessibilidade
    if (isset($_POST['atualizar-acessibilidade']))
    {
        atualizarOpcao('libras', isset($_POST['libras']));
        atualizarOpcao('texto-maior', isset($_POST['texto-maior']));
    }
?>

<div class="acessibilidade-wrapper">
    <input id="menu-toggle" class="menu-toggle" type="checkbox">
    <label class="botao-menu-acessibilidade" for="menu-toggle"></label>
    <form class="menu-wrapper-acessibilidade" action="#" method="POST">
        <label for="menu-toggle" class="botao-fechar"></label>
        <div class="opcoes-wrapper">
            <input type="text" name="atualizar-acessibilidade" value="1">
            <div class="opcao-wrapper">
                <input type="checkbox" name="libras" id="libras" <?php echo opcaoHabilitada('libras'); ?> >
                <label id="icone-libras" for="libras" style="--texto: 'Libras'"></label>
            </div>
            <div class="opcao-wrapper">
                <input type="checkbox" name="texto-maior" id="texto-maior" <?php echo opcaoHabilitada('texto-maior'); ?> >
                <label id="icone-maior" for="texto-maior" style="--texto: 'texto maior'"></label>
            </div>
        </div>
        <input class="botao-enviar botao" type="submit" value="ok">
    </form>
</div>

<?php //integração do VLibras ?>
<?php if (isset($_SESSION['libras'])) { ?>
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
<?php } ?>

<?php //integração da opção de aumentar o tamanho do texto ?>
<?php if (isset($_SESSION['texto-maior'])) {?>
    <link rel="stylesheet" href="assets/styles/texto-maior.css">
<?php } ?>

<link rel="stylesheet" href="assets/styles/acessibilidade.css">