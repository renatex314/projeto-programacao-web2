class PaintOptions {

    constructor(panelWrapper) {
        this._panelWrapper = panelWrapper;
        this._pencilButton = panelWrapper.querySelector('#pencil');
        this._eraserButton = panelWrapper.querySelector('#eraser');
        this._slider = panelWrapper.querySelector('#slider');
        this._visualizer = panelWrapper.querySelector('#visualizer');
        this._opcoesCores = this._obterOpcoesCores();
        this._opcaoSelecionada = 'pencil';
        this._adicionarListeners();
        this._inicializar();
    }

    _inicializar() {
        this.alterarSlider(this._slider.value);
    }

    _adicionarListeners() {
        this._pencilButton.addEventListener('click', () => this.selecionarOpcao('pencil'));
        this._eraserButton.addEventListener('click', () => this.selecionarOpcao('eraser'));
        this._slider.addEventListener('input', () => this.alterarSlider(this._slider.value));

        this._opcoesCores.forEach(elemento => {
            const colorValue = getComputedStyle(elemento).getPropertyValue('--color');
            elemento.addEventListener('click', () => this._selecionarCor(elemento, colorValue));
        });
    }

    _desselecionarOpcoes() {
        this._pencilButton.classList.remove('selected');
        this._eraserButton.classList.remove('selected');
    }

    _desselecionarTodasCores() {
        this._opcoesCores.forEach(color => color.classList.remove('selecionado'));
    }

    _obterOpcoesCores() {
        return this._panelWrapper.querySelector('.colors-wrapper').querySelectorAll('.color');
    }

    _selecionarCor(colorElement, color) {
        this._desselecionarTodasCores();
        colorElement.classList.add('selecionado');
        
        if (this._corListener !== undefined) {
            this._corListener(color);
        }
    }

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

    alterarSlider(valor) {
        this._slider.value = valor;
        this._visualizer.style.setProperty('--pencil-size', `${valor}px`);

        if (this._sliderListener !== undefined) {
            this._sliderListener(valor);
        }
    }

    adicionarOpcaoListener(listener) {
        this._opcaoListener = listener;
    }

    adcionarSliderListener(listener) {
        this._sliderListener = listener;
        this._inicializar();
    }

    adicionarCorListener(listener) {
        this._corListener = listener;
    }

}