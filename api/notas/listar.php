<?php

//require "../../models/notes.php";
require($_SERVER['DOCUMENT_ROOT']."/apirest/models/notes.php");
$notas = new NotasModel();
header("Content-Type: application/json");
echo json_encode($notas->read());
?>