<?php
    namespace MF\Controllers;

    use stdClass;

    class Controller
    {
        protected string $view;
        protected stdClass $data;

        public function __construct()
        {
            $this->data = new stdClass();
        }

        public function render(string $view, string $layout)
        {
            $this->view = $view;
            require_once('../App/Views/Layouts/' . $layout . '.phtml');
        }

        public function content()
        {
            $controller = str_replace('Controller', '', str_replace('App\\Controllers\\', '', get_class($this)));
            require_once('../App/Views/' . $controller . '/' . $this->view . '.phtml');
        }
    }
?>