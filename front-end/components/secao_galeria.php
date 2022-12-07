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
    //instancia o carrossel de imagens
    let slideshow = new Slideshow(document.querySelector('#galeria'));
    //instancia o visualizer dos dados dos alunos
    let visualizer = new Visualizer(document.querySelector('.visualizer-wrapper'));

    //obtem a lista de alunos e preenche o carrossel com os dados de cada aluno
    obterListaAlunos().then(lista => {
        //adiciona a imagem de cada aluno com o nome de cada um
        lista.forEach(aluno => slideshow.addImg(aluno.nome, aluno.desenhoURL));
        //configura a ação a ser realizada pelo carrossel assim que ele for clicado
        slideshow.setOnClickListener(indice => {
            visualizer.atualizarNome(lista[indice].nome);
            visualizer.atualizarIdade(lista[indice].idade);
            visualizer.atualizarTurma(lista[indice].turma);
            visualizer.atualizarImagem(lista[indice].desenhoURL);

            obterTextoAluno(lista[indice].textoURL)
            .then(texto => visualizer.atualizarTexto(texto));

            //exibe os dados do aluno
            visualizer.exibir();
        });
    });
</script>