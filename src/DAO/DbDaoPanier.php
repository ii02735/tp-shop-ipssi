<?php


namespace App\DAO;


use App\Entite\Entite;
use App\Entite\Panier;
use App\Exception\PanierException;

class DbDaoPanier extends DbDao
{

    public function __construct()
    {
        parent::__construct("PANIER");
    }
     /**
     * @param string $id
     * @return Panier
     * @throws PanierException
     */
    public function get(string $id): Entite
    {
        $stmt = $this->pdo->prepare("select * from ".$this->tableName." where id_panier=?");
        $stmt->execute([$id]);
        $result = $stmt->fetchAll();
        if(count($result) == 0)
            throw new PanierException("Le panier $id n'existe pas");
        $data = $result[0];
        $panier = new Panier();
        $panier->setIdPanier($data["id_panier"]);
        $panier->setIdp($data["idp"]);
        $panier->setQuantite($data["quantite"]);
        $panier->setIdClient($data["id_client"]);

        return $panier;
    }

    /**
     * @param Panier $entite
     * @return Panier
     */
    public function create(Entite $entite): Entite
    {
        $stmt = $this->pdo->prepare("insert into ".$this->tableName." (idp,quantite,id_client) values (?,?,?)");
        $stmt->execute([$entite->getIdp(),$entite->getQuantite(),$entite->getIdClient()]);
        $stmt = $this->pdo->prepare("select id_panier from ".$this->tableName." order by id_panier desc limit 1"); //On récupère le dernier id existant
        $stmt->execute();
        $id = $stmt->fetchColumn();
        $entite->setIdPanier($id);
        return $entite;
    }

    /**
     * @param Panier $entite
     * @return Panier
     * @throws PanierException
     */
    public function update(Entite $entite): Entite
    {
        try {
            $stmt = $this->pdo->prepare("update ".$this->tableName." set idp=?, quantite=? where id_panier=?");
            $stmt->execute([$entite->getIdp(), $entite->getQuantite(), $entite->getIdPanier()]);
        }catch (\PDOException $e)
        {
            throw new PanierException("Le panier ".$entite->getIdPanier()." n'a pas pu être mis à jour");
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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by id_panier desc limit 1");
        $stmt->execute($map);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($data) == 0)
            throw new PanierException("Le panier recherché n'existe pas");
        $result = $data[0];
        $panier = new Panier();
        $panier->setIdPanier($result["id_panier"]);
        $panier->setQuantite($result["quantite"]);
        $panier->setIdp($result["idp"]);
        $panier->setIdClient($result["id_client"]);
        return $panier;
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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by id_panier asc ");
        $stmt->execute($map);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($data) == 0)
            throw new PanierException("Aucun panier ne correspond aux paramètres fournis");
        $entites = [];
        foreach ($data as $tblData)
        {
            $entite = new Panier();
            $entite->setIdPanier($tblData["id_panier"]);
            $entite->setIdp($tblData["idp"]);
            $entite->setIdClient($tblData["id_client"]);
            $entite->setQuantite($tblData["quantite"]);
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
            throw new PanierException("Aucun panier disponible");
        $entites = [];
        foreach ($data as $tblData)
        {
            $entite = new Panier();
            $entite->setIdPanier($tblData["id_panier"]);
            $entite->setIdp($tblData["idp"]);
            $entite->setIdClient($tblData["id_client"]);
            $entite->setQuantite($tblData["quantite"]);
            array_unshift($entites,$entite); //pousser dans l'ordre croissant
        }

        return $entites;
    }

    /**
     * @param Panier $entite
     * @throws PanierException
     */
    public function delete(Entite $entite): void
    {
        try{
        $stmt = $this->pdo->prepare("delete from ".$this->tableName." where id_panier=?");
        $stmt->execute([$entite->getIdPanier()]);
        }catch (\PDOException $e)
        {
            throw new PanierException("Le panier ".$entite->getIdPanier()." n'a pas pu être supprimé");
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
            throw new PanierException("Aucun panier ne correspond aux paramètres fournis");
        $finalResult = $result[0];
        $entite = new Panier();
        $entite->setIdPanier($finalResult["id_panier"]);
        $entite->setIdp($finalResult["idp"]);
        $entite->setIdClient($finalResult["id_client"]);
        $entite->setQuantite($finalResult["quantite"]);

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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by id_panier");
        $stmt->execute($map);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($result) == 0)
            throw new PanierException("Aucun panier ne correspond aux paramètres fournis");
        $entites = [];
        foreach ($result as $tblData)
        {
            $entite = new Panier();
            $entite->setIdPanier($tblData["id_panier"]);
            $entite->setIdp($tblData["idp"]);
            $entite->setIdClient($tblData["id_client"]);
            $entite->setQuantite($tblData["quantite"]);
            array_unshift($entites,$entite); //pousser dans l'ordre croissant
        }

        return $entites;
    }
}