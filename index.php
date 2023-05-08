<?php

//include "./config/connect.php";
define('SITE_ROOT', __DIR__);
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

class NotasModel extends DbModel{
    public $status_id;
    public $status;
    public $contenido;
    public $usuario;
    public $tipo;
    public $id;

    public function __destruct(){
        //unset(StatusModel);
    }

    public function create(){
        $this->query = "INSERT INTO notes (contenido) VALUES ('$this->contenido');";
        if($this->set_query()){
            return array("error"=>false, "id"=>$this->connect->insert_id);
        }else{
            return array("error"=>true);
        }
    }
    public function read(){
        $this->query = "SELECT * FROM notes;";
        $this->get_query();
        return $this->rows;
    }
    public function readOne(){

        $this->query = "SELECT * FROM notes WHERE id='$this->id';";
        $this->get_query();
        return $this->rows;
    }
    public function readRange($start, $end){//
        if(($start >= 0) && ($end > 0) && ($start < $end)){
            $this->query = "SELECT * FROM notes LIMIT $start,$end";
            $this->get_query();
        }else{
            $this->rows = null;   
        }        
        return json_encode($this->rows);
    }
    public function update(){

        $this->query = "UPDATE notes set contenido = '$this->contenido' WHERE id = '$this->id';";
        if($this->set_query()){
            return array("error"=>false, "message"=>"No se pudo actualizar");
        }else{
            return array("error"=>true);
        }
    }
    public function delete(){

        $this->query = "DELETE FROM notes WHERE id = '$this->id';";
        if($this->set_query()){
            return array("error"=>false);
        }else{
            return array("error"=>true, "message"=>"No se pudo eliminar la nota");
        }
    }
    
}

$notas = new NotasModel();

header("Content-Type: application/json");
echo json_encode($notas->read());

?>