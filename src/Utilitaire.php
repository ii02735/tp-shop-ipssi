<?php


namespace App;


class Utilitaire
{

    /**
     * Raccourci pour vérifier existance index / clé
     * @param array $array tableau
     * @param string $key index/clés
     * @param bool $allowEmpty Autoriser des chaînes vides ?
     * @return bool
     */
    public static function exists(array $array, array $keys, bool $allowEmpty = false) : bool
    {
        $result = true;
        foreach($keys as $key)
        {
            if(!isset($array[$key]) && empty($array[$key])){
                    $result = false;
                break; //dès qu'un paramètre est inexistant, on arrête la boucle pour retourner false
            }elseif($array[$key] == "" && !$allowEmpty) //si on interdit les chaînes vides
            {
                $result = false;
                break;
            }
        }
        return $result;
    }

    /**
     * Effectue le rendu d'une vue
     * @param string $view
     * @param $variables
     */
    public static function render(string $view, array $variables = [])
    {
        extract($variables); //on déclare des variables depuis les étiquettes du tableau pour ensuite les envoyer dans le fichier à invoquer
        require __DIR__."/views/$view";
    }

}