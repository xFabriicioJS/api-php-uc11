<?php 

require('config.php');


include_once('conn.php');


//Decodificando o json que irá vir
$postjson = json_decode(file_get_contents('php://input', true), true);


if($postjson['requisicao'] == 'add'){

    $dataAntiga = strtotime($postjson['data']);

    $dataFormatadaParaSQL = date('Y-m-d H-i-s', $dataAntiga);

    $obj = new Eventos(
        $postjson['nome'],
        $dataFormatadaParaSQL,
        $postjson['capacidade'],
        null,
        $postjson['usuarios_id'],
    );

    $id = $obj->insert();

    if($id){
        $result = json_encode(array('success' => true, 'id' => $id));

    }else{
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao inserir!", ));
    }

    echo $result;

}
else if($postjson['requisicao']=='listar'){

    $evento = new Eventos();

    if($postjson['nome'] == ''){
        $res = $evento->getList();
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

    $res = $obj->update();

    if($res){
        $result = json_encode(array('success'=>true, 'msg'=>"deu tudo certo"));
    }else{
        $result = json_encode(array('success'=> false, 'msg'=> "deu ruim mano"));
    }

    echo $result;

}
else if($postjson['requisicao'] == 'excluir'){
   $evento = new Eventos();
    $evento->setId($postjson['id']);

    $res = $evento->deleteEvento();
    if($res){
        $result = json_encode(array('success'=>true, 'msg'=>"deu tudo certo"));
    }else{
        $result = json_encode(array('success'=> false, 'msg'=> "deu ruim mano"));
    }
}

else if($postjson['requisicao'] == 'ativar'){

    $evento = new Eventos();
    $evento->setId($postjson['id']);

    $res = $evento->ativarEvento();
   

    if($res){
        $result = json_encode(array('success' => true, 'msg' => "deu tudo certo mano"));

    }else{
        $result = json_encode(array('success' => false, 'msg' => "deu ruim!"));
    }

    echo $result;
}

    if($postjson['requisicao'] == 'adicionarParticipante'){
        $query = $pdo->prepare("insert into participantes_eventos set eventos_id = :eventos_id, participantes_id = :participantes_id");

        $query->bindValue(":eventos_id", $postjson['eventos_id']);
        $query->bindValue(":participantes_id", $postjson['participantes_id']);;
        $query->execute();
        if($query){
            $result =  json_encode(array('success' => true, 'msg' => "participante adicionado ao evento com sucesso."));
        }else{
            $result = json_encode(array('success' => false, 'msg'=> 'Falha ao ingressar o participante ao evento'));
        }
    
        echo $result;
    }

    if($postjson['requisicao'] == 'findAllParticipantesByEvento'){
        $query = $pdo->query("SELECT participantes.id as participantes_id, participantes.nome as participantes_nome, participantes.tipo as participantes_tipo
        FROM participantes
        INNER JOIN participantes_eventos ON participantes.id = participantes_eventos.participantes_id
        WHERE participantes_eventos.eventos_id = $postjson[eventos_id]");

        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        for($i = 0; $i < count($res); $i++){
            $dados[][] = array(
                'participantes_nome'=>$res[$i]['participantes_nome'],
                'participantes_tipo'=>$res[$i]['participantes_tipo'],
                'participantes_id'=>$res[$i]['participantes_id']
              
            );
        }

        if(count($res)){
            $result = json_encode(array('success' => true, 'result' => $dados));
        
        }else{
            $result = json_encode(array('success' => false, 'result' => '0'));
         
         
        }
        echo $result;
        
    }


?>