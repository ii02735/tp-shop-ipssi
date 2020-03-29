<?php

require __DIR__ . "/../../entrypoint.php";

use App\Utilitaire;
use App\Entite\Utilisateur;

    if(!Utilitaire::exists($_SESSION,["utilisateur"])){
        header("Location: connexion.php");
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
        <section>
        <?php if(isset($erreur)):?>
            <div class="container">
                <h4 class="error text-center"><?php echo $erreur ?></h4>
                <div class="text-center my-4"><a class="link" href="index.php">Revenir à l'accueil</a></div>
            </div>
        <?php else: ?>
            <h4 class="mb-4">Merci pour votre achat ! </h4>
            <h5 class="mb-3">Informations client : </h5>
            <p>Nom : <strong><?php echo $_SESSION["utilisateur"]->getName() ?></strong></p>
            <p>Prénom : <strong><?php echo $_SESSION["utilisateur"]->getFirstname() ?></strong></p>
            <p>Adresse mail : <strong><?php echo $_SESSION["utilisateur"]->getEmail() ?></strong></p>
            <h5 class="mb-3">Liste des produits achetés :</h5>
            <table class="table mt-4" id="produits">
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Catégorie</th>
                    <th>Prix unitaire</th>
                    <th>Quantité prise</th>
                    <th>Prix total produit</th>
                </tr>
                <?php
                $prixTotal = 0;
                foreach ($panier as $object): ?>
                    <tr>
                        <td class="nomProduit">
                            <?php echo $object["nom"] ?>
                        </td>
                        <td>
                            <?php echo $object["description"] ?>
                        </td>
                        <td>
                            <?php echo $object["categorie"] ?>
                        </td>
                        <td>
                            <?php
                            //préserver le bon nombre de décimales
                            $parse = explode(".",strval($object["prix"]));
                            //Si la seconde case (nombre après la virqule ne finit pas par zéro, on doit lui en rajouter un)
                            //Il faut aussi regarder si ce nombre n'est pas un chiffre, sinon il n'est pas nécessaire d'ajouter un zéro
                            if(isset($parse[1]) && strlen($parse[1]) == 1 && $parse[1] != "0") {
                                $parse[1] .= "0"; $object["prix"] = join(".",$parse);
                            }
                            echo $object["prix"]?>&nbsp;&euro;
                        </td>
                        <td>
                            <?php echo $object["qte"] ?>
                        </td>
                        <td>
                            <?php
                                $totalPrixProduit = $object["prix"] * $object["qte"];
                                //préserver le bon nombre de décimales
                                $parse = explode(".",strval($totalPrixProduit));
                                //Si la seconde case (nombre après la virqule ne finit pas par zéro, on doit lui en rajouter un)
                                //Il faut aussi regarder si ce nombre n'est pas un chiffre, sinon il n'est pas nécessaire d'ajouter un zéro
                                if(isset($parse[1]) && strlen($parse[1]) == 1 && $parse[1] != "0") {
                                    $parse[1] .= "0"; $totalPrixProduit = join(".",$parse);
                                }
                                $prixTotal += $totalPrixProduit; // Calcul total
                                echo $totalPrixProduit;
                            ?>&nbsp;&euro;
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <article class="mb-3">

                <?php
                    //conserver le bon nombre de zéro
                    $parse = explode(".",strval($prixTotal));
                    if(isset($parse[1]) && strlen($parse[1]) == 1 && $parse[1] != "0")
                        $parse[1] .= "0"; $prixTotal = join(".",$parse);
                ?>
                <h5 class="font-weight-normal">Prix total de vos achats : <strong><?php  echo $prixTotal ?>&nbsp;&euro;</strong></h5>
            </article>
              <a class="link my-4" href="index.php">Revenir à l'accueil</a>
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