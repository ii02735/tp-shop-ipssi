<?php


namespace App\Controller;


use App\DAO\DbDaoCategorie;
use App\DAO\DbDaoIngredient;
use App\DAO\DbDaoPanier;
use App\DAO\DbDaoProduit;
use App\DAO\DbDaoUser;
use App\DAO\IDao;
use App\Entite\Categorie;
use App\Entite\Ingredient;
use App\Entite\Produit;
use App\Entite\Utilisateur;
use App\Exception\CategorieException;
use App\Exception\IngredientException;
use App\Exception\PanierException;
use App\Exception\ProduitException;
use App\Exception\UtilisateurException;
use App\Utilitaire;

class AdminController
{
    /**
     * @var IDao $categorieDao
     */
    private $categorieDao;

    /**
     * @var IDao $ingredientDao
     */
    private $ingredientDao;

    /**
     * @var IDao $produitDao
     */
    private $produitDao;

    /**
     * @var $panierDao IDao
     */
    private $panierDao;

    /**
     * @var IDao $userDao
     */
    private $userDao;

    //injection des dépendences automatique (cf Routeur.php, function invoke())
    public function __construct(IDao $categorieDao,IDao $ingredientDao,IDao $produitDao,IDao $panierDao,IDao $userDao)
    {
        //Évite de vérifier à chaque fois s'il s'agit d'un administrateur, car avant d'invoquer une fonction, on passe par le constructeur
        if(!Utilitaire::exists($_SESSION,["utilisateur"]) ||  Utilitaire::exists($_SESSION,["utilisateur"]) && $_SESSION["utilisateur"]->getRole() != "admin" )
        {
            $_SESSION["erreur"] = "Vous n'êtes pas autorisé à consulter cette ressource";
            header("Location: index.php");
            exit; //ne pas aller plus loin
        }else{
            $this->categorieDao = $categorieDao;
            $this->ingredientDao = $ingredientDao;
            $this->panierDao = $panierDao;
            $this->produitDao = $produitDao;
            $this->userDao = $userDao;
        }
    }

    public function createProduct()
    {                                    //champs envoyés depuis la modale
        if(Utilitaire::exists($_POST,["nameProduct","IngProduct","catProduct","priceProduct"]))
        {
            $produit = new Produit();
            if(Utilitaire::exists($_POST,["descProduct"]))
                $produit->setDescription($_POST["descProduct"]);
            $produit->setNom($_POST["nameProduct"]);
            $produit->setPrix($_POST["priceProduct"]);
            $this->produitDao->create($produit);
            //Si la création réussit, on passe à la création de la catégorie
            $categorie = new Categorie();
            $categorie->setLibelle($_POST["catProduct"]);
            $categorie->setIdp($produit->getIdp());
            $this->categorieDao->create($categorie);

            //idem pour l'ingrédient
            $ingredient = new Ingredient();
            $ingredient->setIdp($produit->getIdp());
            $ingredient->setNom($_POST["IngProduct"]);

            $this->ingredientDao->create($ingredient);

            header("Location: index.php");
        }else{
            $erreur = "Une erreur s'est produite dans la création du produit";
            Utilitaire::render("boutique.php",["erreur" => $erreur]);
        }
    }

    /**
     * Mise à jour du produit par l'administrateur
     */
    public function updateProduct()
    {                                    //champs envoyés depuis la modale
        if(Utilitaire::exists($_POST,["idp","NameProduct","CatProduct","IngProduct","PriceProduct","IngProductId","CatProductId"]))
        {
            try {
                $produit = $this->produitDao->get($_POST["idp"]);
                $produit->setNom($_POST["NameProduct"]);
                if (Utilitaire::exists($_POST, ["DescProduct"]))
                    $produit->setDescription($_POST["DescProduct"]);
                $produit->setPrix($_POST["PriceProduct"]);

                $ingredient = $this->ingredientDao->get($_POST["IngProductId"]);
                $ingredient->setNom($_POST["IngProduct"]);

                if (Utilitaire::exists($_POST, ["newIngProduct"]))
                    $ingredient->setNom($_POST["newIngProduct"]);
                else
                    $ingredient->setNom($_POST["IngProduct"]);

                $categorie = $this->categorieDao->get($_POST["CatProductId"]);
                if (Utilitaire::exists($_POST, ["newCatProduct"]))
                    $categorie->setLibelle($_POST["newCatProduct"]);
                else
                    $categorie->setLibelle($_POST["CatProduct"]);

                $this->produitDao->update($produit);
                $this->categorieDao->update($categorie);
                $this->ingredientDao->update($ingredient);

                header("Location: index.php");
            }catch(ProduitException | IngredientException | CategorieException $e)
            {
                Utilitaire::render("boutique.php",["erreur" => "Une erreur s'est produite durant la mise à jour des produits"]);
            }
        }
    }

