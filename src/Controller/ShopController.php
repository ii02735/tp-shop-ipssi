<?php


namespace App\Controller;


use App\DAO\IDao;
use App\Entite\Panier;
use App\Exception\CategorieException;
use App\Exception\IngredientException;
use App\Exception\PanierException;
use App\Exception\ProduitException;
use App\Utilitaire;

class ShopController
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
        $this->categorieDao = $categorieDao;
        $this->ingredientDao = $ingredientDao;
        $this->panierDao = $panierDao;
        $this->produitDao = $produitDao;
        $this->userDao = $userDao;
    }

    /**
     * Affichage de tous les produits (page principale)
     * Ne dépend pas de l'utilisateur est connecté
     */
    public function index(){
            //Pour la recherche, on passe par des paramètres get, donc on peut réutiliser la route d'accueil
            if(Utilitaire::exists($_GET,["critere_recherche","text_recherche"])){
                $this->searchProducts();
            }else {
                try {
                    $response = [];
                    $produits = $this->produitDao->findAll();
                    foreach ($produits as $produit)  // on fait une jointure en PHP pour récupérer l'ingrédient, puis la catégorie
                    {
                        $categorie = $this->categorieDao->findOne(["idp" => $produit->getIdp()]);
                        $ingredient = $this->ingredientDao->findOne(["idp" => $produit->getIdp()]);
                        //On fusionne l'objet produit sérialisé en tableau avec les autres propriétés lui appartenant
                        $response[] = array_merge($produit->toArray(),["categorie" => $categorie->getLibelle(), "idc" => $categorie->getIdc(), "idi" => $ingredient->getIdi(), "ingredient" => $ingredient->getNom()]);
                    }
                    /**
                     * Tri du tableau, d'abord par la catégorie, puis ensuite par le nom du produit
                     * On commence un tri par ordre alphabétique croissant avec la valeur des différentes catégories
                     * puis ensuite par le nom de chaque produit
                     */
                    array_multisort(array_column($response,"categorie"),
                        SORT_ASC,
                        array_column($response,"nom"),SORT_ASC,$response);
                    Utilitaire::render("boutique.php", ["produits" => $response]);
                } catch (ProduitException | CategorieException | IngredientException $e) {
                    file_put_contents(__DIR__."/../../log_error.txt",print_r(["message" => $e->getMessage(), "date" => date("Y-m-d H:i")],true),FILE_APPEND);
                    Utilitaire::render("boutique.php", ["erreur" => $e->getMessage()]);
                }
            }
    }

    /**
     * Ajout d'un nouvel article à un panier
     */
    public function addToCart()
    {
        if(Utilitaire::exists($_POST,["idp","qte"])) {
            /**
             * Si le panier n'existe pas pour le produit et le panier associé il faut le créer
             * sinon on le met à jour
             */
            try {
                $panier = $this->panierDao->findOne(["idp" => $_POST["idp"]]);
                $panier->setQuantite($_POST["qte"]+$panier->getQuantite());
                $this->panierDao->update($panier);
            } catch (PanierException $e) {
                //on crée un nouveau panier pour le produit
                $panier = new Panier();
                $panier->setIdClient($_SESSION["utilisateur"]->getId());
                $panier->setQuantite($_POST["qte"]);
                $panier->setIdp($_POST["idp"]);
                $this->panierDao->create($panier);
            }
        }else{
            header("HTTP/1.1 400 Bad Request");
        }
    }

    //Récupérer le panier de l'utiliateur (les paniers en réalité, car l'utilisateur en a plusieurs, car il pris des aricles différents)
    //Il s'agit d'une méthode utilitaire, qui sera utilisé pour d'autres actions
    private function getCart()
    {
        try {
            //on récupère les paniers existants (pour ensuite les combiner en un)
            $paniers = $this->panierDao->find(["id_client" => $_SESSION["utilisateur"]->getId()]);
            $reponse_panier = []; //on doit donner le nom des produits mis dans le panier
            foreach ($paniers as $panier) {
                try {
                    $produit_object = $this->produitDao->get($panier->getIdp());
                    $produit_data = [
                        "id_panier" => $panier->getIdPanier(), //envoi de l'ID du panier afin de le MODIFIER par la suite (réduire quantité, supprimer produit...)
                        "idp" => $produit_object->getIdp(),
                        "description" => $produit_object->getDescription(),
                        "nom" => $produit_object->getNom(),
                        "prix" => $produit_object->getPrix(),
                        "qte" => $panier->getQuantite()
                    ];
                    array_unshift($reponse_panier, $produit_data); //on récupère tous les produits pour retourner qu'un seul "panier"
                }catch(PanierException $e)
                {
                    file_put_contents(__DIR__."/../../log_error.txt",print_r(["message" => $e->getMessage(), "date" => date("Y-m-d H:i")],true),FILE_APPEND);
                    throw new PanierException($e->getMessage());
                }
            }
            /**
             * Tri du tableau, par le nom lorsqu'on accède au panier
             */
            array_multisort(array_column($reponse_panier,"nom"),
                SORT_ASC,$reponse_panier);
            return $reponse_panier;
        }catch(PanierException $e) //On remonte les exceptions pour qu'elles soient gérées dans la méthode qui invoque getCart
        {
            throw new PanierException($e->getMessage(),$e->getCode());
        }
    }

    //Résultat de la fonction getCart renvoyé pour requête XHR
    public function getCartXHR()
    {
        try {
            $panier_utilisateur = $this->getCart();
            echo json_encode($panier_utilisateur);
        }catch(PanierException $e)
        {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(["type" => "erreur", "message" => $e->getMessage()]);
        }
    }

    /**
     * Mettre à jour un panier
     * Cette méthode s'applique lorsqu'on modifie ou supprime
     * un article
     */
    public function updateCarts()
    {
        if (Utilitaire::exists($_SESSION, ["utilisateur"])) {
            if (Utilitaire::exists($_POST, ["data"])) {
                foreach ($_POST["data"] as $cart) {
                    if ($cart["delete"] == 1) { //si on détecte qu'on a souhaité supprimer un panier (un produit du panier), on doit le faire en bdd

                        try {
                            $this->panierDao->delete($this->panierDao->get($cart["id_panier"]));
                        } catch (PanierException $e) {
                            header("HTTP/1.1 500 Server Error");
                            echo $e->getMessage();
                        }

                    } else { //si on souhaite juste modifier la quantité
                        try {
                            $panier = $this->panierDao->get($cart["id_panier"]);
                            $panier->setQuantite($cart["qte"]);
                            $this->panierDao->update($panier);
                        } catch (PanierException $e) {
                            header("HTTP/1.1 500 Server Error");
                            echo "Une erreur est survenue pendant la modification du panier";
                        }
                    }
                }
                try { //on doit vérifier si le panier est vide après mise à jour
                      //l'exception confirmera cette règle
                    $this->panierDao->find(["id_client" => $_SESSION["utilisateur"]->getId()]);
                    echo "Votre panier a bien été mis à jour";
                }catch(PanierException $e)
                {
                    //Si le panier est vide...on doit informer le Front, désactiver les boutons par exemple
                    header("HTTP/1.1 404 Not Found");
                    echo json_encode(["type" => "erreur", "message" => $e->getMessage()]);
                }

            } else {
                header("HTTP/1.1 400 Bad Request");
            }
        }else{
            header("HTTP/1.1 401 Unauthorized");
        }
    }
    //Recherche + composition complète des produits
    private function searchProducts()
    {
           try {
               $response = [];
                switch ($_GET["critere_recherche"]) { //selon le choix du critère, nous devons rassembler les propriétés liées aux produits
                    case "produit":
                        $produits = $this->produitDao->findLike(["nom" => "%" . $_GET["text_recherche"] . "%"]);
                        foreach ($produits as $produit) {
                            $response[] = $produit->toArray();
                        }
                        foreach ($response as &$array) {
                            $array["categorie"] = $this->categorieDao->findOne(["idp" => $array["idp"]])->getLibelle();
                        }

                        foreach ($response as &$array) {
                            $array["ingredient"] = $this->ingredientDao->findOne(["idp" => $array["idp"]])->getNom();
                        }
                        break;

                    case "categorie":
                        $categories = $this->categorieDao->findLike(["libelle" => "%" . $_GET["text_recherche"] . "%"]);
                        foreach ($categories as $categorie) {
                            $response[] = [ "categorie" => $categorie->getLibelle(), "idp" => $categorie->getIdp() ];
                        }

                        foreach ($response as &$array) {
                            $produit_obj = $this->produitDao->get($array["idp"]);
                            $array = array_merge($produit_obj->toArray(),$array);
                        }

                        foreach ($response as &$array) {
                            $array["ingredient"] = $this->ingredientDao->findOne(["idp" => $array["idp"]])->getNom();
                        }
                        break;

                    case "ingredient" :
                        $ingredients = $this->ingredientDao->findLike(["nom" => "%" . $_GET["text_recherche"] . "%"]);
                        foreach ($ingredients as $ingredient) {
                            $response[] = [ "ingredient" => $ingredient->getNom(), "idp" => $ingredient->getIdp() ];
                        }
                        //Passage par référence pour ajouter les modifications sur le sous-tableau durant la boucle
                        foreach ($response as &$array)
                        {
                            $array["categorie"] = $this->categorieDao->findOne(["idp" => $array["idp"]])->getLibelle();
                        }

                        foreach ($response as &$array) {
                            $produit_obj = $this->produitDao->get($array["idp"]);
                            $array = array_merge($produit_obj->toArray(),$array);
                        }
                        break;
                    default:
                        header("Location: index.php");
                        exit;
                }
               /**
                * Tri du tableau, d'abord par la catégorie, puis ensuite par le nom du produit
                * On commence un tri par ordre alphabétique croissant avec la valeur des différentes catégories
                * puis ensuite par le nom de chaque produit
                */
               array_multisort(array_column($response,"categorie"),
                   SORT_ASC,
                   array_column($response,"nom"),SORT_ASC,$response);
                Utilitaire::render("boutique.php",["produits" => $response]);
            } catch (ProduitException | CategorieException | IngredientException $e) {
                Utilitaire::render("boutique.php",["erreur" => $e->getMessage()]);
            }
    }
    /**
     * Méthode qui va confirmer le panier de l'utilisateur
     * Et par conséquent, lui afficher une liste de ses courses
     */
    public function validateCart()
    {
        if(Utilitaire::exists($_SESSION,["utilisateur"])){
            try {
                $panier = $this->getCart();
                $panier_riche = [];
                foreach ($panier as $produit_panier)
                {
                    $categorie = $this->categorieDao->findOne(["idp" => $produit_panier["idp"]])->getLibelle();
                    //enrichissement des objets produits (ajout catégorie + ingrédient)
                    $panier_riche[] = array_merge($produit_panier,["categorie" => $categorie ]);
                }

                //Destruction du panier du client en base de données

                foreach ($panier as $produit_panier)
                {
                    try {
                        $this->panierDao->delete($this->panierDao->get($produit_panier["id_panier"]));
                    }catch(PanierException $e)
                    {
                        Utilitaire::render("confirmation.php",["erreur" => "Échec de la validation de votre panier"]);
                        exit;
                    }
                }

                /**
                 * Tri du tableau, d'abord par la catégorie, puis ensuite par le nom du produit
                 * On commence un tri par ordre alphabétique croissant avec la valeur des différentes catégories
                 * puis ensuite par le nom de chaque produit
                 */
                array_multisort(array_column($panier_riche,"categorie"),
                    SORT_ASC,
                    array_column($panier_riche,"nom"),SORT_ASC,$panier_riche);
                Utilitaire::render("confirmation.php",["panier" => $panier_riche]);
            }catch(PanierException $e)
            {
                if($e->getCode() == 404)
                    Utilitaire::render("confirmation.php",["erreur" => "Vous ne possédez pas de panier : impossible de confirmer vos achats"]);
            }
        }else{
            header("index.php");
        }
    }
}