<?php require __DIR__ . "/../../entrypoint.php"; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Formulaire de connexion</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
        <style>
            input{
                margin-bottom: 10px;
            }
            body{
                font-family: Ubuntu,sans-serif,monospace;
            }
            .error
            {
                color:red;
            }
        </style>
    </head>
    <body>
        <?php
            $message = null;
            $message_subscribe = null;
            if(isset($_SESSION["subscribe_error"]) && !empty($_SESSION["subscribe_error"])) {
                $message_subscribe = $_SESSION["subscribe_error"]; unset($_SESSION["subscribe_error"]);
            }

            if(isset($_SESSION["error"]) && !empty($_SESSION["error"])) {
                $message = $_SESSION["error"]; unset($_SESSION["error"]);
            }
        ?>
        <section class="row">
            <article class="container col-sm-5 col-12">
                <h3 class="my-4">Veuillez vous connecter</h3>
                <h4 class="error my-4"><?php echo (isset($message) && !empty($message) ? $message : "")  ?></h4>
                <form method="post" action="index.php?method=login">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Adresse mail</label>
                        <input type="text" class="form-control" name="email" aria-describedby="emailHelp" placeholder="Votre adresse mail">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Mot de passe</label>
                        <input type="password" class="form-control" name="password"  placeholder="Votre mot de passe">
                    </div>
                    <input type="submit" class="btn btn-primary mt-2" value="Connexion"/>
                </form>
            </article>
            <article class="container col-sm-5 col-12">
                <h3 class="my-4">Pas de compte ? Inscrivez-vous !</h3>
                <h5 class="error my-4"><?php echo (isset($message_subscribe) && !empty($message_subscribe) ? $message_subscribe : "")  ?></h5>
                <form method="post" action="index.php?method=subscribe">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" name="name" aria-describedby="nameHelp" placeholder="Votre nom">
                    </div>
                    <div class="form-group">
                        <label for="firstname">Prénom</label>
                        <input type="text" class="form-control" name="firstname" aria-describedby="firstnameHelp" placeholder="Votre prénom">
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse mail</label>
                        <input type="text" class="form-control" name="email" aria-describedby="emailHelp" placeholder="Votre adresse mail">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" name="password"  placeholder="Votre mot de passe">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmation de votre mot de passe</label>
                        <input type="password" class="form-control" name="confirm_password"  placeholder="Votre mot de passe">
                    </div>
                    <input type="submit" class="btn btn-primary mt-2" value="Inscription"/>
                </form>
            </article>
        </section>
    </body>
</html>