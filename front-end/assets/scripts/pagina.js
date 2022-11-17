const URL_SERVIDOR = '../back-end/servidor.php';

let paint = new Paint(document.querySelector('#paint-wrapper'));
let slideshow = new Slideshow(document.querySelector('#galeria'));
let visualizer = new Visualizer(document.querySelector('.visualizer-wrapper'));

let texto = document.getElementById('texto');

obterListaAlunos().then(lista => {
    lista.forEach(aluno => slideshow.addImg(aluno.nome, aluno.desenhoURL));
    slideshow.setOnClickListener(indice => {
        visualizer.atualizarNome(lista[indice].nome);
        visualizer.atualizarIdade(lista[indice].idade);
        visualizer.atualizarImagem(lista[indice].desenhoURL);

        obterTextoAluno(lista[indice].textoURL)
        .then(texto => visualizer.atualizarTexto(texto));

        visualizer.exibir();
    });
});         