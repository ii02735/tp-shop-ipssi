<?php


namespace App\Entite;


trait Serialize
{
    /**
     * @return string
     * Utlilitaire pour sérialiser en JSON une classe
     */
    public function toJson():string
    {
        return json_encode(get_object_vars($this));
    }

    public function toArray(): array{
        return json_decode(json_encode(get_object_vars($this)),true);
    }
}