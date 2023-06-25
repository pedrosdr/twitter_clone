<?php
    namespace App\Controllers;

    use App\Connection;
    use App\Models\User;
    use MF\Controllers\Controller;
    use MF\Security\Hasher;

    class AuthController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function authenticate()
        {
            $users = User::getUsersByEmail($_POST['email'], Connection::getDb());
            if (count($users) == 0)
            {
                header('Location:/?login=email_not_registered');
            }

            $user = $users[0];
            $hasher = new Hasher($_POST['senha'], $user->getSalt());

            if($user->getPassword() === $hasher->getPasswordHash())
            {
                session_start();
                $_SESSION['id'] = $user->getId();
                $_SESSION['name'] = $user->getName();
                $_SESSION['email'] = $user->getEmail();

                header('Location:/timeline');
            }
            else
            {
                header('Location:/?login=wrong_password');
            }
        }
    }
?>