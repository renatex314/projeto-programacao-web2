<?php
    class Aluno implements JsonSerializable
    {
        private $id, $nome, $idade, $desenhoURL, $textoURL;

        function __construct($id, $nome, $idade, $desenhoURL, $textoURL)
        {
            $this->id = $id;
            $this->nome = $nome;
            $this->idade = $idade;
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

        function getDesenhoURL()
        {
            return $this->desenhoURL;
        }

        function getTextoURL()
        {
            return $this->textoURL;
        }

        function converterParaURLGlobal($alunosArquivos)
        {
            $this->setDesenhoURL($alunosArquivos->obterArquivoURL($this->getDesenhoURL()));
            $this->setTextoURL($alunosArquivos->obterArquivoURL($this->getTextoURL()));
        }

        function jsonSerialize(): mixed
        {
            $serializableObj = array();
            $serializableObj['id'] = $this->getId();
            $serializableObj['nome'] = $this->getNome();
            $serializableObj['idade'] = $this->getIdade();
            $serializableObj['textoURL'] = $this->getTextoURL();
            $serializableObj['desenhoURL'] = $this->getDesenhoURL();

            return $serializableObj;
        }
    }
?>