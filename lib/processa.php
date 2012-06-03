<?php
$file = dirname(__FILE__). '/database/'.$_SESSION['sgbd'].'/connection.php';
require_once $file;

$sobj = new sessionClass();
$schema = $sobj->genSchema();
$action = isset($_GET['action'])?$_GET['action']: "";
if(method_exists($sobj, $action)){
    $sobj->$action();
}

$result = $sobj->getResultConsulta();
$layers = $sobj->getSqlConsulta();
$map    = $sobj->getResultMap(300, 100, 2);
$first  = end($layers);
$layers = array_reverse($layers);
?>
