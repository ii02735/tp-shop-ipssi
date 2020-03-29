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

    //injection des dépendences automatique (cf Routeur.php, function invoke())
    public function __construct(IDao $userDao)
    {
        $this->userDao = $userDao;
    }

    public function subscribe()
    {
        if(Utilitaire::exists($_POST,["name","firstname","email","password","confirm_password"]))
        {
            $_SESSION["subscribe_error"] = null;
            //expression régulière pour validation email
            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
            if(!preg_match($regex,$_POST["email"]))
                $_SESSION["subscribe_error"] .= "Adresse mail invalide <br/>";
            if(preg_match('/(\W+)*(\d+)+/',$_POST["name"]))
                $_SESSION["subscribe_error"] .= "Nom invalide <br/>";
            if(preg_match('/(\W+)*(\d+)+/',$_POST["firstname"]))
                $_SESSION["subscribe_error"] .= "Prénom invalide <br/>";
            if($_POST["password"] != $_POST["confirm_password"])
                $_SESSION["subscribe_error"] .= "Les mots de passes ne correspondent pas <br/>";
            if(Utilitaire::exists($_SESSION,["subscribe_error"])) {
                header("Location: index.php?method=loginPage");
            }else{
                $user = new Utilisateur();
                $user->setName($_POST["name"]);
                $user->setFirstname($_POST["firstname"]);
                $user->setEmail($_POST["email"]);
                $user->setPassword($_POST["password"]);
                //Inscription depuis l'interface : il s'agit automatiquement d'un client (seul l'admin peut changer le rôle)
                $user->setRole("client");
                try{
                    $_SESSION["utilisateur"] = $this->userDao->create($user); //on place le nouvel utilisateur en session
                    header("Location: index.php");
                }catch(UtilisateurException $e)
                {
                    if($e->getCode()==23000)
                    {
                        $_SESSION["subscribe_error"] = "L'adresse mail indiquée est déjà utilisée";
                        header("Location: index.php?method=loginPage");
                    }
                }
            }
        }else{
            $_SESSION["subscribe_error"] = "Veuillez remplir tous les champs";
            header("Location: index.php?method=loginPage");
        }
    }

    public function index()
    {
        if(!Utilitaire::exists($_SESSION,["utilisateur"])){

            Utilitaire::render("connexion.php");
//            if ($error != "") //si on a bien un message d'erreur, on doit alors nettoyer l'erreur en session pour la prochaine fois (message flash)
//                unset($_SESSION["error"]);

        }else{
            header("Location: index.php");
        }

    }

    public function login()
    {
        if(!Utilitaire::exists($_POST,["email","password"])) {
            $_SESSION["error"] = "Veuillez bien remplir tous les champs";
            header("Location: index.php?method=loginPage");
        }   //on vérifie le mail avant la connexion (éviter de se rendre inutilement dans la base de données)
        elseif(!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',$_POST["email"])) {
            $_SESSION["error"] = "Adresse mail invalide";
            header("Location: index.php?method=loginPage");
        }
        else
        {
            try {
                $user = $this->userDao->findOne(["email" => $_POST["email"]]);
                if(password_verify($_POST["password"],$user->getPassword())){ //si la vérification du mot de passe réussit : on redirige l'utilisateur sur la page du magasin
                    $_SESSION["utilisateur"] = $user;
                    $_SESSION["ips"] = Utilitaire::getIPs();
                    header("Location: index.php");
                }else{
                    $_SESSION["error"] = "Mot de passe invalide";
                    header("Location: index.php?method=loginPage");
                }
            }catch(UtilisateurException $e) //Si l'utilisateur ne parvient pas à être retrouvé on le redirige vers la page du formulaire
            {
                $_SESSION["error"] = $e->getMessage();
                header("Location: index.php?method=loginPage");
            }

        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: index.php");
    }
}