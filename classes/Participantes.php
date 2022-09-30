<?php 
    class Participante {
        private $id;
        private $nome;
        private $tipo;

        //método construtor
        public function __construct($id, $nome, $tipo){
            $this->id = $id;
            $this->nome = $nome;
            $this->tipo = $tipo;
        }

        //métodos de acesso (getter e setters)
        public function getId(){
            return $this->id;
        }

        public function setId($id){
            $this-> id = $id;
        }

        public function getNome(){
            return $this->nome;
        }

        public function setNome($nome){
            $this->nome = $nome;
        }

        public function getTipo(){
            return $this-> tipo;
        }

        public function setTipo($tipo){
            $this->tipo = $tipo;
        }

        //Método de inserção Participante
        public function insert(){
            $sql = new Sql();

            $res = $sql->select("CALL sp_participantes_insert(:NOME, :TIPO)",array(
                ":NOME" => $this->getNome(),
                ":TIPO" => $this->getTipo()
            ));

            if(count($res) > 0){
                $this->setId($res[0]['id']);
            }

            //retornará o Id criado lá para o controller
            return $this->getId();

        }

        public function delete(){
            $sql = new Sql();

            //deletando o participante

            $sql->querySql("DELETE from participantes WHERE id = :id", array(":id" => $this->getId()));
        }

        public function findAll(){
            $sql = new Sql();

            //listar todos os participantes

            return $sql->querySql("SELECT * FROM participantes ORDER by nome");
        }

        





    }




?>