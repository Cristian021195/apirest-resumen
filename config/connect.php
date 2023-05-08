<?php

abstract class DbModel{
    private static $db_host = 'localhost';
    private static $db_user = '';
    private static $db_pass = '';
    private static $db_charset = 'utf8';
    private static $db_name = '';
    protected $connect;
    protected $query;
    protected $rows = array();
    protected $num_rows;
    protected $result;
    
    abstract protected function create();//si heredamos, tenemos que implementar.
    abstract protected function read();
    abstract protected function readOne();
    abstract protected function readRange($start, $end);
    //abstract protected function readLike($nombre);
    abstract protected function update();
    abstract protected function delete();

    protected function db_open(){//la coneccion solo la hace le modelo.
        $this->connect = pg_connect("host=localhost port=5433 dbname=notas user=postgres password=admin options='--client_encoding=UTF8'") or die("No se pudo conectar");
    }
    protected function db_close(){
        pg_close($this->connect);
    }
    protected function set_query(){//AQUI PODEMOS MANDAR EL ULTIMO ID. CHECKEAR
        $this->db_open();
        if(pg_query($this->connect, $this->query) or die('asd3')){
            return true;
        }else{
            return false;
        }
        $this->db_close();
    }
    protected function get_query(){
        $this->db_open();
        $this->result = pg_query($this->connect,$this->query) or die("Error al obtener pg_query");
        while($this->rows[] = pg_fetch_assoc($this->result));        
        $this->db_close();

        return array_pop($this->rows);
    }
}

?>