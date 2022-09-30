<!-- Controlador de Participantes -->
<?php 
    require ('../config.php');

$postjson = json_decode(file_get_contents('php://input', true), true);

    if($postjson['requisicao'] == 'add'){
        $participante = new Participantes($);
    }   




?>