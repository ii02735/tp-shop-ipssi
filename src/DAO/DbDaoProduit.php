<?php


namespace App\DAO;
use App\Entite\Entite;
use App\Entite\Produit;
use App\Exception\ProduitException;

class DbDaoProduit extends DbDao
{

    public function __construct()
    {
        parent::__construct("PRODUITS");
    }
     /**
     * @param string $id
     * @return Produit
     * @throws ProduitException
     */
    public function get(string $id): Entite
    {
        $stmt = $this->pdo->prepare("select * from ".$this->tableName." where idp=?");
        $stmt->execute([$id]);
        $result = $stmt->fetchAll();
        if(count($result) == 0)
            throw new ProduitException("Le produit $id n'existe pas");
        $data = $result[0];
        $produit = new Produit();
        $produit->setIdp($data["idp"]);
        $produit->setNom($data["nom"]);
        $produit->setPrix($data["prix"]);
        $produit->setDescription($data["description"]);

        return $produit;
    }

    /**
     * @param Produit $entite
     * @return Produit
     */
    public function create(Entite $entite): Entite
    {
        $stmt = $this->pdo->prepare("insert into ".$this->tableName." (nom,description,prix) values (?,?,?)");
        $stmt->execute([$entite->getNom(),$entite->getDescription(),$entite->getPrix()]);
        $stmt = $this->pdo->prepare("select idp from ".$this->tableName." order by idp desc limit 1"); //On récupère le dernier id existant
        $stmt->execute();
        $id = $stmt->fetchColumn();
        $entite->setId($id);
        return $entite;
    }

    /**
     * @param Produit $entite
     * @return Produit
     * @throws ProduitException
     */
    public function update(Entite $entite): Entite
    {
        try {
            $stmt = $this->pdo->prepare("update ".$this->tableName." set nom=?, description=?, prix=? where idp=?");
            $stmt->execute([$entite->getNom(), $entite->getDescription(), $entite->getPrix(), $entite->getIdp()]);
        }catch(\PDOException $e)
        {
            throw new ProduitException("Le produit ".$entite->getIdp()." n'a pas pu être mis à jour");
        }
        return $entite;
    }

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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by id desc limit 1");
        $stmt->execute($map);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($data) == 0)
            throw new ProduitException("Le produit recherché n'existe pas");
        $result = $data[0];
        $produit = new Produit();
        $produit->setIdp($result["id"]);
        $produit->setNom($result["nom"]);
        $produit->setDescription($result["description"]);
        $produit->setPrix($result["prix"]);

        return $produit;
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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by id asc ");
        $stmt->execute($map);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($data) == 0)
            throw new ProduitException("Aucun produit ne correspond aux paramètres fournis");
        $entites = [];
        foreach ($data as $tblData)
        {
            $entite = new Produit();
            $entite->setIdp($tblData["idp"]);
            $entite->setNom($tblData["nom"]);
            $entite->setDescription($tblData["description"]);
            $entite->setPrix($tblData["prix"]);
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
            throw new ProduitException("Aucun produit disponible");
        $entites = [];
        foreach ($data as $tblData)
        {
            $entite = new Produit();
            $entite->setIdp($tblData["idp"]);
            $entite->setNom($tblData["nom"]);
            $entite->setDescription($tblData["description"]);
            $entite->setPrix($tblData["prix"]);
            array_unshift($entites,$entite); //pousser dans l'ordre croissant
        }

        return $entites;
    }

    /**
     * @param Produit $entite
     * @throws ProduitException
     */
    public function delete(Entite $entite): void
    {
        try {
            $stmt = $this->pdo->prepare("delete from ".$this->tableName." where id=?");
            $stmt->execute([$entite->getId()]);
        }catch(\PDOException $e)
        {
            throw new ProduitException("Le produit ".$entite->getIdp()." n'a pas pu être supprimé");
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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by idp desc limit 1");
        $stmt->execute($map);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($result) == 0)
            throw new ProduitException("Aucun produit ne correspond aux paramètres fournis");
        $finalResult = $result[0];
        $entite = new Produit();
        $entite->setIdp($finalResult["idp"]);
        $entite->setNom($finalResult["nom"]);
        $entite->setDescription($finalResult["description"]);
        $entite->setPrix($finalResult["prix"]);

        return $entite;
    }

    public function findLike(array $conditions): array
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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by idp");
        $stmt->execute($map);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($result) == 0)
            throw new ProduitException("Aucun produit ne correspond aux paramètres fournis");
        $entites = [];
        foreach ($result as $tblData)
        {
            $entite = new Produit();
            $entite->setIdp($tblData["idp"]);
            $entite->setNom($tblData["nom"]);
            $entite->setDescription($tblData["description"]);
            $entite->setPrix($tblData["prix"]);
            array_unshift($entites,$entite); //pousser dans l'ordre croissant
        }

        return $entites;
    }
}