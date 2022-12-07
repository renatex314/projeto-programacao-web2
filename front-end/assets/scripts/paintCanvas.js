//Classe que representa o canvas do paint
class PaintCanvas {

    constructor(elementoPai) {
        this._elementoPai = elementoPai; //container do canvas
        this._dadosMouse = this._criarDadosMouse(); //cria dados relacionados ao mouse (posição, botão pressionado)
        this._dadosPaint = this._criarDadosPaint(); //cria dados relacionados ao paint (comprimento, cor, opção de desenho)
        this._canvas = this._criarCanvas(elementoPai); //cria o canvas e o coloca no elemento pai
        this._context = this._canvas.getContext('2d'); //context do element canvas para realizar os desenhos
        this._preencherFundo(); //preenche o fundo com uma cor predefinida
    }

    //preenche o canvas com a cor branca
    _preencherFundo() {
        this._context.fillStyle = '#FFFFFF';
        this._context.fillRect(0, 0, this._canvas.width, this._canvas.height);
    }

    //cria o elemento canvas e o insere no elemento pai
    _criarCanvas(elementoPai) {
        let canvas = document.createElement('canvas');
        
        elementoPai.appendChild(canvas);
        //adiciona um eventListener para caso o elemento pai mudar de tamanho
        elementoPai.addEventListener('resize', () => this._atualizarCanvas(canvas));
        //adiciona os listeners para detectarem as interações realizadas com o usuário
        this._adicionarListenersCanvas(canvas);
        //atualiza 
        this._atualizarCanvas(canvas);

        return canvas;
    }

    //função que cria um objeto contendo as informações padrão do mouse
    _criarDadosMouse() {
        return {
            mousePressionado: false, //indica se o mouse está pressionado
            mouseX: null, //posição horizontal do mouse
            mouseY: null, //posição vertical do mouse
            prevMouseX: null, //posição anterior horizontal do mouse
            prevMouseY: null //posição anterior vertical do mouse
        };
    }

    //função que cria um objeto content as informações padrão de desenho
    _criarDadosPaint() {
        return {
            corSelecionada: '#000000', //cor selecionada para desenho
            comprimentoLinha: 5, //comprimento da linha a ser desenhada
            opcaoSelecionada: 'pencil', //opção de desenho escolhida
        };
    }

    //função que desenha uma linha
    _drawLine(prevMouseX, prevMouseY, x, y, lineWidth, color) {
        this._context.beginPath(); //começa um 'caminho' para ser desenhado
        this._context.moveTo(prevMouseX, prevMouseY); //move para a posição anterior do mouse
        this._context.lineTo(x, y); //cria uma linha até a posição atual do mouse
        this._context.lineCap = 'round'; //deixa a linha com cantos arredondados
        this._context.lineWidth = lineWidth; //define o comprimento da linha
        this._context.strokeStyle = color; //define a cor da linha
        this._context.stroke(); //desenha a linha
        this._context.closePath(); //fecha o 'caminho' desenhado
    }

    //função que desenha um retângulo
    _drawRect(x, y, size, color) {
        this._context.fillStyle = color; //define a cor do retângulo
        this._context.fillRect(x - size / 2, y  - size / 2, size, size); //desenha o retângulo na posição x, y com tamanho size
    }

    //adiciona os listeners para realiza a interação com o usuário
    _adicionarListenersCanvas(canvas) {
        //verifica se o mouse está pressionado
        canvas.addEventListener('mousedown', () => this._dadosMouse.mousePressionado = true);
        //verifica se o mouse foi solto
        canvas.addEventListener('mouseup', () => this._dadosMouse.mousePressionado = false);
        //verifica se o mouse saiu do canvas
        canvas.addEventListener('mouseleave', () => this._dadosMouse.mousePressionado = false);
        //verifica se o mouse está se movendo
        canvas.addEventListener('mousemove', ({offsetX: x, offsetY: y}) => {
            //verifica se a posição anterior do mouse é conhecida e se o mouse está pressionado
            if (this._dadosMouse.prevMouseX !== null && this._dadosMouse.prevMouseY !== null && this._dadosMouse.mousePressionado) {
                //desenha uma linha da posição anterior do mouse até a posição atual
                this._drawLine(
                    this._dadosMouse.prevMouseX, this._dadosMouse.prevMouseY, 
                    x, y, 
                    this._dadosPaint.comprimentoLinha, 
                    (
                        this._dadosPaint.opcaoSelecionada === 'pencil' ?
                        this._dadosPaint.corSelecionada : 'white'
                    ) 
                );
            }

            //define a posição anterior do mouse como sendo a posição atual
            this._dadosMouse.prevMouseX = x;
            this._dadosMouse.prevMouseY = y;
        });
    }

    //atualiza as dimensões do canvas
    _atualizarCanvas(canvas) {
        let estiloPai = getComputedStyle(this._elementoPai);
        canvas.width = parseInt(estiloPai.width) - 2*parseInt(estiloPai.borderWidth);
        canvas.height = parseInt(estiloPai.height) - 2*parseInt(estiloPai.borderWidth);
    }

    //atualiza a cor selecionada para desenho
    atualizarCor(cor) {
        this._dadosPaint.corSelecionada = cor;
    }

    //atualiza o comprimento de linha para desenho
    atualizarComprimentoLinha(comprimento) {
        this._dadosPaint.comprimentoLinha = comprimento;
    }

    //atualiza a opção selecionada para realizar o desenho
    atualizarOpcao(opcao) {
        this._dadosPaint.opcaoSelecionada = opcao;
    }

    //função que exporta a imagem desenhada no canvas
    exportarImagem(callback) {
        return this._canvas.toBlob(callback);
    }

}