<?php 
class Participantes{
    private $id;
    private $nome;
    private $tipo;
  

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
    public function getTipo(){
        return $this->tipo;
    }
    public function setTipo($tipo){
        $this->tipo = $tipo;

    }
   
  
    public function loadById($_id){
        $sql = new Sql();
        $result = $sql->select("SELECT * FROM participantes WHERE id = :ID", array(
            ":ID" => $_id
        ));
        if(count($result) > 0){
            $this->setData($result[0]);
        }
    }

    public function setData($dados){
        $this->setId($dados['id']);
        $this->setNome($dados['nome']);
        $this->setTipo($dados['tipo']);
    }
    

    public function insert(){
        $sql = new Sql();

       
       
        //criando a procedure
        $res = $sql->select("CALL sp_participantes_insert(:NOME, :TIPO)", array(
            ":NOME" => $this->getNome(),
            ":TIPO" => $this->getTipo()
        ));
        if(count($res)>0){
            $this->setId($res[0]['id']);

        }
        return $this->getId();
        

    }

    public function update() : bool{
        $sql = new Sql();

        $res = $sql->query("UPDATE participantes SET nome = :NOME, tipo = :TIPO WHERE id = :ID", array(
            ":NOME" => $this->getNome(),
            ":ID" => $this->getId(),
            ":TIPO" => $this->getTipo()),
        );

        if($res){
            return true;
        }else{
            return false;
        }

    }

    public function delete(){
        $sql = new Sql();

        //deletando participante

        //precisamos instanciar um participante para deleta-lo
        $sql->query("DELETE FROM participantes WHERE id = :id", array(":id"=>$this->getId()));
    }

    //aspas  significa que não precisamos passar o campo em questão, é um campo não-obrigatório
    public function __construct($_nome="", $_tipo="")
    {
        $this->nome = $_nome;
        $this->tipo = $_tipo;
    }

    public static function findAll(){
        $sql = new Sql();   
        return $sql->select("SELECT * FROM participantes ORDER BY nome");
    }


    public static function search($_nome){
        $sql = new Sql();
        return $sql->select("SELECT * FROM participantes WHERE nome LIKE :NOME ORDER BY nome", array(
            ":NOME" => "%".$_nome."%"
        ));
    }

}
?>