<?php
//  	Connessione al DBMS e selezione del database.
//
// 	nome dell'host
//
$host = "localhost";
//
// 	username dell'utente in connessione
//
$user = "root";
//
// 	password dell'utente
//
$password = "";
//
// nome del database
//
$db = "cap";
//
// 	Istanza dell'oggetto della classe MySQLi
//
$connessione = new mysqli($host, $user, $password, $db);
//
// 	Verifica su eventuali errori di connessione
//
if ($connessione->connect_errno)
{
    echo("Connessione fallita: ".$connessione->connect_error.".");
    exit();
}
?>