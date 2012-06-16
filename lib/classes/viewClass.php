<?php

class view{
    
    public function prepare($result){
        
        $resultado = $map = $layer = $tmp_res = "";
        $out = array();
        if(!empty ($result)){
            $table = new tableClass();
            $svg   = new svgClass();

            foreach($result as $id => $array){
                $tmp         = "Consulta realizada em: ".$array['time'] . "<hr/>";
                $resultado[] = $tmp . $table->draw($array['res'], $id);
                $layer     = base64_decode($id);
                $layers[]  = $layer;

                if(geografico){
                    $map .= $svg->draw($id, $array['mapa'], 600, 100, 5);
                }

            }
            //$resultado = array_reverse($resultado);
            if(geografico)
            $out['map']    = $map;
            $out['result'] = implode(" ",$resultado);
            $out['first']  = $layer;
            $out['layers'] = $layers;
        }
        return $out;
    }
    
    public function drawLayers($layers){
        
        if(!is_array($layers)) return "";
        $var = "";
        foreach($layers as $layer){
             $key  = base64_encode($layer);
             $var .= $this->sortable($key, $consulta);
        }
        return $var;
    }
    
    public function sortable($key, $consulta){
        $link    = "?consulta=$key";                   
        $var = "
        <li class='layer border'>
            <a href='$key' class='selecionar'>
                <div class='item bg bg-hover'>". nl2br($consulta)."</div>
            </a>
            <div class='acoes'>$acoes
                <a href='$link&action=recuperaconsulta' class='action'><img src='img/btn_editar.png'/></a>";
            if(geografico){
                $var .= "<a class='action colorSelector bgcolor' id='#bg_$key'></a>";
                $var .= "<a class='action colorSelector licolor' id='#li_$key'></a>";
            }    
            $var .= "<a href='$link&action=apagaconsulta' class='action'><img src='img/btn_excluir.png'/></a>
            </div>
        </li>
        ";
        return $var;
    }
}

?>