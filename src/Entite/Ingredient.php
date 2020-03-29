<?php


namespace App\Entite;


class Ingredient implements Entite
{
    use Serialize;
    /**
     * @var int $idi
     */
    private $idi;

    /**
     * @var int $idp
     */
    private $idp;

    /**
     * @var string $nom
     */
    private $nom;

    /**
     * @param int $idp
     */
    public function setIdp(int $idp): void
    {
        $this->idp = $idp;
    }

    /**
     * @param int $idi
     */
    public function setIdi(int $idi): void
    {
        $this->idi = $idi;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return int
     */
    public function getIdp(): int
    {
        return $this->idp;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @return int
     */
    public function getIdi(): int
    {
        return $this->idi;
    }
}