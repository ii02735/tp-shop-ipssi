<?php


namespace App\DAO;


use App\Entite\Entite;
use App\Entite\Ingredient;
use App\Exception\IngredientException;

class DbDaoIngredient extends DbDao
{

    public function __construct()
    {
        parent::__construct("INGREDIENTS");
    }
    /**
     * @param string $id
     * @return Ingredient
     * @throws IngredientException
     */
    public function get(string $id): Entite
    {
        $stmt = $this->pdo->prepare("select * from ".$this->tableName." where idi=?");
        $stmt->execute([$id]);
        $result = $stmt->fetchAll();
        if(count($result) == 0)
            throw new IngredientException("L'ingrédient $id n'existe pas",404);
        $data = $result[0];
        $ingredient = new Ingredient();
        $ingredient->setIdi($data["idi"]);
        $ingredient->setIdp($data["idp"]);
        $ingredient->setNom($data["nom"]);
        return $ingredient;
    }

    /**
     * @param Ingredient $entite
     * @return Ingredient
     */
    public function create(Entite $entite): Entite
    {
        $stmt = $this->pdo->prepare("insert into ".$this->tableName." (idp,nom) values (?,?)");
        $stmt->execute([$entite->getIdp(),$entite->getNom()]);
        $stmt = $this->pdo->prepare("select idi from ".$this->tableName." order by idi desc limit 1"); //On récupère le dernier id existant
        $stmt->execute();
        $id = $stmt->fetchColumn();
        $entite->setIdi($id);
        return $entite;
    }

    /**
     * @param Ingredient $entite
     * @return Ingredient
     * @throws IngredientException
     */
    public function update(Entite $entite): Entite
    {
        try {
            $stmt = $this->pdo->prepare("update ".$this->tableName." set idp=?, nom=? where idi=?");
            $stmt->execute([$entite->getIdp(), $entite->getNom(), $entite->getIdi()]);
        }catch(\PDOException $e)
        {
            file_put_contents(__DIR__."/../../log_error.txt",print_r(["message" => $e->getMessage(), "date" => date("Y-m-d H:i")],true),FILE_APPEND);
            throw new IngredientException("L'ingrédient ".$entite->getIdi()." n'a pas pu être mis à jour");
        }
        return $entite;
    }

    /**
     * @param array $conditions
     * @return Ingredient
     * @throws IngredientException
     */
    public function findOne(array $conditions): Entite
    {
        $params = ""; //paramètres à être concaténés dans la requête préparée
        $keys = array_keys($conditions);
        $map = [];

        for ($i = 0; $i < count($keys); $i++) {
            if($i == 0)
                $params .= $keys[$i]."=:".$keys[$i]; //on rédige la requête avec les paramètres à injecter
            else
                $params .= "AND ".$keys[$i]."=:".$keys[$i]." ";
            $map[":".$keys[$i]] = $conditions[$keys[$i]]; //on prépare le tableau pour paramétrer la requête préparée
        }


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by idi desc limit 1");
        $stmt->execute($map);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($data) == 0)
            throw new IngredientException("L'ingrédient recherché n'existe pas",404);
        $result = $data[0];
        $ingredient = new Ingredient();
        $ingredient->setIdi($result["idi"]);
        $ingredient->setIdp($result["idp"]);
        $ingredient->setNom($result["nom"]);

        return $ingredient;
    }

    public function find(array $conditions): array
    {
        $params = ""; //paramètres à être concaténés dans la requête préparée
        $keys = array_keys($conditions);
        $map = [];

        for ($i = 0; $i < count($keys); $i++) {
            if($i == 0)
                $params .= $keys[$i]."=:".$keys[$i]; //on rédige la requête avec les paramètres à injecter
            else
                $params .= "AND ".$keys[$i]."=:".$keys[$i]." ";
            $map[":".$keys[$i]] = $conditions[$keys[$i]]; //on prépare le tableau pour paramétrer la requête préparée
        }


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by idi asc ");
        $stmt->execute($map);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($data) == 0)
            throw new IngredientException("Aucun ingrédient correspondant",404);
        $entites = [];
        foreach ($data as $tblData)
        {
            $entite = new Ingredient();
            $entite->setIdi($tblData["idi"]);
            $entite->setIdp($tblData["idp"]);
            $entite->setNom($tblData["nom"]);
            array_unshift($entites,$entite); //pousser dans l'ordre croissant
        }

        return $entites;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if(count($data) == 0)
            throw new IngredientException("Aucun ingrédient disponible",404);
        $entites = [];
        foreach ($data as $tblData)
        {
            $entite = new Ingredient();
            $entite->setIdi($tblData["idi"]);
            $entite->setIdp($tblData["idp"]);
            $entite->setNom($tblData["nom"]);
            array_unshift($entites,$entite); //pousser dans l'ordre croissant
        }

        return $entites;
    }

    /**
     * @param Ingredient $entite
     * @throws IngredientException
     */
    public function delete(Entite $entite): void
    {
        try {
            $stmt = $this->pdo->prepare("delete from ".$this->tableName." where idi=?");
            $stmt->execute([$entite->getIdi()]);
        }catch (\PDOException $e)
        {
            file_put_contents(__DIR__."/../../log_error.txt",print_r(["message" => $e->getMessage(), "date" => date("Y-m-d H:i")],true),FILE_APPEND);
            throw new IngredientException("L'ingrédient ".$entite->getId()." n'a pas pu être supprimé");
        }
    }

    public function findOneLike(array $conditions): Entite
    {
        $params = ""; //paramètres à être concaténés dans la requête préparée
        $keys = array_keys($conditions);
        $map = [];

        for ($i = 0; $i < count($keys); $i++) {
            if($i == 0)
                $params .= $keys[$i] ." LIKE :".$keys[$i]; //on rédige la requête avec les paramètres à injecter
            else
                $params .= "AND ".$keys[$i] ." LIKE :".$keys[$i]." ";
            $map[":".$keys[$i]] = $conditions[$keys[$i]]; //on prépare le tableau pour paramétrer la requête préparée
        }


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by id_panier desc limit 1");
        $stmt->execute($map);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($result) == 0)
            throw new IngredientException("Aucun ingrédient correspondant",404);
        $finalResult = $result[0];
        $entite = new Ingredient();
        $entite->setIdi($finalResult["idi"]);
        $entite->setIdp($finalResult["idp"]);
        $entite->setNom($finalResult["nom"]);

        return $entite;
    }

    public function findLike(array $conditions): array
    {
        $params = ""; //paramètres à être concaténés dans la requête préparée
        $keys = array_keys($conditions);
        $map = [];

        for ($i = 0; $i < count($keys); $i++) {
            if($i == 0)
                $params .= $keys[$i]." LIKE :".$keys[$i]; //on rédige la requête avec les paramètres à injecter
            else
                $params .= "AND ".$keys[$i]." LIKE :".$keys[$i]." ";
            $map[":".$keys[$i]] = $conditions[$keys[$i]]; //on prépare le tableau pour paramétrer la requête préparée
        }


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by idi");
        $stmt->execute($map);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($result) == 0)
            throw new IngredientException("Aucun ingrédient correspondant",404);
        $entites = [];
        foreach ($result as $tblData)
        {
            $entite = new Ingredient();
            $entite->setIdi($tblData["idi"]);
            $entite->setIdp($tblData["idp"]);
            $entite->setNom($tblData["nom"]);
            array_unshift($entites,$entite); //pousser dans l'ordre croissant
        }
        return $entites;
    }
}