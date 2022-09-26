<?php 
class Sql extends PDO{
    private $cn;
    public function __construct(){
        $this->cn = new PDO("mysql:host=localhost;dbname=controledb", "root", "");
    }

    //métodos que atribui parametros para uma query SQL
    private function setParams($comando, $parametros = array()){
        foreach ($parametros as $key => $value) {
            $this->setParam($comando, $key, $value);
        }
    }

    //método para tratar o parâmetro
    private function setParam($statement, $key, $value){
        $statement->bindParam($key, $value);
    }

    //método para executar uma query
    public function querySQL($comandoSQL, $params = array()){
        $statement = $this->cn->prepare($comandoSQL, $params);
        $this->setParams($comandoSQL, $params);
        $statement->execute();
        return $statement;
    }

    public function select($comandoSQL, $params = array()){
        $statement = $this->querySQL($comandoSQL, $params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>