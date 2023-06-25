<?php
    namespace App;

    use PDOException;

    class Connection 
    {
        public static function getDb()
        {
            try
            {
                return new \PDO('mysql:host=localhost;dbname=twitter_clone;charset=utf8', 'root', '');
            }
            catch(PDOException $ignored) {};
        }
    }
?>