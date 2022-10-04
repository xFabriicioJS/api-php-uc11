<?php 
//Api - aplicação para recursos de app mobile

//acessando o nosso arquivo de confikguração para consumo das classes que criamos
require('./config.php');    


//variável que recebe o conteúdo da requisição do App decodificando-a

$postjson = json_decode(file_get_contents('php://input', true), true);





if($postjson['requisicao'] == 'add'){
    
    
    $participante = new Participantes($postjson['nome'], $postjson['tipo']);



    $id = $participante->insert();

    if(isset($id)){
        $result = json_encode(array('success' => true, 'id' => $id));
    }else{
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao inserir!"));
        
    }

    echo $result;
}


// Final requisição add

    else if($postjson['requisicao']=='listar'){

        $participante = new Participantes();

        if($postjson['nome'] == ''){
            $res = Participantes::findAll();
        } else{
            $res = $participante->search($postjson['nome']);

        }
    

    for($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id'=>$res[$i]['id'],
            'nome'=>$res[$i]['nome'],
            'tipo'=>$res[$i]['tipo']            
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
        $participante = new Participantes();
        
        $participante->setNome($postjson['nome']);
        $participante->setTipo($postjson['tipo']);
        $participante->setTipo($participante['id']);

        if($participante->update()){
            $result = json_encode(array(
                "success"=> true,
                "msg"=> "Participante atualizado com sucesso!"
            ));
        }else{
            $result = json_encode(array(
                "success" => false,
                "msg" => "Ocorreu uma falha ao atualizar o participante"
            ));
        }

        echo $result;

    }

    else if($postjson['requisicao'] == 'excluir'){
       $participante = new Participantes();

       $res = $participante->delete($postjson['id']);
       if($res){
        $result = json_encode(array('success' => true, 'msg' => "Participante exluido com sucesso!"));
       }else{
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu um erro ao excluir o participante"));
       }

       echo $result;

    }
    //final do excluir




?>