<?php


require_once __DIR__."/vendor/autoload.php";

session_start();

use App\Routeur;


$routeur = new Routeur($_SERVER["REQUEST_URI"]);

$routeur->rootGet("App\Controller\UserController@index"); //route principale
/**
 * Ce sont les variables $_GET qui donne un aspect MVC
 */
$routeur->get(["method"=>"logout"],"App\Controller\UserController@logout");
$routeur->post(["method"=>"login"],"App\Controller\UserController@login");
$routeur->get(["method"=>"shop"],"App\Controller\ShopController@index");
$routeur->get(["method"=>"validateCart"],"App\Controller\ShopController@validateCart");

//Méthodes à être lancées en AJAX :

$routeur->post(["method"=>"addToCart","xhr"=>true],"App\Controller\ShopController@addToCart");
$routeur->post(["method"=>"updateCarts","xhr"=>true],"App\Controller\ShopController@updateCarts");
$routeur->get(["method"=>"getCart","xhr"=>true],"App\Controller\ShopController@getCartXHR");