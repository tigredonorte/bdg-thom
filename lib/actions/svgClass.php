<?php

class svgClass{
    
    public function draw($array, $x_translate = "0", $y_translate = "0", $scale = "1"){
        $var = "";
        foreach($array as $arr){
            $var .= $this->configureGroup($arr, $x_translate, $y_translate, $scale);
        }
        $var = $this->configureSVG($var);
        return $var;
    }
    
    public function drawMultiple($array, $x_translate = "0", $y_translate = "0", $scale = "1"){
        $var = "";
        foreach($array as $arrs){
            foreach($arrs as $arr)
                $var .= $this->configureGroup($arr, $x_translate, $y_translate, $scale);
        }
        $var = $this->configureSVG($var);
        return $var;
    }


    //(V) Esta funcao sera chamada para cada linha da coluna retornada no passo III, colocando os resultados em um array
    public function configurePath($path, $stroke = "black", $stroke_width = "0.005cm", $stroke_opacity="1.0", $fill="none", $fill_opacity="0.0"){
            static $id = 0;
            $id += 1;
            return "<path id=\"$id\" class='path' stroke=\"$stroke\" stroke-width=\"$stroke_width\" 
                          stroke-opacity=\"$stroke_opacity\" fill=\"$fill\" fill-opacity=\"$fill_opacity\" d=\"$path\" />";
    }

    //(VI) Esta funcao sera chamada apos todas as linhas retornadas no passo V terem sido colocadas no array $path_array
    public function configureGroup($path_array, $x_translate = "0", $y_translate = "0", $scale = "1"){
            $result = "<g transform=\"translate($x_translate,$y_translate) scale($scale)\"> ";
            foreach($path_array as $path){
                //$result .= $path;
                $result .= $this->configurePath($path);
            }
            $result .= "</g>";
            return $result;
    }

    //(VII) Esta funcao sera chamada para juntar em um SVG apenas um ou mais grupos gerados através do passo (VI). Esta funcao serve
    //tando para gerar um grafico quanto um merge de varios graficos, bastando que para isso passemos o grupo de cada grafico. 
    public function configureSVG($group_array, $width = "100%", $height='100%'){
            $result = "<svg xmlns='http://www.w3.org/2000/svg' version='1.1'
                style='height: $height; width: $width;' preserveAspectRatio='xMaxYMax meet' >  ";
            if(is_array($group_array)){
                foreach($group_array as $group){
                        $append .= $group;
                }
            }else $result .= $group_array;
            $result .= "</svg>";	
            return $result;
    }
    
    public function consultar($consulta, $sql){
        $result = array('res' => array(), 'mapa' => array());
        //Apenas executa esses passos se se tratar de um select(case insensitive)
        if (preg_match("/^[\t\n ]*SELECT/i", $consulta)) {
            $tabela_temporaria = "tabela_temporaria_" . rand(0, 100000);
            //(I) Cria uma tabela temporaria para receber o resultado da consulta do cliente.
            //Com essa tabela temporaria podemos verificar os tipos dos dados referentes as colunas
            //no passo posterior.
            $sql->consultar("DROP TABLE IF EXISTS $tabela_temporaria;");
            $sql->consultar("CREATE TEMPORARY TABLE $tabela_temporaria as ($consulta);");
            //(II) Retorna, se houver, o nome de UMA coluna geometrica de 'tabela_temporaria'.
            //A jogada da tabela temporaria esta aqui: Buscamos uma coluna do tipo geometry e sobre
            //ela poderemos trabalhar o SVG no passo posterior.
            $query = "SELECT   column_name
                      FROM     information_schema.columns, information_schema.tables
                      WHERE    information_schema.tables.table_name = information_schema.columns.table_name and
                      information_schema.tables.table_name = '$tabela_temporaria' and
                      information_schema.columns.udt_name = 'geometry'
                      LIMIT 1;";
            $result_parcial = $sql->consultar($query);
            //se $result_parcial tiver uma coluna como conteudo...
            if(is_array($result_parcial) && !empty ($result_parcial)){
                $result_parcial = array_shift($result_parcial);
                $result_parcial = $result_parcial['column_name'];
                //(III) Se II retornar o nome de uma coluna com dados geometricos, busca o SVG correspondente.
                //Como resultado teremos uma coluna com paths com distancias relativas. O que nos falta e' iterar
                //sobre as linhas dessa coluna colocando cada path dentro da tag <path>, o conjunto de <path> dentro
                //da tag <g> e o conjunto de <g> dentro de uma tag <svg>. As funcoes basicas estao no proximo passo.
                $result['res']  = $sql->consultar("Select * from $tabela_temporaria;");
                $result['mapa'] = $sql->consultar("SELECT ST_ASSVG($result_parcial, 1) as svg FROM $tabela_temporaria;");
                $sql->consultar("DROP TABLE IF EXISTS $tabela_temporaria;");
                
                //remove a coluna geometrica
                foreach($result['res'] as &$t){
                    if(array_key_exists($result_parcial, $t)) unset($t[$result_parcial]);
                }

            }else{
                $result['res'] = $sql->consultar($consulta);
                $result['mapa'] = array();
            }
        }else{
            $result['res'] = $sql->consultar($consulta);
            $result['mapa'] = array();
        }
        return $result;
    }

}

?>
