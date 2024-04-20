<?php
include "connessione.php";
/*header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");

foreach($_SERVER as $chiave=>$valore){
    echo $chiave."-->".$valore."\n<br>";
}
*/

//elabora header
$metodo = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

//legge il tipo di contenuto inviato dal client
$ct=$_SERVER["CONTENT_TYPE"];
$type=explode("/",$ct);

//legge il tipo di contenuto di ritorno richiesto dal client
$retct=$_SERVER["HTTP_ACCEPT"];
$ret=explode("/",$retct);
echo $type[1];
//print_r($uri);
//echo "metodo-->".$metodo;

if ($metodo=="GET"){
    echo "GET\n";
    
    $body=file_get_contents('php://input');
   // echo $body
   
   //converte in array associativo
    if ($type[1]=="json"){
        $data = json_decode($body,true);
    }
    if ($type[1]=="xml"){
        $xml = simplexml_load_string($body);
        $json = json_encode($xml);
        $data = json_decode($json, true);
    }

    if(isset($uri[2]) && $uri[2] != ""){
        $query = "SELECT * FROM comuni WHERE nome='".$uri[2]."'";
    }
    else {
        $query = "SELECT * FROM comuni";
    }

    $risultato = $connessione->query($query);
    
    if($risultato){
        
        if($risultato->num_rows > 0){

            echo "comune trovato/i";
            header("Content-Type: ".$retct);    
            //restituisce i dati convertiti nel formato richiesto
            while($row = $risultato->fetch_assoc()){
                if ($ret[1]=="json"){
                    echo json_encode($row);
                }
                if ($ret[1]=="xml"){
                    $xml = new SimpleXMLElement('<root/>');
                    array_walk_recursive($row, array ($xml, 'addChild'));    
                    echo $xml->asXML();
                    //alternativa
                    $r='<?xml version="1.0"?><rec><nome>'.$row["nome"].'</nome><cap>'.$row["cap"].'</cap></rec>';
                }
            }
            http_response_code(200);
            echo json_encode($row);
        }
        else{
            echo json_encode(array("message" => "Nessun comune trovato"));
            http_response_code(404);    
        }
    }
    else{
        echo json_encode(array("message" => "Errore nella query"));
        http_response_code(400);
    }
    
}


if ($metodo=="POST"){
    echo "POST\n";
    
    $body=file_get_contents('php://input');
    echo $body;
    
    //converte in array associativo
    if ($type[1]=="json"){
        $data = json_decode($body,true);
    }
    if ($type[1]=="xml"){
        $xml = simplexml_load_string($body);
        $json = json_encode($xml);
        $data = json_decode($json, true);
    }
    
    $query="INSERT INTO `comuni` (`nome`, `cap`) VALUES ('".$data["city"]."', '".$data["cap"]."')";
    $risultato = $connessione->query($query);
    if ($risultato){
        http_response_code(200);
        echo json_encode(array("message" => "comune inserito"));
    }
    else{
        http_response_code(400);
        echo json_encode(array("message" => "errore nell'inserimento"));
    }
    }


if ($metodo=="DELETE"){
    echo "delete\n";
    
    $body=file_get_contents('php://input');
    // echo $body
    
    //converte in array associativo
    if ($type[1]=="json"){
        $data = json_decode($body,true);
    }
    if ($type[1]=="xml"){
        $xml = simplexml_load_string($body);
        $json = json_encode($xml);
        $data = json_decode($json, true);
    }
    
    $query = "DELETE FROM `comuni` WHERE `nome`='".$uri[2]."' OR `cap`='".$uri[2]."'";
    if($connessione->query($query)){
        echo "cancellazione avvenuta";
        http_response_code(200);
    }
    else{
        echo "cancellazione fallita";
        http_response_code(400);
    }
    
}


if ($metodo=="PUT"){
    echo "PUT\n";
    //recupera i dati dall'header
    $body=file_get_contents('php://input');
    // echo $body
    
    //converte in array associativo
    if ($type[1]=="json"){
        $data = json_decode($body,true);
    }
    if ($type[1]=="xml"){
        $xml = simplexml_load_string($body);
        $json = json_encode($xml);
        $data = json_decode($json, true);
    }
    
    $query = "UPDATE `comuni` SET `cap`='".$data["cap"]."' WHERE `nome`='".$uri[2]."'";
    
    
    if($connessione->query($query)){
        echo "aggiornamento avvenuto";
        http_response_code(200);
    }
    else{
        echo "aggiornamento fallito";
        http_response_code(400);
    }
}

?>