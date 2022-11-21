const URL_SERVIDOR = '../back-end/servidor.php';

window.dadosRedirecionamento = {};
window.funcaoPreRedirecionamento = null;

function definirFuncaoPreRedirecionamento(funcao) {
    window.funcaoPreRedirecionamento = funcao;
}

function adicionarDadoRedirecionamento(chave, dado) {
    dadosRedirecionamento[chave] = dado;
}

function redirecionarEnvio(url) {
    let form = document.createElement('form');
    form.action = url;
    form.method = 'POST';
    form.enctype = 'multipart/form-data';
    form.style.display = 'none';

    Object.keys(dadosRedirecionamento).forEach(chave => {
        if (dadosRedirecionamento[chave] instanceof Blob) {
            let input = document.createElement('input');
            input.name = chave;
            input.type = 'file';

            let file = new File([dadosRedirecionamento[chave]], 'arquivo');
            let container = new DataTransfer();
            container.items.add(file);

            input.files = container.files;
            form.appendChild(input);
        } else {
            let input = document.createElement('textarea');
            input.name = chave;
            input.value = dadosRedirecionamento[chave];
            form.appendChild(input);
        }
    });

    document.body.appendChild(form);
    form.submit();
}

function enviarDadosServidor(formData) {
    return fetch(URL_SERVIDOR, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json());
}

function redirecionarPagina(e, indice, enviarDados = true) {
    e.preventDefault();

    if (enviarDados === true && funcaoPreRedirecionamento !== null) {
        funcaoPreRedirecionamento(() => redirecionarEnvio(`?pagina=${indice}`));    
    } else {
        redirecionarEnvio(`?pagina=${indice}`);
    }
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