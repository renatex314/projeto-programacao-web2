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

        function salvarTexto($conteudoTexto, $idAluno)
        {
            $nomeArquivo = $this->gerarNomeArquivo(AlunosArquivos::PASTA_TEXTOS, $idAluno, ".txt");
            $enderecoArquivo = AlunosArquivos::PASTA_TEXTOS . $nomeArquivo;

            $this->salvarConteudo($this->pathLocal . $enderecoArquivo, $conteudoTexto);

            return $enderecoArquivo;
        }

        function salvarDesenho($conteudoDesenho, $idAluno)
        {
            $nomeArquivo = $this->gerarNomeArquivo(AlunosArquivos::PASTA_DESENHOS, $idAluno, ".png");
            $enderecoArquivo = AlunosArquivos::PASTA_DESENHOS . $nomeArquivo;

            $this->salvarConteudo($this->pathLocal . $enderecoArquivo, $conteudoDesenho);

            return $enderecoArquivo;
        }

        private function salvarConteudo($enderecoArquivo, $conteudo) {
            $arquivo = fopen($enderecoArquivo, 'w');
            fwrite($arquivo, $conteudo);
            fclose($arquivo);
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