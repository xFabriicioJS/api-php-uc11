<?php 

include_once('conn.php');


//Decodificando o json que irá vir
$postjson = json_decode(file_get_contents('php://input', true), true);


if($postjson['requisicao'] == 'add'){

    $query = $pdo->prepare("insert into eventos set nome = :nome, data = :data, capacidade=:capacidade,  ativo = 1, usuarios_id = :usuarios_id");


    $dataAntiga = strtotime($postjson['data']);

    $dataFormatadaParaSQL = date('Y-m-d H-i-s', $dataAntiga);

    $query->bindValue(":nome", $postjson['nome']);
    $query->bindValue(":data", $dataFormatadaParaSQL);
    $query->bindValue(":capacidade", $postjson['capacidade']);
    $query->bindValue(":usuarios_id", $postjson['usuarios_id']);
    $query->execute();


    $id = $pdo->lastInsertId();

    if($query){
        $result = json_encode(array('success' => true, 'id' => $id));

    }else{
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao inserir!"));
    }

    echo $result;

}
else if($postjson['requisicao']=='listar'){
    if($postjson['nome'] == ''){
        


    } else{
        $busca = '%'.$postjson['nome'].'%';
        $query = $pdo->query("SELECT * FROM eventos WHERE nome LIKE '$busca' or evento LIKE '$busca' order BY id desc limit $postjson[start], $postjson[limit]");
    }

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for($i = 0; $i < count($res); $i++) {


    $data = new DateTime($res[$i]['data']);



    $dados[][] = array(
        'id'=>$res[$i]['id'],
        'nome'=>$res[$i]['nome'],
        'data'=>$data->format('d-m-Y H:i:s'),
        'capacidade'=>$res[$i]['capacidade'],
        'ativo'=>$res[$i]['ativo'],
        'usuarios_id'=>$res[$i]['usuarios_id']
    );
}
if(count($res)){
    $result = json_encode(array('success'=>true, 'result'=>$dados));
}else{
    $result = json_encode(array('success'=>false, 'result'=>'0'));
}

echo ($result);

}

else if($postjson['requisicao'] == 'editar'){
    $query = $pdo->prepare("update eventos SET nome=:nome, data = :data, capacidade=:capacidade WHERE id = :id");


    $dataAntiga = strtotime($postjson['data']);

    $dataFormatadaParaSQL = date('Y-m-d H-i-s', $dataAntiga);


    $query->bindValue(":nome", $postjson['nome']);
    $query->bindValue(":data", $dataFormatadaParaSQL);
    $query->bindValue(":capacidade", $postjson['capacidade']);
    $query->bindValue(":id", $postjson['id']);
    $query->execute();

    if($query){
        $result = json_encode(array('success'=>true, 'msg'=>"deu tudo certo"));
    }else{
        $result = json_encode(array('success'=> false, 'msg'=> "deu ruim mano"));
    }

    echo $result;

}
else if($postjson['requisicao'] == 'excluir'){
    $query = $pdo->query("UPDATE eventos set ativo = 0 WHERE id = $postjson[id]");

    if($query){
        $result = json_encode(array('success' => true, 'msg'=> "deu tudo certo mano"));

    }else{
        $result = json_encode(array('success' => false, 'msg'=> "deu tudo errado mano"));
    }

    echo $result;
 
}

else if($postjson['requisicao'] == 'ativar'){
    $query = $pdo->query("update eventos set ativo = 1 where id = $postjson[id]");

    if($query){
        $result = json_encode(array('success' => true, 'msg' => "deu tudo certo mano"));

    }else{
        $result = json_encode(array('success' => false, 'msg' => "deu ruim!"));
    }

    echo $result;
}


?>