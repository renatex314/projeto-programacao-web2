const URL_SERVIDOR = '../back-end/servidor.php';

let paint = new Paint(document.querySelector('#paint-wrapper'));
let texto = document.getElementById('texto');

function enviarDadosServidor(formData) {
    return fetch(URL_SERVIDOR, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json());
}

function obterDadosServidor() {
    return fetch(URL_SERVIDOR, {
        method: 'GET'
    })
    .then(response => response.json());
}

function cadastrarAluno(e) {
    e.preventDefault();

    let form = e.target;
    let formData = new FormData(form);

    paint.exportarImagem(img => {
        formData.append('texto', new Blob([texto.value]));
        formData.append('desenho', img);

        enviarDadosServidor(formData)
        .then(txt => console.log(`${txt}`));
    });
}