    /**
     * Suppression en CASCADE d'un produit (répercution sur les autres tables)
     */
    public function deleteProduct()
    {
        if(Utilitaire::exists($_POST,["dataProduct"]))
        {
            //La donnée a été envoyée en JSON pour rapidement rassembler les informations
            //Et mieux les intégrer au niveau de l'interface : il va falloir décoder la donnée

            $data = json_decode($_POST["dataProduct"],true);
                                    //champs envoyés depuis la modale
            if(Utilitaire::exists($data,["idi","idc","idp"]))
            {
                try {
                    $produit = $this->produitDao->get($data["idp"]);
                    $ingredient = $this->ingredientDao->get($data["idi"]);
                    $categorie = $this->categorieDao->get($data["idc"]);
                    //supprimer aussi ce produit se trouvant dans les paniers
                    try{
                        $paniers = $this->panierDao->find(["idp" => $data["idp"]]);
                        foreach ($paniers as $panier) {
                            $this->panierDao->delete($panier);
                        }
                    }catch(PanierException $e)
                    {
                        //Aucun client n'avait mis ce produit dans un panier
                     //file_put_contents(__DIR__."/../../log_error.txt",print_r(["message"=>$e->getMessage(),"code"=>200,"date"=>date("Y-m-d H:i")]))
                    }

                    //suppression du produit en dernier (éviter contraintes relationnelles)

                    $this->categorieDao->delete($categorie);
                    $this->ingredientDao->delete($ingredient);



                    $this->produitDao->delete($produit);

                    header("Location: index.php");
                }catch(CategorieException | IngredientException | PanierException $e)
                {
                    file_put_contents(__DIR__."/../../log_error.txt",print_r(["message"=>$e->getMessage(),"code"=>$e->getCode(),"date"=>date("Y-m-d H:i")],true),FILE_APPEND);
                    Utilitaire::render("boutique.php",["erreur"=>"Une erreur s'est produite durant la suppression"]);
                }
            }else{
                Utilitaire::render("boutique.php",["erreur"=>"Une erreur s'est produite durant la suppression"]);
            }
        }
    }

    /**
     * Récupérer tous les utilisateurs
     */
    public function getUsers()
    {
        $usersDb = $this->userDao->findAll();
        //conversion des entités utilisateurs en tableau
        $users = array_map(function($user){ return $user->toArray(); },$usersDb);
        array_multisort(array_column($users,"role"),
            SORT_ASC,
            array_column($users,"name"),SORT_ASC,$users);
        Utilitaire::render("utilisateurs.php",["users" => $users]);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function updateUser()
    {                                    //champs envoyés depuis la modale
        if(Utilitaire::exists($_POST,["NameUser","FirstnameUser","EmailUser","RoleUser","id"]))
        {
            $user = $this->userDao->get($_POST["id"]);
            $user->setName($_POST["NameUser"]);
            $user->setFirstname($_POST["FirstnameUser"]);
            $user->setEmail($_POST["EmailUser"]);
            $user->setRole($_POST["RoleUser"]);
            try {
                $this->userDao->update($user);
                header("Location: index.php?method=users");
            }catch(UtilisateurException $e)
            {
                $_SESSION["erreur"] = $e->getMessage();
                header("Location: index.php");
            }
        }
    }

    /**
     * Création d'un utilisateur
     */
    public function createUser()
    {
        if(Utilitaire::exists($_POST,["NameUser","roleUser","PasswordUser","FirstnameUser","EmailUser"]))
        {
            $user = new Utilisateur();
            $user->setRole($_POST["roleUser"]);
            $user->setName($_POST["NameUser"]);
            $user->setFirstname($_POST["FirstnameUser"]);
            $user->setPassword($_POST["PasswordUser"]);
            $user->setEmail($_POST["EmailUser"]);
            try {
                $this->userDao->create($user);
                header("Location: index.php?method=users");
            }catch (UtilisateurException $e)
            {
                $_SESSION["erreur"] = $e->getMessage();
                header("Location: index.php?method=users");
            }

        }
    }

    /**
     * Supprimer un utilisateur
     * D'abord on doit supprimer le panier (éviter contraintes)
     */

    public function deleteUser()
    {
        if(Utilitaire::exists($_POST,["dataUser"]))
        {
            $data = json_decode($_POST["dataUser"],true);
            $user = $this->userDao->get($data["id"]);
            try {
                $panier = $this->panierDao->findOne(["id_client" => $user->getId()]);
                $this->panierDao->delete($panier);
            }catch(PanierException $e)
            {

            }
            $this->userDao->delete($user);
            header("Location: index.php?method=users");
        }else{
            $_SESSION["erreur"] = "Une erreur a été rencontrée durant la suppression de l'utilisateur";
            header("Location: index.php");
        }
    }
}