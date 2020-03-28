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
        <title>Récapitulatif</title>
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
            #recherche
            {
                margin: 0;
            }

        </style>
    </head>
    <body>
    <?php require __DIR__."/navbar.html" ?>
        <section>
        <?php if(isset($erreur)):?>
            <div class="container">
                <h4 class="error text-center"><?php echo $erreur ?></h4>
            </div>
        <?php else: ?>
            <h3>Merci pour votre achat !</h3>
            <h4 id="title" class="text-center mb-3">Voici la liste des produits achetés :</h4>
            <table class="table mt-4" id="produits">
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Catégorie</th>
                    <th>Prix unitaire</th>
                    <th>Prix total produit</th>
                </tr>
                <?php
                foreach ($produit as $panier): ?>
                    <tr>
                        <td class="nomProduit">
                            <?php echo $object["produit"]->getNom() ?>
                        </td>
                        <td>
                            <?php echo $object["produit"]->getDescription() ?>
                        </td>
                        <td>
                            <?php echo $object["categorie"] ?>
                        </td>
                        <td>
                            <?php echo $object["produit"]->getPrix() ?>&nbsp;&euro;
                        </td>
                        <td>
                            <?php echo $object["produit"]->getPrix() * $object["produit"]->getQte() ?>&nbsp;&euro;
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

        </section>



        <?php endif ?>
        <script
                src="https://code.jquery.com/jquery-3.4.1.js"
                integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
                crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script>
            <?php require __DIR__ . "/script.js.php"; ?> //permettre l'utilisation des variables PHP dans le JS
        </script>
    </body>
</html>