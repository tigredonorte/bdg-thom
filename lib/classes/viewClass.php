<?php

class view{
    
    public function prepare($result){
        if(!empty ($temp)){
            $table = new tableClass();
            $svg   = new svgClass();

            foreach($temp as $id => $array){
                $result .= "Consulta realizada em: ".$array['time'] . "<hr/>";
                $result .= $table->draw($array['res'], $id);
                $layers[] = base64_decode($id);

                if(geografico)
                    $map .= $svg->draw($id, $array['map'], 600, 100, 5);

            }
            $this->first  = end($layers);
            //$layers = array_reverse($layers);
        }
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