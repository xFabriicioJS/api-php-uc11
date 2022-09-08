<?php 
//Api - aplicação para recursos de app mobile
    

include_once('conn.php');

//variável que recebe o conteúdo da requisição do App decodificando-a

$postjson = json_decode(file_get_contents('php://input', true), true);

if($postjson['requisicao'] == 'add'){
    $query = $pdo->prepare("insert into usuarios set nome = :nome, usuario = :usuario, senha=:senha, senha_original=:senha_original, nivel=:nivel, ativo = 1");

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
            $query = $pdo->query("SELECT * FROM usuario order BY id desc limit $postjson[start], $postjson[limit]");
        } else{
            $busca = '%'.$postjson['nome'].'%';
            $query = $pdo->query("SELECT * FROM usuarios WHERE nome LIKE '$busca' or usuario LIKE '$busca' order BY id desc limit $postjson[start], $postjson[limit]");

        }
    
    $res = $query->fetchAll(PDO::FETCH_ASSOC);

    for($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id'=>$res[$i]['id'],
            'nome'=>$res[$i]['nome'],
            'usuario'=>$res[$i]['usuario'],
            'senha'=>$res[$i]['senha'],
            'senha_orginal'=>$res[$i]['senha_original'],
            'nivel' =>$res[$i]['nivel'],
            'ativo' => $res[$i]['ativo']
        );
    }
    if(count($res)){
        $result = json_encode(array('success'=>true, 'result'=>$dados));
    }else{
        $result = json_encode(array('success'=>true, 'result'=>'0'));
    }

    echo ($result);

    }

    else if($postjson['requisicao'] == 'editar'){
        $query = $pdo->query("UPDATE usuarios SET nome=:nome,
        usuario=:usuario, senha=:senha, senha_original =:senha_original, nivel=:nivel WHERE id = :id
        ");

        $query->bindValue(":nome",$postjson['nome']);
        $query->bindValue(":usuario", $postjson['usuario']);
        $query->bindValue(":senha", md5($postjson['senha']));
        $query->bindValue(":senha_original", $postjson['senha_original']);
        $query->bindValue(":nivel",$postjson['nivel']);
        $query->bindValue(":id",$postjson['id']);

        $query->execute();
        if($query){
            $result = json_encode(array('success' => true, 'msg'=>"deu tudo certo com a alteração"));
        }else{
            $result = json_encode(array('success' => false, 'msg'=>"Dados incorretos" )); 
        }

        echo $result;

    } //final da requisição editar

    else if($postjson['requisicao'] == 'excluir'){
        $query = $pdo->query("UPDATE usuarios set ativo = 0 WHERE id = $postjson[id]");
    
        if($query){
            $result = json_encode(array('success' => true, 'msg'=>"deu tudo certo com a exclusão"));
        }else{
            $result = json_encode(array('success' => false, 'msg'=>"Dados incorretos" )); 
        }
        echo $result;

    }
    //final do excluir

    else if($postjson['requisicao'] == 'login'){
        $query = $pdo->query("SELECT * from usuarios where usuario = '$postjson[usuario]' and senha = md5('$postjson[senha]') and ativo =1 ");

        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        for($i = 0; $i < count($res); $i++) {
    
            $dados[][] = array(
                'id'=>$res[$i]['id'],
                'nome'=>$res[$i]['nome'],
                'usuario'=>$res[$i]['usuario'],
                'nivel' =>$res[$i]['nivel'],
                'ativo' => $res[$i]['ativo']
            );
        }
    
        if($query){
            $result = json_encode(array('success' => true, 'msg'=>"deu tudo certo com a exclusão"));
        }else{
            $result = json_encode(array('success' => false, 'msg'=>"Falha a efetuar o login" )); 
        }
        echo $result;

    }

    
    else if($postjson['requisicao'] == 'ativar'){
        $query = $pdo->query("UPDATE usuarios set ativo = 1 WHERE id = $postjson[id]");
    
        if($query){
            $result = json_encode(array('success' => true, 'msg'=>"Usuário ativado com sucesso"));
        }else{
            $result = json_encode(array('success' => false, 'msg'=>"Falha ao ativar o usuário" )); 
        }
        echo $result;

    }


?>