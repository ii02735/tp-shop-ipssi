<?php

//RÉÉCRITURE D'URL POUR RETROUVER INDEX.PHP

/**
 * Si on charge une vue SANS passer par index.php,
 * on rencontre les limitations suivantes :
 * La session n'est pas chargée
 * Le namespacing de composer n'est pas actif
 */

require_once __DIR__."/vendor/autoload.php";

use App\Utilitaire;


//on récupère le script qui a été exécuté par le serveur
$script = explode("/",$_SERVER["SCRIPT_FILENAME"]);
$file = end($script);

if($file != "index.php") {

    session_start();

    //il faut retrouver index.php
    /**
     * La chaîne contient "src/views"
     * En récupérant ce qui a avant cette partie, on retrouve la racine du projet, et donc index.php
     */
    $index = explode("src/views", $_SERVER["SCRIPT_FILENAME"])[0] . "index.php";
    $index = str_replace($_SERVER["DOCUMENT_ROOT"],"",$index);
    switch ($file) {
        case "connexion.php":
            if (Utilitaire::exists($_SESSION,["utilisateur"]))
                header("Location: $index?method=shop");
            else
                header("Location: $index?method=login");
            exit; //pour ne pas continuer après le header

        case "boutique.php":
            if (Utilitaire::exists($_SESSION,["utilisateur"]))
                header("Location: $index");
            else
                header("Location: $index?method=shop");
            exit;
        case "confirmation.php": //on vérifie qu'un panier pour le client existe
            if(Utilitaire::exists($_SESSION,["utilisateur"])){

                try {
                    $panierDao = new \App\DAO\DbDaoPanier();
                    $panierDao->find(["id_client" => $_SESSION["utilisateur"]->getId()]);
                    header("Location: $index?method=validateCart");
                }catch (\App\Exception\PanierException $e)
                {
                    header("Location: $index?method=shop");
                }
            }

    }
}
