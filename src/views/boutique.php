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
<body>
    <?php require __DIR__."/navbar.html" ?>
    <section id="listeProduits">
    <?php if(!isset($erreur)):?>
        <?php if(Utilitaire::exists($_GET,["critere_recherche","text_recherche"])): ?>
            <h4 id="title" class="text-center mb-3">Résultats de votre recherche :</h4>
        <?php else: ?>
        <h4 id="title" class="text-center mb-3">Nous proposons les produits suivants pour le moment :</h4>
        <?php endif ?>
        <table class="table mt-4" id="produits">
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th>Ingrédients</th>
                <th>Prix unitaire</th>
                <th>Action</th>
            </tr>
            <?php
            foreach ($produits as $object): ?>
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
                    <?php echo $object["ingredient"] ?>
                </td>
                <td>
                    <?php echo $object["produit"]->getPrix() ?>&nbsp;&euro;
                </td>
                <td>
                    <button class="btn btn-info ajout" value='<?php echo json_encode(["nom"=>$object["produit"]->getNom(),"id"=>$object["produit"]->getIdp()]) ?>' data-toggle="modal" data-target="#modal">Ajouter au panier</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else:
            echo "<h3 class='error mb-4'>$erreur</h3>";
          endif;
    ?>
        <button class="btn btn-primary" id="btnCart" data-toggle="modal" data-target="#cart">Voir mon panier</button>
        <button class="btn btn-success" id="validateCart">Valider mon panier</button>

    </section>


    <?php require __DIR__."/modal_add.php" ?>
    <?php require __DIR__."/modal_cart.php" ?>

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