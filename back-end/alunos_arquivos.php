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
            $nomeArquivo = $this->gerarNomeArquivo(AlunosArquivos::PASTA_TEXTOS, $idAluno, ".txt");
            $enderecoArquivo = AlunosArquivos::PASTA_TEXTOS . $nomeArquivo;

            move_uploaded_file(
                $enderecoTexto,
                $this->pathLocal . $enderecoArquivo
            );

            return $enderecoArquivo;
        }

        function salvarDesenho($enderecoDesenho, $idAluno)
        {
            $nomeArquivo = $this->gerarNomeArquivo(AlunosArquivos::PASTA_DESENHOS, $idAluno, ".png");
            $enderecoArquivo = AlunosArquivos::PASTA_DESENHOS . $nomeArquivo;

            move_uploaded_file(
                $enderecoDesenho,
                $this->pathLocal . $enderecoArquivo
            );

            return $enderecoArquivo;
        }

        function gerarNomeArquivo($pasta, $idAluno, $extensao)
        {
            $enderecoPasta = $this->pathLocal . $pasta;
            
            if (!file_exists($enderecoPasta . $idAluno . $extensao))
            {
                return $idAluno . $extensao;
            }
            
            $listaArquivos = scandir($enderecoPasta);

            $maiorIndice = $idAluno;
            for ($i = 2; $i < count($listaArquivos); $i++)
            {
                $indice = intval(str_replace($extensao, '', $listaArquivos[$i]));

                if ($indice > $maiorIndice)
                {
                    $maiorIndice = $indice;
                }
            }

            return ($maiorIndice + 1) . $extensao;
        }

        function obterArquivoURL($enderecoArquivo)
        {
            return $this->pathGlobal . $enderecoArquivo;
        }
    }
?>