<?php


namespace App\DAO;

use App\Entite\Entite;

/**
 * Class DbDao
 * @package App\DAO
 * Classe abstraite uniquement
 * pour alléger accès BDD
 */
abstract class DbDao implements IDao
{
    /**
     * @var \PDO $pdo
     */
    protected $pdo;

    protected $tableName;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
        $this->pdo = require __DIR__."/../../config/PDO.php";
        /**
         * Activation des exceptions de PDO
         */
        $this->pdo->setAttribute( $this->pdo::ATTR_ERRMODE, $this->pdo::ERRMODE_EXCEPTION );
        $this->pdo->setAttribute( $this->pdo::ATTR_EMULATE_PREPARES, FALSE );
    }

    //Destruction de la connexion PDO à la BDD
    public function __destruct()
    {
        $this->pdo = null;
    }

}