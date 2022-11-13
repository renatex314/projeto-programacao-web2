<?php
    class AlunosArquivos
    {
        private const PASTA_DESENHOS = 'desenhos/';
        private const PASTA_TEXTOS = 'textos/';

        function __construct($pathLocal, $pathGlobal)
        {
            $this->pathLocal = $pathLocal;
            $this->pathGlobal = $pathGlobal;
        }

        function salvarTexto($enderecoTexto, $idAluno)
        {
            $enderecoArquivo = AlunosArquivos::PASTA_TEXTOS . "$idAluno.txt";

            move_uploaded_file(
                $enderecoTexto,
                $this->pathLocal . $enderecoArquivo
            );

            return $enderecoArquivo;
        }

        function salvarDesenho($enderecoDesenho, $idAluno)
        {
            $enderecoArquivo = AlunosArquivos::PASTA_DESENHOS . "$idAluno.png";

            move_uploaded_file(
                $enderecoDesenho,
                $this->pathLocal . $enderecoArquivo
            );

            return $enderecoArquivo;
        }

        function obterArquivoURL($enderecoArquivo)
        {
            return $this->pathGlobal . $enderecoArquivo;
        }
    }
?>