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

            #existentRole
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
        <?php
            if(Utilitaire::exists($_SESSION,["erreur"])) {
                $erreur = $_SESSION["erreur"];
                unset($_SESSION["erreur"]);
            }
         ?>
        <?php require __DIR__ . "/navbar.php" ?>
        <section id="listeUtilisateurs">
        <?php if(!isset($erreur)):?>
                <?php if(Utilitaire::exists($_SESSION,["utilisateur"])): ?>
                    <h4 class="text-center">Bienvenue <?php echo strtoupper($_SESSION["utilisateur"]->getName())." ".$_SESSION["utilisateur"]->getFirstname() ?>&nbsp;!</h4>
                    <br />
                <?php endif ?>
                <?php if(Utilitaire::exists($_SESSION,["utilisateur"]) && $_SESSION["utilisateur"]->getRole() == "admin"): ?>
                    <h4 id="title" class="text-center mb-3">La liste des utilisateurs est la suivante :</h4>
                <?php endif ?>
            <?php endif ?>
            <table class="table mt-4" id="utilisateurs">
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Adresse mail</th>
                    <th>Rôle</th>
                    <th>Action</th>
                </tr>
                <?php
                $categories = []; // à stocker pour aider la création de la nouvelle catégorie
                $ingredients = [];  //idem
                foreach ($users as $object): ?>
                <?php
                    //ne pas afficher l'utilisateur qui est connecté
                    if($object == $_SESSION["utilisateur"]->toArray())
                        continue;
                ?>
                <tr>
                    <td class="nomUtilisateur">
                       <?php echo $object["name"] ?>
                    </td>
                    <td>
                        <?php echo $object["firstname"] ?>
                    </td>
                    <td>
                        <?php echo $object["email"] ?>
                    </td>
                    <td>
                        <?php echo $object["role"] ?>
                    </td>
                    <?php if(Utilitaire::exists($_SESSION,["utilisateur"])): ?> <!--autoriser l'ajout uniquement lorsqu'on est connecté -->
                    <td>
                        <?php if(Utilitaire::exists($_SESSION,["utilisateur"]) && $_SESSION["utilisateur"]->getRole() == "admin"): ?>
                            <button class="btn btn-info modifierUtilisateur" value='<?php echo json_encode($object) ?>' data-toggle="modal" data-target="#changeUser">Modifier</button>
                            <button class="btn btn-danger supprimerUtilisateur" value='<?php echo json_encode($object) ?>' data-toggle="modal" data-target="#deleteUser">Supprimer</button>
                          <? endif ?>
                    </td>
                    <?php endif ?>
                </tr>
                <?php endforeach; ?>
            </table>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createUser">Créer un utilisateur</button>
            <?php if(Utilitaire::exists($_GET,["critere_recherche","text_recherche"])): ?>
                <?php  echo "<a class=\"link my-4\" href=\"index.php\">Revenir à l'accueil</a><br/><br/>"; ?>
            <?php endif ?>
        </section>

        <?php
            require __DIR__."/modal_change_user.php";
            require __DIR__."/modal_create_user.php";
            require __DIR__."/modal_delete_user.php";
        ?>

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