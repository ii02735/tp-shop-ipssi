<?php


namespace App\DAO;
use App\Entite\Entite;
use App\User;

interface IDao
{
    public function get(string $id): Entite;
    public function create(Entite $entite): Entite;
    public function update(Entite $entite): Entite;
    public function findAll():array;

    /**
     * @param array $conditions
     * @return Entite
     * Récupérer qu'une seule entité
     */
    public function findOne(array $conditions): Entite;

    public function findOneLike(array $conditions): Entite;

    public function findLike(array $conditions) : array;
    /**
     * @param array $conditions
     * @return array
     * En récupérer plusieurs
     */
    public function find(array $conditions): array;

    public function delete(Entite $entite): void;
}