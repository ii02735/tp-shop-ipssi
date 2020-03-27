<?php


namespace App\Controller;


use App\DAO\DbDaoUser;
use App\DAO\IDao;
use App\Entite\Utilisateur;
use App\Exception\UtilisateurException;
use App\Utilitaire;

class UserController
{
    /**
     * @var IDao
     */
    private $userDao;

    public function __construct()
    {
        $this->userDao = new DbDaoUser();
    }

    public function index()
    {
        if(!Utilitaire::exists($_SESSION,["utilisateur"])){
            $error = Utilitaire::exists($_SESSION,["error"]) ? $_SESSION["error"] : ""; //récupération du dernier message d'erreur enregistré en session
            Utilitaire::render("connexion.php", ["message" => $error]);
            if($error != "") //si on a bien un message d'erreur, on doit alors nettoyer l'erreur en session pour la prochaine fois (message flash)
                unset($_SESSION["error"]);
        }else{
            header("Location: index.php?method=shop");
        }

    }

    public function login()
    {
        if(!Utilitaire::exists($_POST,["username","password"])) {
            $_SESSION["error"] = "Veuillez bien remplir tous les champs";
            header("Location: index.php");
        }
        else
        {
            try {
                $user = $this->userDao->findOne(["name" => $_POST["username"]]);
                if(password_verify($_POST["password"],$user->getPassword())){ //si la vérification du mot de passe réussit : on redirige l'utilisateur sur la page du magasin
                    $_SESSION["utilisateur"] = $user;
                    header("Location: index.php?method=shop");
                }else{
                    $_SESSION["error"] = "Mot de passe invalide";
                    header("Location: index.php");
                }
            }catch(UtilisateurException $e) //Si l'utilisateur ne parvient pas à être retrouvé on le redirige vers la page du formulaire
            {
                $_SESSION["error"] = $e->getMessage();
                header("Location: index.php");
            }

        }
    }

    public function logout()
    {
        session_destroy();
        Utilitaire::render("connexion.php");
    }
}