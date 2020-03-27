<?php


namespace App\Exception;

use Throwable;

class UtilisateurException extends \Exception
{
    public function __construct($message = "Un problème a été rencontré dans la récupération de l'utilisateur", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}