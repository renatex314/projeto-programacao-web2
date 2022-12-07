<div class="card">
    Parabéns !!!!
    <br>
    <img src="assets/imgs/confetti.png">
    <br>
    Você conseguiu contar suas ferias com sucesso ! <br>
    Se quiser ver como foram as férias de seus amigos, clique no botão abaixo:
</div>

<div class="botoes-wrapper">
    <a class="galeria botao" href="?pagina=5">Ir para a galeria</a>
    <a class="galeria botao refazer" onclick="refazer();">Cadastrar novas férias</a>
</div>

<link rel="stylesheet" href="assets/styles/parabens.css">

<script>
    //adiciona a opção de refazer o cadastro do aluno caso o usuário queira
    adicionarDadoRedirecionamento('refazer', 1);
    
    function refazer() {
        redirecionarEnvio('?pagina=1');
    }
</script>