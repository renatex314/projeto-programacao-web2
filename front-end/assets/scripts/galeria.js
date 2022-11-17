class Slideshow {

    constructor(wrapperElement) {
        this._wrapperElement = wrapperElement;
        this._prevButton = wrapperElement.querySelector('#prev-button');
        this._nextButton = wrapperElement.querySelector('#next-button');
        this._prevImgDiv = wrapperElement.querySelector('#previous-img');
        this._currImgDiv = wrapperElement.querySelector('#current-img');
        this._clickImgDiv = wrapperElement.querySelector('.click-message');
        this._imgsList = [];
        this._currIndex = 0;
        this._prevIndex = 0;
        this._addListeners();
        this.addImg('sem imagens', '');
        this._isEmpty = true;
        this._onClickListener = null;
        this._updateImg();
    }

    _createTransition() {
        this._prevImgDiv.classList.add('fade-out');
        this._currImgDiv.classList.add('fade-in');

        if (this._currentTimeout) clearTimeout(this._currentTimeout);

        this._currentTimeout = setTimeout(() => {
            this._prevImgDiv.classList.remove('fade-out');
            this._currImgDiv.classList.remove('fade-in');
        }, 500);
    }

    _updateImg() {
        let prevImg = this._prevImgDiv.querySelector('img');
        let prevText = this._prevImgDiv.querySelector('p');

        let currImg = this._currImgDiv.querySelector('img');
        let currText = this._currImgDiv.querySelector('p');

        prevImg.src = currImg.src;
        prevText.textContent = currText.textContent;
        
        let imgUrl = this._imgsList[this._currIndex].imgSrc;
        currImg.src = imgUrl === '' ? 'empty' : imgUrl;
        currText.textContent = this._imgsList[this._currIndex].text;
 
        if (imgUrl === '') {
            prevImg.src = 'empty';
        }
    }

    _addListeners() {
        this._prevButton.addEventListener('click', () => this.swipeLeft());
        this._nextButton.addEventListener('click', () => this.swipeRight());
        this._clickImgDiv.addEventListener('click', () => {
            if (this._onClickListener !== null) {
                this._onClickListener(this._currIndex);
            }
        });
    }

    setOnClickListener(listener) {
        this._onClickListener = listener;
    }

    addImg(text, imgSrc) {
        if (this._isEmpty) {
            this._imgsList = [];
            this._isEmpty = false;
        }

        this._imgsList.push({
            imgSrc: imgSrc,
            text: text
        });

        this._updateImg();
    }

    setCurrentIndex(index) {
        this._prevIndex = this._currIndex;
        this._currIndex = index;
        
        this._updateImg();
        this._createTransition();
    }

    swipeRight() {
        let newIndex = this._currIndex + 1;

        this.setCurrentIndex(newIndex > this._imgsList.length - 1 ? 0 : newIndex);
    }

    swipeLeft() {
        let newIndex = this._currIndex - 1;
        
        this.setCurrentIndex(newIndex < 0 ? this._imgsList.length - 1 : newIndex);
    }
}