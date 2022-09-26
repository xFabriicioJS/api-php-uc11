<?php 
    class Eventos {
        private $id;
        private $nome;
        private $data;
        private $capacidade;
        private $ativo;
        private $usuarios_id;

        //métodos de acesso (getters e setters)
        public function getId(){
            return $this->id;
        }
        public function setId($id){
            $this->id = $id;
        }
        public function getNome(){
            return $this->nome;
        }
        public function setNome($nome){
            $this->nome = $nome;
        }
        public function getData(){
            return $this->data;
        }
        //setando a data (de data mesmo )
        public function setData($data){
            $this->data = $data;
        }
        public function getCapacidade(){
            return $this->capacidade;
        }
        public function setCapacidade($capacidade){
            $this->capacidade = $capacidade;
        }
        public function getAtivo(){
            return $this->ativo;
        }
        public function setAtivo($ativo){
            $this->ativo = $ativo;
        }
        public function getUsuarios_id(){
            return $this->usuarios_id;
        }
        public function setUsuarios_id($usuarios_id){
            $this->usuarios_id = $usuarios_id;
        }

        //Método construtor
        public function __construct($_nome="", $_data="", $_capacidade="", $_ativo="", $_usuarios_id="" )
        {
            $this->nome = $_nome;
            $this->data = $_data;
            $this->capacidade = $_capacidade;
            $this->ativo = $_ativo;
            $this->usuarios_id = $_usuarios_id;
        }


        //Setando os dados
        public function setDados($dados){
            $this->setId($dados['id']);
            $this->setNome($dados['nome']);
            $this->setData($dados['data']);
            $this->setCapacidade($dados['capacidade']);
            $this->setAtivo($dados['ativo']);
            $this->setUsuarios_id($dados['usuarios_id']);
        }


        // métodos para buscar por id
        public function getEventoById($_id){
            $sql = new Sql();
            $result = $sql->select("SELECT * FROM eventos WHERE id = :ID", array(
                ":ID"=>$_id
            ));
            if(count($result) > 0){
                $this->setDados($result[0]);
            }
        }


        public function update($_nome, $_data, $_capacidade){
            $sql = new Sql();
            
            $sql->query("UPDATE eventos SET nome = :NOME, data = :DATA, capacidade = :CAPACIDADE WHERE id = :ID", array(
                ":NOME"=>$_nome,
                ":DATA"=>$_data,
                ":CAPACIDADE"=>$_capacidade,
                ":ID"=>$this->getId()
            ));
        }

        //iremos precisar instanciar um evento para poder utilizar esse método
        public function deleteEvento(){
            $sql = new Sql();

            $sql->query("DELETE FROM eventos WHERE id = :id", array(":id"=>$this->getId()));
        }


        //iremos precisar instanciar um evento para executar esse método
        public function insert(){
            $sql = new Sql();

            $result = $sql->select("CALL sp_eventos_insert(:NOME, :DATA, :CAPACIDADE, :ATIVO, :USUARIOS_ID)", array(
                ":NOME"=>$this->getNome(),
                ":DATA"=>$this->getData(),
                ":CAPACIDADE"=>$this->getCapacidade(),
                ":ATIVO"=>$this->getAtivo(),
                ":USUARIOS_ID"=>$this->getUsuarios_id()
            ));
        
            if(count($result) > 0){
                $this->setDados($result[0]);
            }
        }


        //Método para listar todos os eventos, é um método estático, não precisamos instanciar nenhum objeto
        public static function getList(){
            $sql = new Sql();

            return $sql->select("SELECT * FROM eventos ORDER BY nome");
        }

        public static function search($_nome){
            $sql = new Sql();
        return $sql->select("SELECT * FROM eventos WHERE nome LIKE :NOME ORDER BY nome", array(
            ":NOME" => "%".$_nome."%"
        ));
        }
    }




?>