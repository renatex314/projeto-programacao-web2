class Visualizer {

    constructor(visualizerWrapper) {
        this._visualizerWrapper = visualizerWrapper;
        this._imgView = this._visualizerWrapper.querySelector('img');
        this._exitButton = this._visualizerWrapper.querySelector('.exit-button');
        this._nomeView = this._visualizerWrapper.querySelector('#visualizer-nome');
        this._idadeView = this._visualizerWrapper.querySelector('#visualizer-idade');
        this._textoView = this._visualizerWrapper.querySelector('#visualizer-texto');
        this._turmaView = this._visualizerWrapper.querySelector('#visualizer-turma');
        this._adicionarListeners();
    }

    _adicionarListeners() {
        this._exitButton.addEventListener('click', () => this.esconder());
    }

    atualizarImagem(imgURL) {
        this._imgView.src = imgURL;
    }

    atualizarNome(nome) {
        this._nomeView.textContent = nome;
    }

    atualizarIdade(idade) {
        this._idadeView.textContent = `${idade} anos`;
    }

    atualizarTurma(turma) {
        this._turmaView.textContent = `turma: ${turma}`;
    }

    atualizarTexto(texto) {
        this._textoView.innerHTML = texto.replaceAll('\n', '<br>');
    }

    exibir() {
        this._visualizerWrapper.classList.remove('escondido');
        setTimeout(() => this._visualizerWrapper.classList.remove('transicao'), 200);
    }

    esconder() {
        this._visualizerWrapper.classList.add('transicao');
        setTimeout(() => this._visualizerWrapper.classList.add('escondido'), 200);
    }

}