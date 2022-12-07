<?php
    //Classe que representa um objeto que gerencia os arquivos dos alunos dentro do servidor
    class AlunosArquivos
    {
        //endereço da pasta com os desenhos dos alunos
        private const PASTA_DESENHOS = 'desenhos/';
        //endereço da pasta com os arquivos dos alunos
        private const PASTA_TEXTOS = 'textos/';

        function __construct($pathLocal, $pathGlobal)
        {
            $this->pathLocal = $pathLocal; //endereço para acesso local
            $this->pathGlobal = $pathGlobal; //endereço para acesso global (pela internet)
        }

        //método que exclui os arquivos do aluno especificado pelos parâmetros
        function excluirArquivosAluno($enderecoTexto, $enderecoDesenho)
        {
            unlink($this->pathLocal . $enderecoTexto);
            unlink($this->pathLocal . $enderecoDesenho);
        }

        //método que salva o texto de um aluno
        function salvarTexto($conteudoTexto, $idAluno)
        {
            $nomeArquivo = $this->gerarNomeArquivo(AlunosArquivos::PASTA_TEXTOS, $idAluno, ".txt");
            $enderecoArquivo = AlunosArquivos::PASTA_TEXTOS . $nomeArquivo;

            $this->salvarConteudo($this->pathLocal . $enderecoArquivo, $conteudoTexto);

            return $enderecoArquivo;
        }

        //método que salva o desenho de um aluno
        function salvarDesenho($conteudoDesenho, $idAluno)
        {
            $nomeArquivo = $this->gerarNomeArquivo(AlunosArquivos::PASTA_DESENHOS, $idAluno, ".png");
            $enderecoArquivo = AlunosArquivos::PASTA_DESENHOS . $nomeArquivo;

            $this->salvarConteudo($this->pathLocal . $enderecoArquivo, $conteudoDesenho);

            return $enderecoArquivo;
        }

        //método que salva o conteudo de um arquivo no endereço especificado
        private function salvarConteudo($enderecoArquivo, $conteudo) {
            $arquivo = fopen($enderecoArquivo, 'w');
            fwrite($arquivo, $conteudo);
            fclose($arquivo);
        }

        /*
            método que gera o novo nome de um arquivo, 
            para evitar conflitos com arquivos existentes em uma pasta
        */
        function gerarNomeArquivo($pasta, $idAluno, $extensao)
        {
            $enderecoPasta = $this->pathLocal . $pasta;
            
            //verifica se o arquivo não existe
            if (!file_exists($enderecoPasta . $idAluno . $extensao))
            {
                return $idAluno . $extensao; //retorna caso este nome de arquivo não exista
            }

            //obtem a lista de nomes de arquivos na pasta
            $listaArquivos = scandir($enderecoPasta);

            //verifica qual o maior número nos nomes dos arquivos dentro da pasta
            $maiorIndice = $idAluno;
            for ($i = 2; $i < count($listaArquivos); $i++)
            {
                //obtem o número no nome do arquivo
                $indice = intval(str_replace($extensao, '', $listaArquivos[$i]));

                //verifica se o número no nome do arquivo é maior que a variavel maiorIndice
                if ($indice > $maiorIndice)
                {
                    $maiorIndice = $indice;
                }
            }

            //retorna o maior índice + 1 como sendo o nome do arquivo
            return ($maiorIndice + 1) . $extensao;
        }

        //retorna o endereço global de um arquivo global
        function obterArquivoURL($enderecoArquivo)
        {
            return $this->pathGlobal . $enderecoArquivo;
        }
    }
?>