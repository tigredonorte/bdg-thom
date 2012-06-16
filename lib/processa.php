<?php
require_once dirname(__FILE__).'/classes/controllerClass.php';
require_once dirname(__FILE__).'/database/'.$_SESSION['sgbd'].'/connection.php';
require_once dirname(__FILE__).'/classes/viewClass.php';

$vobj = new view();
$sobj = new controllerClass();

$schema = $sobj->genSchema();
$action = isset($_GET['action'])?$_GET['action']: "";
if(method_exists($sobj, $action)){
    $sobj->$action();
}

$result = $first = $map = "";
$layers = array();

$result = $sobj->getResult('');
$result = $vobj->prepare($result);
//print_r($result);
extract($result);
/*
if(!empty ($temp)){
    $table = new tableClass();
    $svg   = new svgClass();
    
    foreach($temp as $id => $array){
        $result .= "Consulta realizada em: ".$array['time'] . "<hr/>";
        $result .= $table->draw($array['res'], $id);
        $layers[] = base64_decode($id);

        if(geografico)
            $map .= $svg->draw($id, $array['mapa'], 600, 100, 5);
    }
    $first  = end($layers);
}
*/
$tags = $sobj->getTags();

?>