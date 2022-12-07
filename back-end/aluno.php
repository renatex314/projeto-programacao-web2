<?php
    //Classe que representa os dados de um aluno
    /*
        OBS: 
            esta classe implementa a interface 
            JsonSerializable para fácil conversão do 
            objeto para o formato JSON
    */
    class Aluno implements JsonSerializable
    {
        private $id, $nome, $idade, $turma, $desenhoURL, $textoURL;

        function __construct($id, $nome, $idade, $turma, $desenhoURL, $textoURL)
        {
            $this->id = $id;
            $this->nome = $nome;
            $this->idade = $idade;
            $this->turma = $turma;
            $this->desenhoURL = $desenhoURL;
            $this->textoURL = $textoURL;
        }

        function setId($id)
        {
            $this->id = $id;
        }

        function setNome($nome)
        {
            $this->nome = $nome;
        }

        function setIdade($idade)
        {
            $this->idade = $idade;
        }

        function setTurma($turma)
        {
            $this->turma = $turma;
        }

        function setDesenhoURL($desenhoURL)
        {
            $this->desenhoURL = $desenhoURL;
        }

        function setTextoURL($textoURL)
        {
            $this->textoURL = $textoURL;
        }

        function getId()
        {
            return $this->id;
        }

        function getNome()
        {
            return $this->nome;
        }

        function getIdade()
        {
            return $this->idade;
        }

        function getTurma()
        {
            return $this->turma;
        }

        function getDesenhoURL()
        {
            return $this->desenhoURL;
        }

        function getTextoURL()
        {
            return $this->textoURL;
        }

        //método que converte o endereço local dos arquivos para o endereço global
        function converterParaURLGlobal($alunosArquivos)
        {
            $this->setDesenhoURL($alunosArquivos->obterArquivoURL($this->getDesenhoURL()));
            $this->setTextoURL($alunosArquivos->obterArquivoURL($this->getTextoURL()));
        }

        //método da interface JSONSerializable que realiza a conversão para JSON
        function jsonSerialize(): mixed
        {
            $serializableObj = array();
            $serializableObj['id'] = $this->getId();
            $serializableObj['nome'] = $this->getNome();
            $serializableObj['idade'] = $this->getIdade();
            $serializableObj['turma'] = $this->getTurma();
            $serializableObj['textoURL'] = $this->getTextoURL();
            $serializableObj['desenhoURL'] = $this->getDesenhoURL();

            return $serializableObj;
        }
    }
?>