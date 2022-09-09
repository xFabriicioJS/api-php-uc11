<?php 

$atual = new DateTime();

$especifica = new DateTime('1990-01-22');

$texto =  new DateTime(' +1 month');

print_r($atual);
print_r($especifica);
print_r($texto);


$data = new DateTime();
echo $data->format('d-m-Y H:i:s');
echo "<br/>";
$data = new DateTime (" -  6 year");
echo $data->format('d-m-Y H:i:s');

?>

