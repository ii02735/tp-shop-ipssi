<?php


namespace App\Entite;


class Panier implements Entite
{
    use Serialize;
    /**
     * @var int $id_panier
     */
    private $id_panier;

    /**
     * @var int $idp
     * Clé étrangère reliée au panier
     */
    private $idp;

    /**
     * @var int $quantite
     */
    private $quantite;

    /**
     * @var int $id_client
     * Clé étrangère à relier avec le client
     */
    private $id_client;

    /**
     * @param int $idp
     */
    public function setIdp(int $idp): void
    {
        $this->idp = $idp;
    }

    /**
     * @param int $id_panier
     */
    public function setIdPanier(int $id_panier): void
    {
        $this->id_panier = $id_panier;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    /**
     * @param int $id_client
     */
    public function setIdClient(int $id_client): void
    {
        $this->id_client = $id_client;
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
    public function getIdPanier(): int
    {
        return $this->id_panier;
    }

    /**
     * @return int
     */
    public function getQuantite(): int
    {
        return $this->quantite;
    }

    /**
     * @return int
     */
    public function getIdClient(): int
    {
        return $this->id_client;
    }
}

