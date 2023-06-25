<?php
    namespace App\Controllers;

    use MF\Controllers\Controller;

    class IndexController extends Controller
    {
        public function home()
        {
            $this->render('index', 'layout');
        }

        public function subscribe()
        {
            $this->render('inscreverse', 'layout');
        }
    }
?>