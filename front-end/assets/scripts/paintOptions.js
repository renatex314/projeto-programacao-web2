//classe que representa a faixa de opções do paint
class PaintOptions {

    constructor(panelWrapper) {
        this._panelWrapper = panelWrapper; //container do painel
        this._pencilButton = panelWrapper.querySelector('#pencil'); //botão de lápis
        this._eraserButton = panelWrapper.querySelector('#eraser'); //botão de borracha
        this._slider = panelWrapper.querySelector('#slider'); //slider para mudar o tamanho do desenho
        this._visualizer = panelWrapper.querySelector('#visualizer'); //bolinha que muda de tamanho de acordo com o slider
        this._opcoesCores = this._obterOpcoesCores(); //lista de todas as opções de cores diponíveis
        this._opcaoSelecionada = 'pencil'; //opção de desenho selecionada
        this._adicionarListeners(); //adiciona os listeners para interação
        this._inicializar(); //inicializa o painel
    }

    //inicializa o painel de opções
    _inicializar() {
        this.alterarSlider(this._slider.value);
    }

    //adiciona os listeners para realizar a interação com o usuário
    _adicionarListeners() {
        this._pencilButton.addEventListener('click', () => this.selecionarOpcao('pencil')); //caso o usuário selecione o lápis
        this._eraserButton.addEventListener('click', () => this.selecionarOpcao('eraser')); //caso o usuário selecione a borracha
        this._slider.addEventListener('input', () => this.alterarSlider(this._slider.value)); //caso o usuário arraste o slider

        //adiciona a ação de clique para cada cor
        this._opcoesCores.forEach(elemento => {
            const colorValue = getComputedStyle(elemento).getPropertyValue('--color');
            elemento.addEventListener('click', () => this._selecionarCor(elemento, colorValue));
        });
    }

    //desseleciona todas as opções de desenho
    _desselecionarOpcoes() {
        this._pencilButton.classList.remove('selected');
        this._eraserButton.classList.remove('selected');
    }

    //desseleciona todas as cores
    _desselecionarTodasCores() {
        this._opcoesCores.forEach(color => color.classList.remove('selecionado'));
    }

    //retorna uma lista com todas as cores disponíveis para selecionar
    _obterOpcoesCores() {
        return this._panelWrapper.querySelector('.colors-wrapper').querySelectorAll('.color');
    }

    //seleciona a cor para realizar o desenho
    _selecionarCor(colorElement, color) {
        this._desselecionarTodasCores();
        colorElement.classList.add('selecionado');
        
        if (this._corListener !== undefined) {
            this._corListener(color);
        }
    }

    //seleciona a opção de desenho
    selecionarOpcao(opcao) {
        this._opcaoSelecionada = opcao;

        this._desselecionarOpcoes();
        if (this._opcaoSelecionada === 'pencil') {
            this._pencilButton.classList.add('selected');
        }

        if (this._opcaoSelecionada === 'eraser') {
            this._eraserButton.classList.add('selected');
        }

        if (this._opcaoListener !== undefined) {
            this._opcaoListener(opcao);
        }
    }

    //altera o valor do slider para o parametro especificado
    alterarSlider(valor) {
        this._slider.value = valor;
        this._visualizer.style.setProperty('--pencil-size', `${valor}px`);

        if (this._sliderListener !== undefined) {
            this._sliderListener(valor);
        }
    }

    //define o listener que é executado quando uma opção de desenho é selecionada
    adicionarOpcaoListener(listener) {
        this._opcaoListener = listener;
    }

    //define o listener que é executado quando o usuário arrasta o slider
    adcionarSliderListener(listener) {
        this._sliderListener = listener;
        this._inicializar();
    }

    //define o listener que é executado quando o usuário escolhe uma cor
    adicionarCorListener(listener) {
        this._corListener = listener;
    }

}