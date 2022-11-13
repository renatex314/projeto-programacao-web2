class PaintCanvas {

    constructor(elementoPai) {
        this._elementoPai = elementoPai;
        this._dadosMouse = this._criarDadosMouse();
        this._dadosPaint = this._criarDadosPaint();
        this._canvas = this._criarCanvas(elementoPai);
        this._context = this._canvas.getContext('2d');
        this._preencherFundo();
    }

    _preencherFundo() {
        this._context.fillStyle = '#FFFFFF';
        this._context.fillRect(0, 0, this._canvas.width, this._canvas.height);
    }

    _criarCanvas(elementoPai) {
        let canvas = document.createElement('canvas');
        
        elementoPai.appendChild(canvas);
        elementoPai.addEventListener('resize', () => this._atualizarCanvas(canvas));
        this._adicionarListenersCanvas(canvas);
        this._atualizarCanvas(canvas);

        return canvas;
    }

    _criarDadosMouse() {
        return {
            mousePressionado: false,
            mouseX: null,
            mouseY: null,
            prevMouseX: null,
            prevMouseY: null
        };
    }

    _criarDadosPaint() {
        return {
            corSelecionada: '#000000',
            comprimentoLinha: 5,
            opcaoSelecionada: 'pencil',
        };
    }

    _drawLine(prevMouseX, prevMouseY, x, y, lineWidth, color) {
        this._context.beginPath();
        this._context.moveTo(prevMouseX, prevMouseY);
        this._context.lineTo(x, y);
        this._context.lineCap = 'round';
        this._context.lineWidth = lineWidth;
        this._context.strokeStyle = color;
        this._context.stroke();
        this._context.closePath();
    }

    _drawRect(x, y, size, color) {
        this._context.fillStyle = color;
        this._context.fillRect(x - size / 2, y  - size / 2, size, size);
    }

    _adicionarListenersCanvas(canvas) {
        canvas.addEventListener('mousedown', () => this._dadosMouse.mousePressionado = true);
        canvas.addEventListener('mouseup', () => this._dadosMouse.mousePressionado = false);
        canvas.addEventListener('mouseleave', () => this._dadosMouse.mousePressionado = false);
        canvas.addEventListener('mousemove', ({offsetX: x, offsetY: y}) => {
            if (this._dadosMouse.prevMouseX !== null && this._dadosMouse.prevMouseY !== null && this._dadosMouse.mousePressionado) {
                if (this._dadosPaint.opcaoSelecionada === 'pencil') {
                    this._drawLine(
                        this._dadosMouse.prevMouseX, this._dadosMouse.prevMouseY, 
                        x, y, 
                        this._dadosPaint.comprimentoLinha, this._dadosPaint.corSelecionada
                    );
                }

                if (this._dadosPaint.opcaoSelecionada === 'eraser') {
                    this._drawLine(
                        this._dadosMouse.prevMouseX, this._dadosMouse.prevMouseY, 
                        x, y, 
                        this._dadosPaint.comprimentoLinha, 'white'
                    );
                }
            }

            this._dadosMouse.prevMouseX = x;
            this._dadosMouse.prevMouseY = y;
        });
    }

    _atualizarCanvas(canvas) {
        let estiloPai = getComputedStyle(this._elementoPai);
        canvas.width = parseInt(estiloPai.width) - 2*parseInt(estiloPai.borderWidth);
        canvas.height = parseInt(estiloPai.height) - 2*parseInt(estiloPai.borderWidth);
    }

    atualizarCor(cor) {
        this._dadosPaint.corSelecionada = cor;
    }

    atualizarComprimentoLinha(comprimento) {
        this._dadosPaint.comprimentoLinha = comprimento;
    }

    atualizarOpcao(opcao) {
        this._dadosPaint.opcaoSelecionada = opcao;
    }

    exportarImagem(callback) {
        return this._canvas.toBlob(callback);
    }

}