let textarea = document.querySelector('#texto');
let textWrapper = document.querySelector('.text-wrapper');

function aumentarTextarea(textarea) {
    textarea.style.height = '25px';
    textarea.style.height = (textarea.scrollHeight) + 'px';
}

textWrapper.addEventListener('click', () => textarea.focus());
textarea.addEventListener('input', () => aumentarTextarea(textarea));