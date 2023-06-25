<?php
    namespace App\Controllers;

    use App\Connection;
    use App\Models\User;
    use MF\Controllers\Controller;

    class IndexController extends Controller
    {
        public function home()
        {
            $this->data = User::getInstances(Connection::getDb());
            $this->render('index', 'layout');
        }

        public function subscribe()
        {
            $this->render('inscreverse', 'layout');
        }
    }
?>