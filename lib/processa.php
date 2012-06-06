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
if(geografico)$map = $sobj->getResultMap(600, 100, 5);
$tags   = $sobj->getTags();
$first  = end($layers);
$layers = array_reverse($layers);
?>
