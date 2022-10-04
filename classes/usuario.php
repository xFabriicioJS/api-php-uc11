<?php 
class Usuario{
    private $id;
    private $nome;
    private $usuario;
    private $senha;
    private $senha_original;
    private $ativo;
    private $avatar;
    private $nivel;

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
    public function getUsuario(){
        return $this->usuario;
    }
    public function setNivel($nivel){
        $this->nivel = $nivel;

    }
    public function getNivel(){
        return $this->nivel;
    }

    public function setUsuario($usuario){
        $this->usuario = $usuario;
    }
    public function getSenha(){
        return $this->senha;
    }
    public function setSenha($senha){
        $this->senha = $senha;
    }
    public function getSenhaOriginal(){
        return $this->senha_original;
    }
    public function setSenhaOriginal($senha_original){
        $this->senha_original = $senha_original;
    }
    public function getAtivo(){
        return $this->ativo;
    }
    public function setAtivo($ativo){
        $this->ativo = $ativo;
    }
    public function getAvatar(){
        return $this->avatar;
    }
    public function setAvatar($avatar){
        $this->avatar = $avatar;
    }
  
    public function loadById($_id){
        $sql = new Sql();
        $result = $sql->select("SELECT * FROM usuarios WHERE id = :ID", array(
            ":ID" => $_id
        ));
        if(count($result) > 0){
            $this->setData($result[0]);
        }
    }

    public function setData($dados){
        $this->setId($dados['id']);
        $this->setNome($dados['nome']);
        $this->setUsuario($dados['usuario']);
        $this->setSenha($dados['senha']);
        $this->setNivel($dados['nivel']);
        $this->setSenhaOriginal($dados['senha']);
        $this->setAtivo($dados['ativo']);
        $this->setAvatar($dados['avatar']);
    }
    

    public function insert(){
        $sql = new Sql();

       

        //criando a procedure
        $res = $sql->select("CALL sp_user_insert(:NOME, :USUARIO, :SENHA, :NIVEL, :AVATAR)", array(
            ":NOME" => $this->getNome(),
            ":USUARIO" => $this->getUsuario(),
            ":SENHA" => $this->getSenha(),
            ":NIVEL" => $this->getNivel(),
            ":AVATAR" => $this->getAvatar()
        ));
        if(count($res)>0){
            $this->setId($res[0]['id']);   
        }
       

        return $this->getId();

    }

    public function update() : bool{
        $sql = new Sql();

        $res = $sql->query("UPDATE usuarios SET nome = :NOME, nivel = :NIVEL, avatar = :AVATAR WHERE id = :ID", array(
            ":NOME" => $this->getNome(),
            ":ID" => $this->getId(),
            ":SENHA" => md5($this->getSenha()),
            ":NIVEL" => $this->getNivel(),
            ":AVATAR" => $this->getAvatar()
        ));

        if($res){
            return true;
        }else{
            return false;
        }

    }

    public function delete(){
        $sql = new Sql();

        //deletando usuário

        //precisamos instanciar um usuário para deleta-lo
        $sql->query("DELETE FROM usuarios WHERE id = :id", array(":id"=>$this->getId()));
    }

    //aspas  significa que não precisamos passar o campo em questão, é um campo não-obrigatório
    public function __construct($_nome="", $_usuario="", $_senha="", $_nivel="", $_ativo="", $_avatar="")
    {
        $this->nome = $_nome;
        $this->usuario = $_usuario;
        $this->senha = $_senha;
        $this->ativo = $_ativo;
        $this->nivel = $_nivel;
        $this->avatar = $_avatar;
    }

    public static function getList(){
        $sql = new Sql();   
        return $sql->select("SELECT * FROM usuarios ORDER BY nome");
    }


    public static function search($_nome){
        $sql = new Sql();
        return $sql->select("SELECT * FROM usuarios WHERE nome LIKE :NOME ORDER BY nome", array(
            ":NOME" => "%".$_nome."%"
        ));
    }

    public function efetuarLogin($_login, $_senha){
        $sql = new Sql();
        $senhaCrip = md5($_senha);
        $result = $sql->select("SELECT * FROM usuarios WHERE usuario = :usuario AND senha = :SENHA", array(
            ":usuario" => $_login,
            ":senha" => $senhaCrip
        ));
        if(count($result) > 0){
            $this->setData($result[0]);
        }else{
            throw new Exception("Login ou senha estão inválidos.");
        }

    }
}
?>