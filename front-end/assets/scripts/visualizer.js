//classe que representa o visualizador de dados dos alunos
class Visualizer {

    constructor(visualizerWrapper) {
        this._visualizerWrapper = visualizerWrapper; //container do visualizador
        this._imgView = this._visualizerWrapper.querySelector('img'); //elemento img para visualizar a imagem do aluno
        this._exitButton = this._visualizerWrapper.querySelector('.exit-button'); //botão para fechar o visualizador
        this._nomeView = this._visualizerWrapper.querySelector('#visualizer-nome'); //caixa de texto com o nome do aluno
        this._idadeView = this._visualizerWrapper.querySelector('#visualizer-idade'); //caixa de texto com a idade do aluno
        this._textoView = this._visualizerWrapper.querySelector('#visualizer-texto'); //caixa de texto com o texto do aluno
        this._turmaView = this._visualizerWrapper.querySelector('#visualizer-turma'); //caixa de texto com a turma do aluno
        this._adicionarListeners(); //adiciona os listeners de interação
    }

    //adiciona o listener que fecha o visualizador quando o botão de fechar é clicado
    _adicionarListeners() {
        this._exitButton.addEventListener('click', () => this.esconder());
    }

    //atualiza a imagem de acordo com a url passada pelo parâmetro
    atualizarImagem(imgURL) {
        this._imgView.src = imgURL;
    }

    //atualiza o nome de acordo com o parâmero passado
    atualizarNome(nome) {
        this._nomeView.textContent = nome;
    }

    //atualiza a idade de acordo com o parâmetro passado
    atualizarIdade(idade) {
        this._idadeView.textContent = `${idade} anos`;
    }

    //atualiza a turma de acordo com o parâmetro passado
    atualizarTurma(turma) {
        this._turmaView.textContent = `turma: ${turma}`;
    }

    //atualiza o texto de acordo com o parâmetro passado
    atualizarTexto(texto) {
        this._textoView.innerHTML = texto.replaceAll('\n', '<br>');
    }

    //exibe o visualizer na tela
    exibir() {
        this._visualizerWrapper.classList.remove('escondido');
        setTimeout(() => this._visualizerWrapper.classList.remove('transicao'), 200);
    }

    //esconde o visualizer da tela
    esconder() {
        this._visualizerWrapper.classList.add('transicao');
        setTimeout(() => this._visualizerWrapper.classList.add('escondido'), 200);
    }

}