<?php


namespace App\Entite;


class Produit implements Entite
{
    use Serialize;
    /**
     * @var int $idp
     */
    private $idp;

    /**
     * @var string $nom
     */
    private $nom;

    /**
     * @var string $description
     */
    private $description;

    /**
     * @var string $prix
     * Type string utilisé pour éviter
     * problèmes d'affichage des décimales
     */
    private $prix;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param int $idp
     */
    public function setIdp(int $idp): void
    {
        $this->idp = $idp;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @param string $prix
     */
    public function setPrix(string $prix): void
    {
        $this->prix = $prix;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
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
     * @return string
     */
    public function getPrix(): string
    {
        return $this->prix;
    }

}