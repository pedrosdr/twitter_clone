<?php
    namespace MF\Controllers;

    class RouteSettings 
    {
        private string $id;
        private string $route;
        private string $controller;
        private string $action;

        public function __construct(string $id, string $route, string $controller, string $action)
        {
            $this->id = $id;
            $this->route = $route;
            $this->controller = $controller;
            $this->action = $action;
        }

        public function getId() {return $this->id;}
        public function getRoute() {return $this->route;}
        public function getController() {return $this->controller;}
        public function getAction() {return $this->action;}
    }
?>