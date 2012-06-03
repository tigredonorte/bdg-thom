<?php
    define('bd_dns'     , "mysql");
    define("bd_name"    , "originwe_mvc");
    define("bd_server"  , "localhost");
    define("bd_user"    , "originwe_thom");
    define("bd_password", "12tm3flol");
    define("geografico" , false);
    
    function db_schema() {
        return "SELECT TABLE_NAME as tabela, COLUMN_NAME as coluna, COLUMN_KEY as ktype
                FROM  information_schema.COLUMNS
                WHERE table_schema = '".bd_name."'
                ORDER BY TABLE_NAME, COLUMN_NAME";
    }
?>