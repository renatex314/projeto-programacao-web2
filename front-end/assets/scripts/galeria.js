//classe que representa o carrossel
class Slideshow {

    constructor(wrapperElement) {
        this._wrapperElement = wrapperElement; //container do carrossel
        this._prevButton = wrapperElement.querySelector('#prev-button'); //botão para ver imagem anterior
        this._nextButton = wrapperElement.querySelector('#next-button'); //botão para ver próxima imagem
        this._prevImgDiv = wrapperElement.querySelector('#previous-img'); //div que contem a imagem anterior
        this._currImgDiv = wrapperElement.querySelector('#current-img'); //div que contem a próxima imagem
        this._clickImgDiv = wrapperElement.querySelector('.click-message'); //div que detecta a ação de clique
        this._imgsList = []; //lista de imagens
        this._currIndex = 0; //índice da imagem atual
        this._prevIndex = 0; //índice da imagem anterior
        this._addListeners(); //configura as funções a serem executadas quando o usuário interagir com o carrossel
        this.addImg('sem imagens', ''); //configura o carrossel para inicialmente não ter imagens
        this._isEmpty = true; //variável que indica se o carrossel está vazio
        this._onClickListener = null; //função executada quando o usuário clicar
        this._updateImg(); //atualiza a imagem selecionada atualmente para ser exibida
    }

    //realiza o efeito de transição entre as imagens
    _createTransition() {
        this._prevImgDiv.classList.add('fade-out');
        this._currImgDiv.classList.add('fade-in');

        if (this._currentTimeout) clearTimeout(this._currentTimeout);

        this._currentTimeout = setTimeout(() => {
            this._prevImgDiv.classList.remove('fade-out');
            this._currImgDiv.classList.remove('fade-in');
        }, 500);
    }

    //exibe a imagem selecionada atualmente
    _updateImg() {
        let prevImg = this._prevImgDiv.querySelector('img');
        let prevText = this._prevImgDiv.querySelector('p');

        let currImg = this._currImgDiv.querySelector('img');
        let currText = this._currImgDiv.querySelector('p');

        //coloca os dados da imagem anterior como sendo a imagem atual
        prevImg.src = currImg.src;
        prevText.textContent = currText.textContent;
        
        //configura a imagem atual como sendo a imagem selecionada
        let imgUrl = this._imgsList[this._currIndex].imgSrc;
        currImg.src = imgUrl === '' ? 'empty' : imgUrl;
        currText.textContent = this._imgsList[this._currIndex].text;
 
        //coloca o endereço da imagem como 'empty' caso a imagem não esteja definida
        if (imgUrl === '') {
            prevImg.src = 'empty';
        }
    }

    //configura as funções executadas quando o usuário interagir com o carrossel
    _addListeners() {
        this._prevButton.addEventListener('click', () => this.swipeLeft());
        this._nextButton.addEventListener('click', () => this.swipeRight());
        this._clickImgDiv.addEventListener('click', () => {
            if (this._onClickListener !== null) {
                this._onClickListener(this._currIndex);
            }
        });
    }

    //define a função a ser executada quando o usuário clicar
    setOnClickListener(listener) {
        this._onClickListener = listener;
    }

    //adiciona uma imagem ao carrossel
    addImg(text, imgSrc) {
        //condicional que verifica se o carrossel está vazio
        if (this._isEmpty) {
            this._imgsList = [];
            this._isEmpty = false;
        }

        this._imgsList.push({
            imgSrc: imgSrc,
            text: text
        });
        //configura o indice da imagem atual como sendo da última imagem adicionada
        this._currIndex = this._imgsList.length - 1;

        //exibe a imagem adicionada
        this._updateImg();
    }

    //configura o índice da imagem atual a ser exibida
    setCurrentIndex(index) {
        this._prevIndex = this._currIndex;
        this._currIndex = index;
        
        this._updateImg();
        this._createTransition();
    }

    //realiza a interação de ver a próxima imagem
    swipeRight() {
        let newIndex = this._currIndex + 1;

        this.setCurrentIndex(newIndex > this._imgsList.length - 1 ? 0 : newIndex);
    }

    //realiza a interação de ver a imagem anterior
    swipeLeft() {
        let newIndex = this._currIndex - 1;
        
        this.setCurrentIndex(newIndex < 0 ? this._imgsList.length - 1 : newIndex);
    }
}