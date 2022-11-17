<form class="cadastro espacamento-superior centralizar-horizontal" onsubmit="cadastrarAluno(event)">
    <label class="titulo">Digite seus dados</label>

    <div class="data-section">
        <label for="nome">Nome</label>
        <input id="nome" name="nome" type="text" minlength="2" maxlength="40" placeholder="Nome completo" required>
    </div>
    <div class="data-section">
        <label for="idade">Idade</label>
        <input id="idade" name="idade" type="number" placeholder="idade" required>
    </div>
    
    <input type="submit" value="enviar">
</form>

<link rel="stylesheet" href="assets/styles/cadastro.css">