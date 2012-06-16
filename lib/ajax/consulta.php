<?php

if(!isset($_POST)) { die();}
session_start();

//carrega os arquivos
require_once '../classes/controllerClass.php';
require_once '../classes/tableClass.php';
require_once '../classes/svgClass.php';
require_once '../database/'.$_SESSION['sgbd'].'/connection.php';

//instancia as classes
$sobj  = new controllerClass();
$table = new tableClass();

//realiza a consulta
$resultado = $sobj->consultar();

//faz o desenho dos itens da consulta
$key = base64_encode($_POST['consulta']);
$var['mainlayer'] = $table->draw($resultado['res'], $key);
if(geografico){
    $svg = new svgClass();
    $var['maps'] = $svg->draw($key, $resultado['mapa'], 600, 100, 5);
}

//gera a interface
$link    = "?consulta=$key";
$acoes   = "<a href='$link&action=recuperaconsulta' class='action'><img src='img/btn_editar.png'/></a>";
if(geografico){
    $acoes .= "<a class='action colorSelector bgcolor' id='#bg_$key'></a>";
    $acoes .= "<a class='action colorSelector licolor' id='#li_$key'></a>";
}
$acoes  .= "<a href='$link&action=apagaconsulta' class='action'><img src='img/btn_excluir.png'/></a>";
                                    
$var['sortable'] = "
    <li class='layer border'>
        <a href='$key' class='selecionar'>
            <div class='item bg bg-hover'>". nl2br($_POST['consulta'])."</div>
        </a>
        <div class='acoes'>$acoes</div>
    </li>
";

//retorna o resultado
echo json_encode($var);

?>