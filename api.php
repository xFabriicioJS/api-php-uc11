<?php 
//Api - aplicação para recursos de app mobile
    

include_once('conn.php');

//variável que recebe o conteúdo da requisição do App decodificando-a

$postjson = json_decode(file_get_contents('php://input', true), true);

if($postjson['requisicao'] == 'add'){
    $query = $pdo->prepare("insert into usuarios set nome = :nome, usuario = :usuario, senha=:senha, senha_original=:senha_original, nivel=:nivel, ativo=1");

    $query->bindValue(":nome", $postjson['nome']);
    $query->bindValue(":usuario", $postjson['usuario']);
    $query->bindValue(":senha", md5($postjson['senha']));
    $query->bindValue(":senha_original", $postjson['senha']);
    $query->bindValue(":nivel", $postjson['nivel']);
    $query->execute();

    $id = $pdo->lastInsertId();

    if($query){
        $result =  json_encode(array('success' => true, 'id' => $id));
    }else{
        $result = json_encode(array('success' => false, 'msg'=> 'Falha ao inserir o usuário'));
    }

    echo $result;

}


// Final requisição add

    else if($postjson['requisicao']=='listar'){
        if($postjson['nome'] == ''){
            $query = $pdo->query("SELECT * FROM usuario order BY id desc limit $postjson['start'], $postjson['limit']")
        }
    }


?>