<?php
include "connessione.php";

$metodo = $_SERVER['REQUEST_METHOD'];

if($metodo == "GET"){
    $sql = "SELECT * FROM comuni";
    $result = $connessione->query($sql);
    $rows = array();
    while($row = $result->fetch_assoc()){
        $rows[] = $row;
    }
    echo json_encode($rows);
}else if($metodo == "POST"){
    $json = file_get_contents('php://input');
    $dati = json_decode($json);
    $nome = $dati->nome;
    $cap = $dati->cap;
    $sql = "INSERT INTO comuni (nome, cap) VALUES ('$nome', '$cap')";
    $connessione->query($sql);
    echo "Inserimento avvenuto con successo";
}
?>