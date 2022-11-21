<div id="desenho" class="desenferias">
    <p class="texto">Desenhe suas férias ! :D</p>
</div>

<div id="paint-wrapper">
    <div id="canvas-wrapper"></div>    
    <div class="panel-wrapper">
        <div class="options-wrapper">
            <ul class="options-list">
                <li id="pencil" class="button selected">
                    <img src="assets/icons/pencil.png">
                </li>
                <li id="eraser" class="button">
                    <img src="assets/icons/eraser.png">
                </li>
                <li class="slider">
                    <input id="slider" type="range" min="5" max="80"> 
                </li>
                <li>
                    <div id="visualizer" class="slider-visualizer" style="--pencil-size: 80px"></div>
                </li>
            </ul>
        </div>
        <div class="separator"></div>
        <div class="colors-wrapper">    
            <div class="row">
                <div class="color" style="--color: red"></div>
                <div class="color" style="--color: blue"></div>
                <div class="color" style="--color: green"></div>
            </div>
            <div class="row">
                <div class="color" style="--color: orange"></div>
                <div class="color selecionado" style="--color: black"></div>
                <div class="color" style="--color: purple"></div>
            </div>
            <div class="row">
                <div class="color" style="--color: crimson"></div>
                <div class="color" style="--color: DarkCyan"></div>
                <div class="color" style="--color: DarkViolet"></div>
            </div>
        </div>
    </div>
</div>



<link rel="stylesheet" href="assets/styles/paint.css">

<script src="assets/scripts/paintCanvas.js"></script>
<script src="assets/scripts/paintOptions.js"></script>
<script src="assets/scripts/paint.js"></script>

<script>
    let paint = new Paint(document.querySelector('#paint-wrapper'));

    definirFuncaoPreRedirecionamento(funcaoRedirecionar => paint.exportarImagem(blob => {
        adicionarDadoRedirecionamento('desenho', blob);

        funcaoRedirecionar();
    }));
</script>