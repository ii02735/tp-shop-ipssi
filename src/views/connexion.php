<?php require __DIR__ . "/../../entrypoint.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de connexion</title>
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
    <h2>Connectez-vous</h2>
    <h3 class="error"><?php echo $message ?></h3>
    <form method="post" action="index.php?method=login">
        <label for="username">Nom :</label>
        <input type="text" name="username">
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" name="password">
        <br>
        <input type="submit" value="OK">
    </form>
</body>
</html>