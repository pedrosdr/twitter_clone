<?php
    namespace App\Controllers;

    use App\Connection;
    use App\Models\User;
    use MF\Controllers\Controller;

    class IndexController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->data->user = new User(Connection::getDb(), '', '');
        }

        public function home()
        {
            $this->data->login = isset($_GET['login']) ? $_GET['login'] : '';
            $this->data->erroCadastro = false;
            $this->data->users = User::getInstances(Connection::getDb());
            $this->render('index', 'layout');
        }

        public function subscribe()
        {
            $this->data->erroCadastro = false;
            $this->render('inscreverse', 'layout');
        }

        public function signUp()
        {
            $this->data->erroCadastro = false;
            if(!$this->validateSignUp())
            {
                $user = new User(Connection::getDb(), $_POST['nome'], $_POST['email']);
                $this->data->user = $user;
                $this->render('inscreverse', 'layout');
                return;
            }

            $user = new User(Connection::getDb(), $_POST['nome'], $_POST['email']);
            $user->save($_POST['senha']);

            $this->data->user = $user;

            $this->render('cadastro', 'layout');
        }

        public function validateSignUp()
        {
            if(!(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha'])))
            {
                $this->data->erroCadastro = true;
                return false;
            }
                

            if(strlen($_POST['nome']) < 3 || strlen($_POST['email']) < 7 || strlen($_POST['senha']) < 7)
            {
                $this->data->erroCadastro = true;
                return false;
            }

            if(count(User::getUsersByEmail($_POST['email'], Connection::getDb())) != 0)
            {
                $this->data->erroCadastro = true;
                return false;
            }

            return true;
        }
    }
?>