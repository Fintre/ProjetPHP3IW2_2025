<?php

namespace App\Core;

class Router {
    
    private array $routes = [];
    private string $uri;
    
    public function __construct(string $uri) {
        $this->uri = $uri;
    }
    
    public function loadRoutes(string $routeFile): void {
        if(!file_exists($routeFile)){
            die("Le fichier de routing n'existe pas");
        }
        $this->routes = yaml_parse_file($routeFile);
    }

    public function resolve(): array {
        if(isset($this->routes[$this->uri])){
            return $this->getStaticRoute();
        }
        
        return $this->getDynamicRoute();
    }
    
    private function getStaticRoute(): array {
        $route = $this->routes[$this->uri];
        
        if(empty($route["controller"]) || empty($route["action"])){
            die("Erreur, il n'y a aucun controller ou aucune action pour cette URI");
        }
        
        return [
            'controller' => $route["controller"],
            'action' => $route["action"],
            'params' => []
        ];
    }
    

    private function getDynamicRoute(): array {
        $slug = ltrim($this->uri, '/');
        
        if(empty($slug)){
            $this->throw404();
        }
        
        return [
            'controller' => 'PageController',
            'action' => 'show',
            'params' => ['slug' => $slug]
        ];
    }
    
    public function dispatch(): void {
        $route = $this->resolve();
        $this->executeRoute($route['controller'], $route['action'], $route['params']);
    }
    
    private function executeRoute(string $controller, string $action, array $params): void {
        if(!file_exists("Controllers/".$controller.".php")){
            die("Erreur, le fichier du controller n'existe pas");
        }
        
        include "Controllers/".$controller.".php";
        
        $controllerClass = "App\\Controller\\".$controller;
        if(!class_exists($controllerClass)){
            die("Erreur, la class controller ".$controllerClass." n'existe pas");
        }
        
        $objController = new $controllerClass();
        
        if(!method_exists($objController, $action)){
            die("Erreur, l'action ".$action." n'existe pas");
        }
        
        if(!empty($params)){
            call_user_func_array([$objController, $action], $params);
        } else {
            $objController->$action();
        }
    }
}