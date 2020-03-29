<?php

require_once __DIR__."/vendor/autoload.php";

session_start();

use App\Routeur;
use App\Utilitaire;

//Vérification de l'ip de la session avec celle du client
if(Utilitaire::exists($_SESSION,["ips"]) && $_SESSION["ips"] != Utilitaire::getIPs())
    header("Location: index.php?method=logout");

$routeur = new Routeur($_SERVER["REQUEST_URI"]);



$routeur->rootGet("App\Controller\ShopController@index"); //route principale : affichage des produits
/**
 * Ce sont les variables $_GET qui donne un aspect MVC
 */
$routeur->get(["method"=>"loginPage"],"App\Controller\UserController@index");
$routeur->get(["method"=>"logout"],"App\Controller\UserController@logout");
$routeur->post(["method"=>"login"],"App\Controller\UserController@login");
$routeur->post(["method"=>"subscribe"],"App\Controller\UserController@subscribe");
$routeur->get(["method"=>"validateCart"],"App\Controller\ShopController@validateCart");
$routeur->post(["method"=>"updateProduct"],"App\Controller\AdminController@updateProduct");
$routeur->post(["method"=>"createProduct"],"App\Controller\AdminController@createProduct");
$routeur->post(["method"=>"deleteProduct"],"App\Controller\AdminController@deleteProduct");

$routeur->get(["method" => "users"],"App\Controller\AdminController@getUsers");
$routeur->post(["method" => "updateUser"],"App\Controller\AdminController@updateUser");
$routeur->post(["method" => "deleteUser"],"App\Controller\AdminController@deleteUser");
$routeur->post(["method" => "createUser"],"App\Controller\AdminController@createUser");

//Méthodes à être lancées en AJAX :

$routeur->post(["method"=>"addToCart","xhr"=>true],"App\Controller\ShopController@addToCart");
$routeur->post(["method"=>"updateCarts","xhr"=>true],"App\Controller\ShopController@updateCarts");
$routeur->get(["method"=>"getCart","xhr"=>true],"App\Controller\ShopController@getCartXHR");