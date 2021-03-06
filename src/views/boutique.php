<?php

    require __DIR__ . "/../../entrypoint.php";

    use App\Utilitaire;
    use App\Entite\Utilisateur;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Boutique en ligne</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
        <style>
            input{
                margin-bottom: 10px;
            }
            body{
                font-family: Ubuntu,sans-serif,monospace;
                padding:0;
                margin:0;
            }
            .error
            {
                color: red;
            }
            small[class='error']
            {
                display:block;
                margin-bottom:20px;
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

            #existentCategory
            {
                display: block;
            }

            #newCat,#newIng,#newCatUpdate,#newIngUpdate
            {
                margin-top: 10px;
                display: none;
            }

        </style>
    </head>
    <body>
        <?php require __DIR__ . "/navbar.php" ?>
        <section id="listeProduits">
        <?php
        if(Utilitaire::exists($_SESSION,["erreur"])) {
            $erreur = $_SESSION["erreur"];
            unset($_SESSION["erreur"]);
        }
        ?>
        <?php if(!isset($erreur)):?>
            <?php if(Utilitaire::exists($_GET,["critere_recherche","text_recherche"])): ?>
                <h4 id="title" class="text-center mb-3">Résultats de votre recherche :</h4>
            <?php else: ?>
                <?php if(Utilitaire::exists($_SESSION,["utilisateur"])): ?>
                    <h4 class="text-center">Bienvenue <?php echo strtoupper($_SESSION["utilisateur"]->getName())." ".$_SESSION["utilisateur"]->getFirstname() ?>&nbsp;!</h4>
                    <br />
                <?php endif ?>
                <?php if(Utilitaire::exists($_SESSION,["utilisateur"]) && $_SESSION["utilisateur"]->getRole() == "admin"): ?>
                    <h4 id="title" class="text-center mb-3">La liste des produits existants est la suivante :</h4>
                <?php else: ?>
                    <h4 id="title" class="text-center mb-3">Nous proposons les produits suivants pour le moment :</h4>
                <?php endif ?>
            <?php endif ?>
            <table class="table mt-4" id="produits">
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Catégorie</th>
                    <th>Ingrédients</th>
                    <th>Prix unitaire</th>
                    <?php if(Utilitaire::exists($_SESSION,["utilisateur"])): ?>
                    <th>Action</th>
                    <?php endif ?>
                </tr>
                <?php
                $categories = []; // à stocker pour aider la création de la nouvelle catégorie
                $ingredients = [];  //idem
                foreach ($produits as $object): $categories[] = $object["categorie"]; $ingredients[] = $object["ingredient"]; ?>
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
                        <?php echo $object["ingredient"] ?>
                    </td>
                    <td>
                        <?php echo $object["prix"] ?>&nbsp;&euro;
                    </td>
                    <?php if(Utilitaire::exists($_SESSION,["utilisateur"])): ?> <!--autoriser l'ajout uniquement lorsqu'on est connecté -->
                    <td>
                        <?php if(Utilitaire::exists($_SESSION,["utilisateur"]) && $_SESSION["utilisateur"]->getRole() == "admin"): ?>
                            <button class="btn btn-info modifierProduit" value='<?php echo json_encode($object) ?>' data-toggle="modal" data-target="#changeProduct">Modifier</button>
                            <button class="btn btn-danger supprimerProduit" value='<?php echo json_encode($object) ?>' data-toggle="modal" data-target="#deleteProduct">Supprimer</button>
                        <? else: ?>
                            <button class="btn btn-info ajout" value='<?php echo json_encode(["nom"=>$object["nom"],"id"=>$object["idp"]]) ?>' data-toggle="modal" data-target="#modal">Ajouter au panier</button>
                        <? endif ?>
                    </td>
                    <?php endif ?>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else:
                    echo "<h3 class='error mb-4'>$erreur</h3>";
                    echo "<a class=\"link my-4\" href=\"index.php\">Revenir à l'accueil</a><br/><br/>";
                  endif;
            ?>
            <?php if(Utilitaire::exists($_GET,["critere_recherche","text_recherche"])): ?>
                <?php  echo "<a class=\"link my-4\" href=\"index.php\">Revenir à l'accueil</a><br/><br/>"; ?>
            <?php endif ?>
            <?php if(Utilitaire::exists($_SESSION,["utilisateur"]) && $_SESSION["utilisateur"]->getRole() != "admin"): ?>
                <button class="btn btn-primary" id="btnCart" data-toggle="modal" data-target="#cart">Voir mon panier</button>
                <button class="btn btn-success" id="validateCart">Valider mon panier</button>
            <?php else: ?>
                <button class="btn btn-primary" id="btnProduct" data-toggle="modal" data-target="#create">Créer un produit</button>
            <?php endif ?>
        </section>

        <?php if(Utilitaire::exists($_SESSION,["utilisateur"]) && $_SESSION["utilisateur"]->getRole() == "admin"){
            $categories = array_unique($categories); //suppression des doublons (le DAO ne s'y occupe pas)
            $ingredients = array_unique($ingredients);
            require __DIR__."/modal_create.php";
            require __DIR__ . "/modal_change.php";
            require __DIR__ . "/modal_delete.php";
        } else {
            require __DIR__."/modal_add.php";
            require __DIR__."/modal_cart.php";
        }?>

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