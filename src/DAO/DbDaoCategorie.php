<?php


namespace App\DAO;


use App\Entite\Categorie;
use App\Entite\Entite;
use App\Exception\CategorieException;

class DbDaoCategorie extends DbDao
{
    public function __construct()
    {
        parent::__construct("CATEGORIE");
    }
    /**
     * @param string $id
     * @return Categorie
     * @throws CategorieException
     */
    public function get(string $id): Entite
    {
        $stmt = $this->pdo->prepare("select * from ".$this->tableName." where idc=?");
        $stmt->execute([$id]);
        $result = $stmt->fetchAll();
        if(count($result) == 0)
            throw new CategorieException("La catégorie $id n'existe pas");
        $data = $result[0];
        $categorie = new Categorie();
        $categorie->setIdc($data["idc"]);
        $categorie->setIdp($data["idp"]);
        $categorie->setLibelle($data["libelle"]);
        return $categorie;
    }

    /**
     * @param Categorie $entite
     * @return Categorie
     */
    public function create(Entite $entite): Entite
    {
        $stmt = $this->pdo->prepare("insert into ".$this->tableName." (idp,libelle) values (?,?)");
        $stmt->execute([$entite->getIdp(),$entite->getLibelle()]);
        $stmt = $this->pdo->prepare("select idc from ".$this->tableName." order by idc desc limit 1"); //On récupère le dernier id existant
        $stmt->execute();
        $id = $stmt->fetchColumn();
        $entite->setId($id);
        return $entite;
    }

    /**
     * @param Categorie $entite
     * @return Categorie
     * @throws CategorieException
     */
    public function update(Entite $entite): Entite
    {
        try {
            $stmt = $this->pdo->prepare("update ".$this->tableName." set idp=?, libelle=? where idc=?");
            $stmt->execute([$entite->getIdp(), $entite->getLibelle(), $entite->getIdc()]);
        }catch(\PDOException $e)
        {
            throw new CategorieException("La catégorie ".$entite->getIdc()." n'a pas pu être mis à jour");
        }
        return $entite;
    }

    /**
     * @param array $conditions
     * @return Categorie
     * @throws CategorieException
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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by idc desc limit 1");
        $stmt->execute($map);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($data) == 0)
            throw new CategorieException("La catégorie recherchée n'existe pas");
        $result = $data[0];
        $categorie = new Categorie();
        $categorie->setIdc($result["idc"]);
        $categorie->setIdp($result["idp"]);
        $categorie->setLibelle($result["libelle"]);

        return $categorie;
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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by idc asc ");
        $stmt->execute($map);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($data) == 0)
            throw new CategorieException("Aucune catégorie ne correspond aux paramètres fournis");
        $entites = [];
        foreach ($data as $tblData)
        {
            $entite = new Categorie();
            $entite->setIdc($tblData["idc"]);
            $entite->setIdp($tblData["idp"]);
            $entite->setLibelle($tblData["libelle"]);
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
            throw new CategorieException("Aucune catégorie existante");
        $entites = [];
        foreach ($data as $tblData)
        {
            $entite = new Categorie();
            $entite->setIdc($tblData["idc"]);
            $entite->setIdp($tblData["idp"]);
            $entite->setLibelle($tblData["libelle"]);
            array_unshift($entites,$entite); //pousser dans l'ordre croissant
        }

        return $entites;
    }

    /**
     * @param Categorie $entite
     * @throws CategorieException
     */
    public function delete(Entite $entite): void
    {
        try {
            $stmt = $this->pdo->prepare("delete from ".$this->tableName." where idc=?");
            $stmt->execute([$entite->getIdc()]);
        }catch (\PDOException $e)
        {
            throw new CategorieException("La catégorie ".$entite->getIdc()." n'a pas pu être supprimée");
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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by idc desc limit 1");
        $stmt->execute($map);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($result) == 0)
            throw new CategorieException("Aucune catégorie ne correspond aux paramètres fournis");
        $finalResult = $result[0];
        $entite = new Categorie();
        $entite->setIdc($finalResult["idc"]);
        $entite->setIdp($finalResult["idp"]);
        $entite->setLibelle($finalResult["libelle"]);

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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by idc");
        $stmt->execute($map);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($result) == 0)
            throw new CategorieException("Aucune catégorie ne correspond aux paramètres fournis");
        $entites = [];
        foreach ($result as $tblData)
        {
            $entite = new Categorie();
            $entite->setIdc($tblData["idc"]);
            $entite->setIdp($tblData["idp"]);
            $entite->setLibelle($tblData["libelle"]);
            array_unshift($entites,$entite); //pousser dans l'ordre croissant
        }

        return $entites;
    }
}