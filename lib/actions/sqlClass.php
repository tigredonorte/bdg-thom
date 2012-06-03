<?php

class sqlClass{

    private $erro = "";
    public function  __construct() {
        $this->bd_server   = bd_server;
        $this->bd_name     = bd_name;
        $this->bd_user     = bd_user;
        $this->bd_password = bd_password;
        $this->bd_dns      = bd_dns;
        $this->bd_port     = defined("bd_port")?bd_port:"";
        $this->ctime       = 0;
        $this->connect();
    }

    private function connect(){
       $port = ($this->bd_port != "")?";port=$this->bd_port":"";
       $dsn = $this->bd_dns.':host='.$this->bd_server.';dbname='.$this->bd_name.$port;
       $this->pdo = new PDO($dsn, $this->bd_user, $this->bd_password);
       $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
       if(!is_object($this->pdo)){
           die("Não foi possível instanciar o objeto do banco de dados!");
       }
       return true;
    }

    private function execute($query){
        $bd = $this->pdo;
        if(!is_object($bd)) throw new Exception(__CLASS__ . ": Erro na conexão do banco de dados");
        $q = $bd->prepare($query);
        //$q = $bd->prepare('fubá');
        $t1 = microtime();
        $q->execute();
        $t2 = microtime();
        $this->ctime = number_format($t2 - $t1, 4);
        if(stristr($query, "SELECT ") === FALSE) return true;
        return($q->fetchAll(PDO::FETCH_ASSOC));
    }

    //aqui será feita a consulta sql
    public function consultar($sql){
        try {return $this->execute($sql);}
        catch(Exception $e){
            $this->erro = "Query: $sql <br/><br/> Erro: ".$e->getMessage();
            return false;
        }
    }
    
    public function getCTime(){
        return $this->ctime;
    }

    public function getErro(){
        return $this->erro;
    }
    
    public function getSchema(){
        $query = db_schema();
        try {$var = $this->execute($query);}
        catch(Exception $e){
            $this->erro = "Query: $query <br/><br/> Erro: ".$e->getMessage();
            return false;
        }
        $out = array();
        foreach($var as $v){
            $out[$v['tabela']][$v['coluna']] = @$v['ktype'];
        }
        return $out;
    }
}

?>
