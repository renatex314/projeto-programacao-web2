<div id="galeria">
    <div id="prev-button" class="button"></div>
    <div id="next-button" class="button"></div>
    <div id="previous-img">
        <img src="" alt="">
        <p></p>
    </div>
    <div id="current-img">
        <img src="" alt="">
        <p></p>
    </div>
    <div class="click-message"></div>
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



<link rel="stylesheet" href="assets/styles/galeria.css">
<link rel="stylesheet" href="assets/styles/visualizer.css">

<script src="assets/scripts/galeria.js"></script>
<script src="assets/scripts/visualizer.js"></script>

<script>
    let slideshow = new Slideshow(document.querySelector('#galeria'));
    let visualizer = new Visualizer(document.querySelector('.visualizer-wrapper'));

    obterListaAlunos().then(lista => {
        lista.forEach(aluno => slideshow.addImg(aluno.nome, aluno.desenhoURL));
        slideshow.setOnClickListener(indice => {
            visualizer.atualizarNome(lista[indice].nome);
            visualizer.atualizarIdade(lista[indice].idade);
            visualizer.atualizarTurma(lista[indice].turma);
            visualizer.atualizarImagem(lista[indice].desenhoURL);

            obterTextoAluno(lista[indice].textoURL)
            .then(texto => visualizer.atualizarTexto(texto));

            visualizer.exibir();
        });
    });
</script>