<?php
    namespace App;

    use MF\Bootstrap;
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
            $routes[] = new RouteSettings('home', '/', 'index', 'home');
            $routes[] = new RouteSettings('sobre_nos', '/sobre_nos', 'index', 'sobreNos');
            $this->routes = $routes;
        }
    }
?>