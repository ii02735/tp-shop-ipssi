<?php


namespace App\Entite;


class Categorie implements Entite
{
    use Serialize;
    /**
     * @var int $idc
     */
    private $idc;

    /**
     * Clé étrangère reliée à PRODUIT
     * @var int $idp
     */
    private $idp;

    /**
     * @var string $libelle
     */
    private $libelle;

    /**
     * @param int $idp
     */
    public function setIdp(int $idp): void
    {
        $this->idp = $idp;
    }

    /**
     * @param int $idc
     */
    public function setIdc(int $idc): void
    {
        $this->idc = $idc;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }

    /**
     * @return int
     */
    public function getIdp(): int
    {
        return $this->idp;
    }

    /**
     * @return int
     */
    public function getIdc(): int
    {
        return $this->idc;
    }

    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }
}