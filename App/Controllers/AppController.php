<?php
    namespace App\Controllers;

    use App\Connection;
    use App\Models\User;
    use MF\Controllers\Controller;
    use MF\Security\Hasher;

    class AppController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function timeline()
        {
            session_start();
            echo '<pre>';
            print_r($_SESSION);
            echo '</pre>';

            if(!isset($_SESSION['id']) || !isset($_SESSION['email']) || !isset($_SESSION['name']))
                header('Location:/?login=error');
        }
    }
?>