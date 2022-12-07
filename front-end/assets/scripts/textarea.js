//textarea do texto do aluno
let textarea = document.querySelector('#texto');
//container do textarea
let textWrapper = document.querySelector('.text-wrapper');

//aumenta dinamicamente o tamanho do textarea de acordo com o texto sendo digitado para evitar o aparecimento do scroll
function aumentarTextarea(textarea) {
    textarea.style.height = '25px';
    textarea.style.height = (textarea.scrollHeight) + 'px';
}

//redireciona o foco para o textarea caso o container seja clicado
textWrapper.addEventListener('click', () => textarea.focus());
//aumenta dinamicamente o tamanho do textarea
textarea.addEventListener('input', () => aumentarTextarea(textarea));