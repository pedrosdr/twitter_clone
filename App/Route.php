<?php
    namespace App;

    use MF\Controllers\Bootstrap;
    use MF\Controllers\RouteSettings;

    class Route extends Bootstrap
    {
        public function __construct()
        {
            parent::__construct();
        }
        
        public function initRoutes()
        {
            $routes = array();
            $routes[] = new RouteSettings('home', '/', 'index', 'index');
            $routes[] = new RouteSettings('sobre_nos', '/sobre_nos', 'index', 'sobreNos');
            $this->routes = $routes;
        }
    }
?>