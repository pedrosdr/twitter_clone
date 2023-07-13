<?php
    namespace App;

    class Auth
    {
        public static function checkAuth()
        {
            if(!isset($_SESSION['id']) || !isset($_SESSION['email']) || !isset($_SESSION['name']))
                header('Location:/?login=error');
        }
    }
?>