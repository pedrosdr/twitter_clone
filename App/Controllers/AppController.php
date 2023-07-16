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
        private int $limit = 5;
        private int $offset = 0;
        protected int $page = 1;

        public function __construct()
        {
            parent::__construct();
        }

        public function timeline()
        {
            session_start();

            Auth::checkAuth();

            if(isset($_GET['pagina']))
            {
                $this->offset = $this->limit * ((int) $_GET['pagina'] - 1);
                $this->page = $_GET['pagina'];
            }

            $user = new User(Connection::getDb());
            $user->setId($_SESSION['id']);
            $this->data->userInfo = $user->getInfo();
            $this->data->tweets = Tweet::getTweetsByPage($this->limit, $this->offset, Connection::getDb());

            $this->data->totalPages = ceil(Tweet::getTotal(Connection::getDb()) / $this->limit);

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

            $user = new User(Connection::getDb());
            $user->setId($_SESSION['id']);
            $this->data->userInfo = $user->getInfo();

            if(isset($_GET['search']))
                $this->data->searchResults = User::searchName($_GET['search'], Connection::getDb());

            $this->render('quemSeguir', 'layout');
        }

        public function removeTweet()
        {
            session_start();
            Auth::checkAuth();
            
            Tweet::removeTweet($_GET['id'], Connection::getDb());
            header('Location: /timeline');
        }

        public function exit()
        {
            session_start();
            session_destroy();
            header('Location: /');
        }
    }
?>