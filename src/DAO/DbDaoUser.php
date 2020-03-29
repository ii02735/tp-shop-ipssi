<?php


namespace App\DAO;
use App\Entite\Entite;
use App\Entite\Utilisateur;
use App\Exception\UtilisateurException;

/**
 * Communication avec la BDD pour récupérer une instance d'utilisateur
 * Class DbDaoUser
 * @package App\DAO
 */
class DbDaoUser extends DbDao
{

    public function __construct()
    {
        parent::__construct("user");
    }

    /**
     * Récupérer un utilisateur depuis son id
     * @param string $id
     * @return Utilisateur
     */
    public function get(string $id): Entite
    {
        $stmt = $this->pdo->prepare("select * from ".$this->tableName." where id=?");
        $stmt->execute([$id]);
        $result = $stmt->fetchAll();
        if(count($result) == 0)
            throw new UtilisateurException("L'utilisateur $id n'existe pas");
        $data = $result[0];
        $user = new Utilisateur();
        $user->setId($data["id"]);
        $user->setName($data["name"]);
        $user->setFirstname($data["firstname"]);
        $user->setEmail($data["email"]);
        $user->setRole($data["role"]);
        $user->setPassword($data["password"]);


        return $user;
    }

    /**
     * Trouver un utilisateur
     * @param array $conditions
     * @return Utilisateur
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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by id desc limit 1");
        $stmt->execute($map);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($result) == 0)
            throw new UtilisateurException("L'utilisateur n'existe pas",404);
        $finalResult = $result[0];
        $user = new Utilisateur();
        $user->setId($finalResult["id"]);
        $user->setName($finalResult["name"]);
        $user->setRole($finalResult["role"]);
        $user->setFirstname($finalResult["firstname"]);
        $user->setEmail($finalResult["email"]);
        $user->setPassword($finalResult["password"]);

        return $user;


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
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($result) == 0)
            throw new UtilisateurException("Aucun utilisateur correspondant");
        $data = $result;
        $entites = [];
        foreach ($data as $tblData)
        {
            $entite = new Utilisateur();
            $entite->setId($tblData["id"]);
            $entite->setName($tblData["name"]);
            $entite->setFirstname($tblData["firstname"]);
            $entite->setEmail($tblData["email"]);
            $entite->setRole($tblData["role"]);
            $entite->setPassword($tblData["password"]);
            array_unshift($entites,$entite); //pousser dans l'ordre croissant
        }
        return $entites;


    }

    /**
     * Effacer un utilisateur
     * @param Utilisateur $user
     */
    public function delete(Entite $user): void
    {
        try {
            $stmt = $this->pdo->prepare("delete from ".$this->tableName." where id=?");
            $stmt->execute([$user->getId()]);
        }catch(\PDOException $e)
        {
            file_put_contents(__DIR__."/../../log_error.txt",print_r(["message" => $e->getMessage(), "date" => date("Y-m-d H:i")],true),FILE_APPEND);
            throw new UtilisateurException("L'entité ".$user->getId()." n'existe pas");
        }
    }

    /**
     * Créer un utilisateur
     * @param Utilisateur $user
     * @return Utilisateur
     */
    public function create(Entite $entite): Entite
    {
        try {
            $stmt = $this->pdo->prepare("insert into " . $this->tableName . " (name,firstname,email,role,password) values (?,?,?,?,?)");
            //cryptage du mot de passe avec BCRYPT (moins robuste que ARGON mais moins lourd)
            $res = $stmt->execute([$entite->getName(), $entite->getFirstname(), $entite->getEmail(), $entite->getRole(),password_hash($entite->getPassword(), PASSWORD_BCRYPT)]);
            $stmt = $this->pdo->prepare("select id from " . $this->tableName . " order by id desc limit 1"); //On récupère le dernier id existant
            $stmt->execute();
            $id = $stmt->fetchColumn();
            $entite->setId($id);
        }catch(\PDOException $e)
        {
            /**
             * Inscription de l'erreur de la création dans un fichier de log (ajout de true à print_r)
             */
            file_put_contents(__DIR__."/../../log_error.txt",print_r(["message" => $e->getMessage(), "code"=>$e->getCode(), "date" => date("Y-m-d H:i")],true),FILE_APPEND);
            throw new UtilisateurException($e->getMessage(),$e->getCode());
        }
            return $entite;
    }



    public function findAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if(count($data) == 0)
            throw new UtilisateurException("Aucun utilisateur disponible",404);
        $entites = [];
        foreach ($data as $tblData)
        {
            $entite = new Utilisateur();
            $entite->setId($tblData["id"]);
            $entite->setName($tblData["name"]);
            $entite->setFirstname($tblData["firstname"]);
            $entite->setEmail($tblData["email"]);
            $entite->setRole($tblData["role"]);
            $entite->setPassword($tblData["password"]);
            array_unshift($entites,$entite); //pousser dans l'ordre croissant
        }

        return $entites;
    }

    /**
     * Mettre à jour un utilisateur
     * @param Utilisateur $entite
     * @return Utilisateur
     */
    public function update(Entite $entite): Entite
    {
        try {
            $stmt = $this->pdo->prepare("update ".$this->tableName." set name=?, firstname=?, email=?, role=?, password=? where id=?");
            $stmt->execute([$entite->getName(), $entite->getFirstname(),$entite->getEmail(),$entite->getRole(),$entite->getPassword(), $entite->getId()]);
        }catch(\PDOException $e)
        {
            file_put_contents(__DIR__."/../../log_error.txt",print_r(["message" => $e->getMessage(), "date" => date("Y-m-d H:i")],true),FILE_APPEND);
            throw new UtilisateurException("L'utilisateur ".$entite->getId()." n'a pas pu être mis à jour");
        }
        return $entite;
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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by id desc limit 1");
        $stmt->execute($map);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($result) == 0)
            throw new UtilisateurException("Aucun utilisateur correspondant",404);
        $finalResult = $result[0];
        $entite = new Utilisateur();
        $entite->setId($finalResult["id"]);
        $entite->setName($finalResult["name"]);
        $entite->setFirstname($finalResult["firstname"]);
        $entite->setEmail($finalResult["email"]);
        $entite->setRole($finalResult["role"]);
        $entite->setPassword($finalResult["password"]);

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


        $stmt = $this->pdo->prepare("SELECT * from ".$this->tableName." where $params order by id");
        $stmt->execute($map);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC); //on retourne un tableau associatif pour plus de facilité
        if(count($result) == 0)
            throw new UtilisateurException("Aucun utilisateur correspondant",404);
        $entites = [];
        foreach ($result as $tblData)
        {
            $entite = new Utilisateur();
            $entite->setId($tblData["id"]);
            $entite->setName($tblData["name"]);
            $entite->setFirstname($tblData["firstname"]);
            $entite->setEmail($tblData["email"]);
            $entite->setRole($tblData["role"]);
            $entite->setPassword($tblData["password"]);
            array_unshift($entites,$entite); //pousser dans l'ordre croissant
        }

        return $entites;
    }
}