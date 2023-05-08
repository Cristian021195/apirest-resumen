<?php
require($_SERVER['DOCUMENT_ROOT']."/apirest/config/connect.php");
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

?>