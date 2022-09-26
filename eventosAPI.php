<?php 

require('config.php');




//Decodificando o json que irá vir
$postjson = json_decode(file_get_contents('php://input', true), true);


if($postjson['requisicao'] == 'add'){

    $dataAntiga = strtotime($postjson['data']);

    $dataFormatadaParaSQL = date('Y-m-d H-i-s', $dataAntiga);

    $obj = new Eventos(
        $postjson['nome'],
        $dataFormatadaParaSQL,
        $postjson['capacidade'],
        $postjson['usuarios_id'],
    );

    $id = $obj->insert();

    if($id){
        $result = json_encode(array('success' => true, 'id' => $id));

    }else{
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao inserir!"));
    }

    echo $result;

}
else if($postjson['requisicao']=='listar'){


    if($postjson['nome'] == ''){
        $res = Eventos::getList();
    } else{
        $res = Eventos::search($postjson['nome']);
    }
    
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
    
    $dataAntiga = strtotime($postjson['data']);

    $dataFormatadaParaSQL = date('Y-m-d H-i-s', $dataAntiga);

    $obj = new Eventos();
    $obj->setId($postjson['id']);
    $obj->setNome($postjson['nome']);
    $obj->setData($dataFormatadaParaSQL);
    $obj->setCapacidade($postjson['capacidade']);
    $obj->setAtivo($postjson['ativo']);
    $obj->setUsuarios_id($postjson['usuarios_id']);

    $res = $obj->update();

    if($res){
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