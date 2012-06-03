<?php
require_once 'sqlClass.php';
require_once 'svgClass.php';
require_once 'consultClass.php';

class sessionClass{
    
    public function __construct() {
        //cria a variavel de sessao (responsável por guardar as consultas
        $this->mcons = new Consult();
        $this->sql   = new sqlClass();
        $this->svg   = new svgClass();
 
    }
    
    //se uma consulta foi enviada e ainda não foi feita salva a consulta
    public function consultar(){

        //verifica se a consulta não foi feita e salva
        $consulta = isset($_POST['consulta'])?$_POST['consulta']:"";
        if($consulta == "") return;

        //faz a nova consulta
        if(geografico){
            $result = $this->svg->consultar($consulta, $this->sql);
            $mapa = $result['mapa'];
            $res  = $result['res'];
        }
        else $res = $this->sql->consultar($consulta);
        if($res === false) $res = $this->sql->getErro();
        $time = $this->sql->getCTime();
        $this->mcons->saveConsult($consulta, $res, $mapa, $time);
    }
    
    //apaga uma consulta caso ela exista
    public function apagaconsulta(){
        $this->mcons->deleteConsult($_GET['consulta']);
    }
    
    //se foi feita uma consulta que estava na lista, coloca esta na primeira posicao
    public function recuperaconsulta(){
        if(isset($_GET['consulta'])){
            $this->mcons->toFirst($_GET['consulta']);
        }
    }
    
    public function getResultadoPorConsulta($consulta){
        $var = $this->mcons->loadConsult($consulta);
        print_r($var);
        if($var == "") return "Nenhum resultado encontrado";
        if(is_array($var)){
            if(!empty ($var)){
                require_once 'tableClass.php';
                $table = new tableClass();
                $var   = $table->draw($var);
            }else $var = "Nenhum resultado encontrado";
        }
        return $var;
    }
    
    public function getResultConsulta(){
        $var = $this->mcons->getFirstResult();
        if(is_array($var)){
            if(!empty ($var)){
                require_once 'tableClass.php';
                $table = new tableClass();
                $var = $table->draw($var);
            }else $var = "Nenhum resultado encontrado";
        }
        return $var;
    }
    
    public function getResultMap(){
        $var = $this->mcons->getFirstMap();
        if(is_array($var)){
            if(!empty ($var)){
                $var = $this->svg->draw($var, 300, 100, 4);
            }else $var = "";
        }
        return $var;
    }
    
    public function getSqlConsulta(){
        return $this->mcons->getConsult();
    }
    
    public function getlink(){
        $cons = $v = "";
        $sql  = $this->getSqlConsulta();
        foreach($sql as $sq){
            $cons .= $v .base64_encode($sq);
            $v = ";";
        }
        $sgbd = $_SESSION['sgbd'];
        $link = "<div style='width: 600px;'>".URL ."/praticando.php?sgbd=$sgbd&action=consultbylink&multicons=$cons</div>";
        die($link);
    }
    
    public function consultbylink(){
        $_SESSION['consulta'] = array();
        $_SESSION['consres'] = array();
        if(isset($_GET['multicons'])) {
            $consultas = explode(";", $_GET['multicons']);
            foreach($consultas as $cons) {
                $_POST['consulta'] = base64_decode($cons);
                $this->consultar($cons);
            }
        }
        $this->redirect(URL);
    }
    
    public function genSchema(){
        //if(isset($_SESSION['schema'])) return $_SESSION['schema'];        
        $schema = $this->sql->getSchema();
        $html   = "";
        if(is_array($schema)){
            foreach($schema as $tablename => $sc){
                $html .= "$tablename(";
                $v = "";
                    foreach($sc as $col => $key){
                        $col   = ($key == "PRI")?"<u>$col</u>":$col;
                        $html .= "$v$col";
                        $v = ", ";
                    }
                $html .= ")<br/><br/>";
            }
        }else{ $html = $this->sql->getErro(); }
        $_SESSION['schema'] = $html;
        return $_SESSION['schema'];
    }
    
    private function redirect($url){
        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
              <meta http-equiv='refresh' content='0;URL=$url' />";
    }
}

?>
