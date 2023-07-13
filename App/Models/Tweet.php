<?php
    namespace App\Models;

    use App\Auth;
    use DateTime;
    use PDO;

    class Tweet 
    {
        // fields
        private PDO $db;
        private ?int $id;
        private ?int $userId;
        private ?string $tweet;
        private ?DateTime $date;

        // constructor
        public function __construct(PDO $db, ?int $userId = null, ?string $tweet = null, ?DateTime $date = null, ?int $id = null)
        {
            Auth::checkAuth();

            $this->db = $db;
            $this->id = $id;
            $this->userId = $userId;
            $this->tweet = $tweet;
            $this->date = $date;
        }

        // properties
        public function getId() {return $this->id;}
        public function setId(int $set) {$this->id = $set;}

        public function getUserId() {return $this->userId;}
        public function setUserId(int $set) {$this->userId = $set;}

        public function getTweet() {return $this->tweet;}
        public function setTweet(string $set) {$this->tweet = $set;}

        public function getDate() {return $this->date;}
        public function setDate(DateTime $set) {$this->date = $set;}

        // methods
        public static function getInstances(PDO $db)
        {
            Auth::checkAuth();

            $query = 'SELECT id, id_usuario, tweet, data FROM tweets';
            $result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

            $instances = array();
            foreach($result as $item)
            {
                $instances[] = new Tweet($db, $item['id_usuario'], $item['tweet'], new DateTime($item['data'], $item['id']));
            }
            return $instances;
        }

        public static function getTweetsByEmail(PDO $db, string $email)
        {
            Auth::checkAuth();

            $query = 'SELECT tweets.id as id, tweets.id_usuario as id_usuario, tweets.tweet as tweet, tweets.data as data 
                      FROM tweets
                      INNER JOIN usuarios
                      ON tweets.id_usuario = usuarios.id
                      WHERE usuarios.email = :email
                      ORDER BY data DESC';

            $stmt = $db->prepare($query);
            $stmt->bindValue(':email', $_SESSION['email']);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $instances = array();
            foreach($result as $item)
            {
                $instances[] = new Tweet($db, $item['id_usuario'], $item['tweet'], new DateTime($item['data']), $item['id']);
            }
            return $instances;
        }

        public function save()
        { 
            $query = 'INSERT INTO tweets(id_usuario, tweet) VALUES (:id_usuario, :tweet)';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->userId, PDO::PARAM_INT);
            $stmt->bindValue(':tweet', $this->tweet);
            $stmt->execute();
        }
    }
?>