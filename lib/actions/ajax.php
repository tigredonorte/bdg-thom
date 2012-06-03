<?php
session_start();
require_once '../database/'.$_SESSION['sgbd'].'/connection.php';
require_once 'sessionClass.php';

$resp = explode(";", $_POST['consulta']);
$sobj = new sessionClass();
foreach($resp as $r){
    //$_SESSION['consulta'][base64_encode($_POST['consulta'])] = $_POST['consulta']; 
    if(!array_key_exists($r, $_SESSION['consulta'])) continue;
    $index = $_SESSION['consulta'][$r];
    $var['consulta'][]  = base64_decode($r);
    $var['resultado'][] = $sobj->getResultadoPorConsulta($r);
}
$var['response'] = "Consulta realizada com sucesso!";
echo json_encode($var);

?>