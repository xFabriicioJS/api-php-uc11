
<?php 
//conexão com banco de dados da app mobile ionic


//Configurações para as requisições JSON

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

header('Content-Type: application/json; charset=utf-8');

// Dados do servidor local
$banco = 'controledb';
$host = 'localhost'; //127.0.0.1
$usuario = 'root';
$senha = '';

try{
    $pdo = new PDO('mysql:host=localhost;dbname=controledb', $usuario, $senha);

}catch(Exception $e){
    echo "Deu ruim claudia";
    
}


//Dados do servidor remoto/hospedado
// $banco =  'wellington_91';
// $host =  'softkleen.com.br'; //127.0.0.1
// $usuario = 'apiti9111';
// $senha = '';


?>