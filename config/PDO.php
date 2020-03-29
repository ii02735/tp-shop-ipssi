<?php

//Fichier d'injection
//Paramètres de la base de données (interprétés depuis XML)

$xml = simplexml_load_file(__DIR__."/../config/database.xml");
$user = $xml->xpath("/configuration/username")[0];
$password = $xml->xpath("/configuration/password")[0];
$dbname = $xml->xpath("/configuration/dbname")[0];
$engine = $xml->xpath("/configuration/engine")[0];
$host = $xml->xpath("/configuration/host")[0];
$port = $xml->xpath("/configuration/port")[0];
$pdo = new \PDO($engine.":host=".$host.";port=".$port.";dbname=".$dbname.";charset=utf8mb4",$user,$password);

return $pdo;