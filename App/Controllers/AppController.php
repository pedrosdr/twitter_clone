<?php
    namespace App\Controllers;

    use App\Auth;
    use App\Connection;
    use App\Models\Tweet;
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

            Auth::checkAuth();

            $this->render('timeline', 'layout');
        }

        public function tweet()
        {
            session_start();
            Auth::checkAuth();
            
            $user = User::getUsersByEmail($_SESSION['email'], Connection::getDb())[0];
            $tweet = new Tweet(Connection::getDb(), $user->getId(), $_POST['tweet']);
            $tweet->save();

            header('Location: /timeline');
        }

        public function action()
        {
            session_start();
            Auth::checkAuth();

            $action = isset($_GET['action']) ? $_GET['action'] : '';
            $followedId = isset($_GET['id']) ? $_GET['id'] : '';

            $currentUser = new User(Connection::getDb());
            $currentUser->setId($_SESSION['id']);

            if($action == 'follow')
                $currentUser->addFollowed($followedId);
            else
                $currentUser->removeFollowed($followedId);

            header('Location: /quem_seguir');
        }

        public function followUsers()
        {
            session_start();
            Auth::checkAuth();

            $this->data->searchResult = null;

            if(isset($_GET['search']))
                $this->data->searchResults = User::searchName($_GET['search'], Connection::getDb());

            $this->render('quemSeguir', 'layout');
        }

        public function exit()
        {
            session_start();
            session_destroy();
            header('Location: /');
        }
    }
?>