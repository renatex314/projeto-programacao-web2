/*
    Esse arquivo tem como finalidade definir 
    as variáveis e funções utilizadas para realizar 
    requisições http e redirecionamento de página

*/

//endereço do servidor
const URL_SERVIDOR = '../back-end/servidor.php';

//dados que serão enviados em um redirecionamento de página POST
window.dadosRedirecionamento = {};
//função executada antes de redirecionar para uma página
window.funcaoPreRedirecionamento = null;

//função que define a função de pré-redirecionamento
function definirFuncaoPreRedirecionamento(funcao) {
    window.funcaoPreRedirecionamento = funcao;
}

//função que adiciona um dado a ser enviado no redirecionamento de página
function adicionarDadoRedirecionamento(chave, dado) {
    dadosRedirecionamento[chave] = dado;
}

//função que realiza a operação de redirecionar para uma página
function redirecionarEnvio(url) {
    let form = document.createElement('form'); //cria um form
    form.action = url; //coloca o action do form como sendo a url passada pelo parâmetro
    form.method = 'POST'; //coloca o methodo de redirecionamento como POST
    form.enctype = 'multipart/form-data'; //habilita o form para enviar diversos tipos de dados
    form.style.display = 'none'; //deixa o form invísivel

    //itera pelos dados passados e os prepara para serem enviados
    Object.keys(dadosRedirecionamento).forEach(chave => {
        //verifica se o dado passado é um arquivo
        if (dadosRedirecionamento[chave] instanceof Blob) {
            let input = document.createElement('input');
            input.name = chave;
            input.type = 'file';

            //converte o dado para um formato de arquivo
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

    //adiciona o form a página
    document.body.appendChild(form);
    //simula um envio no form para realizar o redirecionamento
    form.submit();
}

//realiza o redirecionamento para outra página especificada pelo parâmetro 'indice'
function redirecionarPagina(e, indice, enviarDados = true) {
    e.preventDefault();

    //verifica se há dados para serem enviados
    if (enviarDados === true && funcaoPreRedirecionamento !== null) {
        funcaoPreRedirecionamento(() => redirecionarEnvio(`?pagina=${indice}`));    
    } else {
        redirecionarEnvio(`?pagina=${indice}`);
    }
}

//retorna dados do servidor
function obterDadosServidor() {
    return fetch(URL_SERVIDOR, {
        method: 'GET'
    })
    .then(response => response.json());
}

//retorna a lista de alunos cadastrados no servidor
function obterListaAlunos() {
    return obterDadosServidor();
}

//retorna o texto de um aluno cadastrado
function obterTextoAluno(textoURL) {
    return fetch(textoURL)
    .then(response => response.text());
}