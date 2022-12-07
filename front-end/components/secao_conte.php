<p id="conte-ferias" class="texto">
    Nos conte como foram suas férias !!! :D
</p>
<div class="text-wrapper">
    <textarea name="texto" id="texto" cols="30" rows="10"></textarea>
</div>



<link rel="stylesheet" href="assets/styles/conte.css">

<script src="assets/scripts/textarea.js"></script>
<script>
    //elemento de digitação do texto
    let texto = document.getElementById('texto');

    //define o dado do texto para ser enviado para o servidor
    definirFuncaoPreRedirecionamento(funcaoRedirecionar => {
        adicionarDadoRedirecionamento('texto', new Blob([texto.value]));

        funcaoRedirecionar();
    });
</script>