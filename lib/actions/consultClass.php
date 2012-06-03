<?php

class Consult{
    
    private $session = 'consulta';
    public function __construct() {
        //unset($_SESSION[$this->session]);
        if(!isset($_SESSION[$this->session]))  $_SESSION[$this->session] = array();
    }
    
    public function saveConsult($consulta, $res, $mapa, $time){
        
        //comprime o resultado
        $consulta = base64_encode($consulta);
        $res      = $this->compress($res);
        
        //se resultado não foi registrado, registra
        if(!array_key_exists($consulta , $_SESSION[$this->session])) {
            if(geografico){
                $mapa = $this->compress($mapa);
                $_SESSION[$this->session][$consulta]['mapa'] = $mapa;
            }
            $_SESSION[$this->session][$consulta]['resultado'] = $res;
            $_SESSION[$this->session][$consulta]['tempo']     = $time;
        }
    }
    
    public function deleteConsult($consulta){
        if(isset($_SESSION[$this->session][$consulta]))
            unset($_SESSION[$this->session][$consulta]);
        
    }
    
    public function loadAllConsult(){
        $var = array_reverse($_SESSION[$this->session]);
        $out = array();
        foreach($var as $arr){
            $out[] = $arr['resultado'];
        }
        return $out;
    }
    
    public function loadConsult($consulta){
        if(!array_key_exists($consulta, $_SESSION[$this->session])) return "";
        return $this->uncompress($_SESSION[$this->session][$consulta]['resultado']);
    }
    
    public function loadMap($consulta){
        //if(!array_key_exists($consulta, $_SESSION[$this->session])) return array();
        return $this->uncompress($_SESSION[$this->session][$consulta]['mapa']);
    }
    
    public function toFirst($consulta){
        if(array_key_exists($consulta, $_SESSION[$this->session])) {
            $var = $_SESSION[$this->session][$consulta];
            unset ($_SESSION[$this->session][$consulta]);
            $_SESSION[$this->session][$consulta] = $var; 
        }
    }
    
    public function getFirstResult(){
        $var = end($_SESSION[$this->session]);
        return $this->uncompress($var['resultado']);
    }
    
    public function getFirstMap(){
        $var = end($_SESSION[$this->session]);
        return $this->uncompress($var['mapa']);
    }
    
    public function getConsult(){
        $var = array_keys($_SESSION[$this->session]);
        $out = array();
        foreach($var as $v){
            $out[] = base64_decode($v);
        }
        return $out;
    }
    
    public function compress($res){
        if($res == "") return "";
        $res = serialize($res);
        $res = gzcompress($res);
        return $res;
    }
    
    public function uncompress($res){
        if($res == "") return "";
        $res = gzuncompress($res);
        $res = unserialize($res);
        return $res;
    }
}

?>
