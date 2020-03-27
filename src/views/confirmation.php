<?php

require __DIR__ . "/../../entrypoint.php";

use App\Utilitaire;
use App\Entite\Utilisateur;

if(!Utilitaire::exists($_SESSION,["utilisateur"])){
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Récapitulatif de vos articles</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <style>
        body{
            font-family: Ubuntu,sans-serif,monospace;
            padding:0;
            margin:0;
        }
        .error
        {
            color: red;
        }

        header{
            text-align: center;
        }

        #title
        {
            text-align: center;
        }
        #listeProduits
        {
            margin: 3em;
        }
        section{
            margin: 3em;
        }
        #cart .modal-dialog
        {
            width: 600px;
        }
        #formCriteres
        {
            margin: 10px;
        }
        #recherche
        {
            margin: 0;
        }

    </style>
</head>
