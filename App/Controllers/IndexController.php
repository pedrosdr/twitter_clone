<?php
    namespace App\Controllers;

    use MF\Controllers\Controller;

    class IndexController extends Controller
    {
        public function home()
        {
            $this->render('home', 'layout1');
        }

        public function sobreNos()
        {
            $this->render('sobre_nos', 'layout1');
        }
    }
?>