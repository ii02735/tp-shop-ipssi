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
    }


}