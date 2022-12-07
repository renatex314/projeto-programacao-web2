//classe que representa o Paint
class Paint {

    constructor(paintWrapper) {
        this._paintWrapper = paintWrapper; //container do paint
        //"papel" do paint onde será realizado o desenho
        this._paintCanvas = new PaintCanvas(paintWrapper.querySelector('#canvas-wrapper')); 
        //barra de opções do paint
        this._paintOptions = new PaintOptions(paintWrapper.querySelector('.panel-wrapper'));
        //configura as funções relacionadas a interação
        this._adicionarListeners();
    }

    //configura as funções relacionadas a interação
    _adicionarListeners() {
        //atualiza o valor do slider da barra de opções com o comprimento da linha do canvas
        this._paintOptions.adcionarSliderListener(valor => this._paintCanvas.atualizarComprimentoLinha(valor));
        //atualiza a forma de desenho (lápis ou borracha) da barra de opções com o canvas
        this._paintOptions.adicionarOpcaoListener(opcao => this._paintCanvas.atualizarOpcao(opcao));
        //atualiza a cor selecionada na barra de opções com o canvas
        this._paintOptions.adicionarCorListener(cor => this._paintCanvas.atualizarCor(cor));
    }

    //função que exporta a imagem desenhada no canvas
    exportarImagem(callback) {
        return this._paintCanvas.exportarImagem(callback);
    }

}