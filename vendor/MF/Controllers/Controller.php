<?php
    namespace MF\Controllers;

    class Controller
    {
        protected string $view;

        public function render(string $view, string $layout)
        {
            $this->view = $view;
            require_once('../App/Views/Layouts/' . $layout . '.phtml');
        }

        public function content()
        {
            $controller = str_replace('Controller', '', str_replace('App\\Controllers\\', '', get_class($this)));
            require_once('../App/Views//' . $controller . '//' . $this->view . '.phtml');
        }
    }
?>