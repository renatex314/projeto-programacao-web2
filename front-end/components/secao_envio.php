<form action="?pagina=<?php echo $_SESSION['pagina_indice'] + 1; ?>" method="POST" class="cadastro espacamento-superior centralizar-horizontal">
    <label class="titulo">Digite seus dados</label>

    <div class="data-section">
        <label for="nome">Nome</label>
        <input id="nome" name="nome" type="text" minlength="2" maxlength="40" placeholder="Qual é o seu nome ?" required>
    </div>
    <div class="data-section">
        <label for="idade">Idade</label>
        <input id="idade" name="idade" type="number" placeholder="Quantos anos você tem ?" required>
    </div>
    <div class="data-section">
        <label for="turma">Turma</label>
        <input id="turma" name="turma" type="text" placeholder="De qual turma você é ?" required>
    </div>
    <input type="submit" value="Enviar">
</form>

<link rel="stylesheet" href="assets/styles/cadastro.css">