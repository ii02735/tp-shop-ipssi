<?php


namespace App;

use mysql_xdevapi\Exception;

class Routeur
{
    /**
     * @var string $request
     * Capture de la requête entrante
     */
    private $request;

    /**
     * @var bool $is_xhr
     * Détermine s'il s'agit d'une requête XHR ou pas
     */
    private $is_xhr;

    public function __construct(string $request)
    {
        $this->request = $request;
        $this->is_xhr = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function get(array $parameters, $action)
    {
        if($_SERVER["REQUEST_METHOD"] == "GET" && Utilitaire::exists($_GET,["method"])
            && Utilitaire::exists($parameters,["method"])
            && $parameters["method"] == $_GET["method"]) //on vérifie la méthode de la requête
        {

                if(is_callable($action)) { //si on a fourni une fonction en second paramètre
                    if (Utilitaire::exists($parameters, ["xhr"])) //si on souhaite examiner au niveau du type de la requête (XMLHTTPRequest ou non)
                    {
                        if ($this->is_xhr == $parameters["xhr"])
                            $action();
                    } else {
                        $action();
                    }
                }elseif(is_string($action) && (!isset($parameters["xhr"]) || ($parameters["xhr"] == $this->is_xhr))){
                    $this->invoke($action);
                }else{
                    header("HTTP/1.1 500 Server Error");
                }

        }
    }

    //À invoquer lorsque aucun paramètre n'est passé
    public function rootGet($action)
    {
        if($_SERVER["REQUEST_METHOD"] =="GET") {
            if (!Utilitaire::exists($_GET, ["method"])) {
                if (is_callable($action)) { //si l'élément passé est une fonction, on l'exécute
                    $action();
                } elseif (is_string($action)) { //sinon on exécute la fonction du controller associé
                    $this->invoke($action);
                }
            }
        }
    }

    //À invoquer lorsque aucun paramètre n'est passé
    public function rootPost($action)
    {
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(is_callable($action)) {
                $action();
            }elseif(is_string($action)){
                $this->invoke($action);
            }
        }
    }

    public function post(array $parameters, $action) : void
    {
        if($_SERVER["REQUEST_METHOD"] == "POST" && Utilitaire::exists($_GET, ["method"])
            && Utilitaire::exists($parameters, ["method"])
            && $parameters["method"] == $_GET["method"]) {

                if (is_callable($action)) { //si on a fourni une fonction en second paramètre
                    if (Utilitaire::exists($parameters, ["xhr"])) //si on souhaite examiner au niveau du type de la requête (XMLHTTPRequest ou non)
                    {
                        if ($this->is_xhr == $parameters["xhr"])
                            $action();
                    } else {
                        $action();
                    }
                } elseif (is_string($action) && (!isset($parameters["xhr"]) || ($parameters["xhr"] == $this->is_xhr))) {
                    $this->invoke($action);
                } else {
                    header("HTTP/1.1 500 Server Error");
                }


        }
    }

    private function invoke(string $action) :void
    {
        $elements = explode("@",$action);
        $class = $elements[0];
        $method = $elements[1];


        if(class_exists($class) && is_callable([$class,$method])) {
            //les paramètres de la classe doivent-elles êtres chargées automatiquement ?
            $parameters = Utilitaire::loadDepedencies($class);
            /**
             * si des paramètres de la classe ont été mentionnées, et qu'elles sont
             * mentionnés dans le fichier d'injection, on doit les charger
             */
            if(count($parameters) > 0)
            {
                //Utilisation de la réflexion pour charger automatiquement la classe
                //avec les bons paramètres
                $reflection = new \ReflectionClass($class);
                $controller = $reflection->newInstanceArgs($parameters);
            }else { //sinon on construit l'instance normalement
                $controller = new $class();
            }
            $controller->$method();
        }else{
            throw new Exception("Erreur de configuration pour la route $action");
        }
    }


}
