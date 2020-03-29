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

    /**
     * Charge les dépendences depuis un fichier XML
     * @param string $className
     */
    public static function loadDepedencies(string $className) : array
    {
        //Utilisation de la classe DOMDocument (meilleur traitement d'un fichier XML = suppression des espaces, formatage, etc.)
        $dom = new \DOMDocument("1.0","UTF-8");
        $dom->preserveWhiteSpace = false; //suppression des espaces
        $dom->load(__DIR__ . "/../config/dependences.xml"); //chargement du fichier d'injection
        $dom->formatOutput = true;
        $xpath = new \DOMXPath($dom); //utilisation de XPATH pour naviguer dans le fichier XML
        //on recherche dans le fichier la classe qu'on désire charger automatiquement
        $usercontroller = $xpath->query("/root/controllers/controller[@class='$className']");
        $parameters = []; //tableau utilisé pour stocker les attributs à charger dans le constructeur de la classe
        //suppression des commentaires, car ces derniers sont considérés comme étant des nœuds
        foreach ($xpath->query('//comment()') as $comment) {
            $comment->parentNode->removeChild($comment);
        }
        //si le nom du controlleur a été trouvé dans l'arborescence dictée dans le XPATH
        if($usercontroller->count() > 0){
            //on peut récupérer le nom de chaque attribut de ce dernier
            foreach($usercontroller->item(0)->childNodes as $node)
            {
                //on stocke pour chaque instance, les différentes classes qu'elles peuvent implémenter
                foreach($node->childNodes as $child)
                    $parameters[$node->getAttribute("name")][$child->tagName] = $child->textContent;
            }
            $classes = [];
            //on passe maintenant à l'initialisation des instances
            foreach(array_keys($parameters) as $instanceName)
            {
                //on regarde au niveau du load, les différentes références indiquées pour les instances à charger
                //référence = classe d'instance à charger (exemple : dbdao = classe gérant le DAO par BDD...)
                $dbModeQuery = $xpath->query("/root/load/controller[@class='$className']/@$instanceName");
                $dbMode = $dbModeQuery[0]->value;
                if(is_null($dbMode))
                    throw new \Exception("Paramètre $instanceName pas défini dans le chargement de l'instance (cf <controller class='$className'>'");
                //initialisation de l'instance pour chaque paramètre fourni
                $classes[$instanceName] = new $parameters[$instanceName][$dbMode]();
            }
            return $classes;
        }else{
            return [];
        }



    }

    /**
     * Fonction de contrôle sur l'adresse IP cliente à celle du serveur
     */
    public static function getIPs()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip=$ip.'_'.$_SERVER['HTTP_X_FORWARDED_FOR']; }
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip=$ip.'_'.$_SERVER['HTTP_CLIENT_IP']; }
        return $ip;
    }

}