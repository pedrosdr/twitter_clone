<?php
    namespace MF\Init;

    abstract class Bootstrap 
    {
        // fields
        protected array $routes;

        // constructor
        public function __construct()
        {
            $this->initRoutes();
            $this->run();
        }

        // methods
        public abstract function initRoutes();

        public function getUrl()
        {
            return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }

        public function run()
        {
            foreach($this->routes as $route)
            {
                if($this->getUrl() == $route->getRoute())
                {
                    $controllerName = '\\App\\Controllers\\' . ucfirst($route->getController()) . 'Controller';
                    $controller = new $controllerName();

                    $action = $route->getAction();
                    $controller->$action();

                    break;
                }
            }
        }
    }
?>