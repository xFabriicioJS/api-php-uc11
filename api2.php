<?php 
//Api - aplicação para recursos de app mobile

//acessando o nosso arquivo de confikguração para consumo das classes que criamos
require('./config.php');    



// include_once('conn.php');

//variável que recebe o conteúdo da requisição do App decodificando-a

$postjson = json_decode(file_get_contents('php://input', true), true);





if($postjson['requisicao'] == 'add'){
    
    //lógica para pegar pegar a imagem na requisição
    if($postjson['avatar'] !== ''){
        $avatar_name = $_FILES["avatar"]["name"];
        $avatar_tmp_name = $_FILES["avatar"]["tmp_name"];
    
        $pasta_img = "./images/". $avatar_name;
        move_uploaded_file($avatar_tmp_name, $pasta_img);    
        }
    
    
    $user = new Usuario($postjson['nome'], $postjson['usuario'], $postjson['senha'], $postjson['nivel'],null,$postjson['avatar']);



    $id = $user->insert();

    if(isset($id)){
        $result = json_encode(array('success' => true, 'id' => $id));
    }else{
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao inserir!"));
        echo $id;
    }

    echo $result;
}


else if($postjson['requisicao'] == 'addImagem'){
    if($_FILES['avatar']){
        $avatar_name = $_FILES["avatar"]["name"];
        $avatar_tmp_name = $_FILES["avatar"]["tmp_name"];
    
        $pasta_img = "./images/". $avatar_name;
        move_uploaded_file($avatar_tmp_name, $pasta_img);    
        }
}


// Final requisição add

    else if($postjson['requisicao']=='listar'){

        $user = new Usuario();

        if($postjson['nome'] == ''){
            $res = Usuario::getList();
        } else{
            $res = $user->search($postjson['nome']);

        }
    

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
        $result = json_encode(array('success'=>false, 'result'=>'0'));
    }

    echo ($result);

    }

    else if($postjson['requisicao']=='editar'){
        $query = $pdo->prepare("UPDATE usuarios SET nome=:nome, usuario=:usuario, senha= :senha, senha_original = :senha_original, nivel=:nivel WHERE id = :id");
        $query->bindValue(":nome",$postjson['nome']);
        $query->bindValue(":usuario",$postjson['usuario']);
        $query->bindValue(":senha",$postjson['senha']);
        $query->bindValue(":senha_original",$postjson['senha']);
        $query->bindValue(":nivel",$postjson['nivel']);
        $query->bindValue(":id",$postjson['id']);
        $query->execute();
        if ($query){
            $result = json_encode(array('success'=>true, 'msg'=>"Deu tudo certo com alteração!"));
        }else{
            $result = json_encode(array('success'=>false,'msg'=>"Dados incorretos! Falha ao atualizar o usuário! (WRH014587)"));
        }
        echo $result;
    }

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
    
        if(count($res) > 0){
            $result = json_encode(array('success' => true, 'result' => $dados));
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