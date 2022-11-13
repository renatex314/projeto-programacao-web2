class Paint {

    constructor(paintWrapper) {
        this._paintWrapper = paintWrapper;
        this._paintCanvas = new PaintCanvas(paintWrapper.querySelector('#canvas-wrapper'));
        this._paintOptions = new PaintOptions(paintWrapper.querySelector('.panel-wrapper'));
        this._adicionarListeners();
    }

    _adicionarListeners() {
        this._paintOptions.adcionarSliderListener(valor => this._paintCanvas.atualizarComprimentoLinha(valor));
        this._paintOptions.adicionarOpcaoListener(opcao => this._paintCanvas.atualizarOpcao(opcao));
        this._paintOptions.adicionarCorListener(cor => this._paintCanvas.atualizarCor(cor));
    }

    exportarImagem(callback) {
        return this._paintCanvas.exportarImagem(callback);
    }

}