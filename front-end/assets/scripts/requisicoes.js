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
        .then(() => {
            alert('aluno cadastrado com sucesso !');
            window.location.reload();
        });
    });
}

function obterListaAlunos() {
    return obterDadosServidor();
}

function obterTextoAluno(textoURL) {
    return fetch(textoURL)
    .then(response => response.text());
}