<?php

require __DIR__."/vendor/autoload.php";

$userDao = new \App\DAO\DbDaoUser();

$user = $userDao->get(10);

$array = $user->toJson();

var_dump($array);
//$dom = new DOMDocument("1.0","UTF-8");
//$dom->preserveWhiteSpace = false; ////////IMPORTANT (à mettre avant load)
//$dom->load(__DIR__ . "/config/daoDependences.xml");
//$dom->formatOutput = true;
//$xpath = new DOMXPath($dom);
//$usercontroller = $xpath->query("/root/controllers/controller[@class='App\Controller\UserController']");
//$parameters = [];
//
//foreach ($xpath->query('//comment()') as $comment) {
//    $comment->parentNode->removeChild($comment);
//}
//
//foreach($usercontroller->item(0)->childNodes as $node)
//{
//    foreach($node->childNodes as $child)
//        $parameters[$child->nodeName] = $child->textContent;
//}
//
//$dbModeQuery = $xpath->query("/root/load/controller[@class='App\Controller\UserController']/@daoMode");
//$dbMode = $dbModeQuery[0]->value;
//
//$loadInstances = [];
//
//foreach ($parameters as $name => $value)
//{
//    $loadInstances[$name] =
//}
//foreach ($dbMode as $node)
//{
//    array_push($values,$node->value);
//}

echo "hello world";
/**
 * récupérer tous les paramètres
 * Puis, en fonction du mode, les initialiser aux bonnes valeurs
 */