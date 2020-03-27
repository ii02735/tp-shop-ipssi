<?php


namespace App\Entite;


trait JsonSerialize
{
    /**
     * @return string
     * Utlilitaire pour sérialiser en JSON une classe
     */
    public function toJson():string
    {
        return json_encode(get_object_vars($this));
    }
}