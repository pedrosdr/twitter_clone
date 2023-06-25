<?php
    namespace App;

    use MF\Init\Bootstrap;
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
            $routes[] = new RouteSettings('inscreverse', '/inscreverse', 'index', 'subscribe');
            $routes[] = new RouteSettings('registrar', '/registrar', 'index', 'signUp');
            $routes[] = new RouteSettings('autenticar', '/autenticar', 'auth', 'authenticate');
            $routes[] = new RouteSettings('timeline', '/timeline', 'app', 'timeline');

            $this->routes = $routes;
        }
    }
?>