<?php
    namespace App\Models;
    use MF\Security\Hasher;
    use PDO;

    class User
    {
        // fields
        private PDO $db;
        private ?int $id;
        private ?string $name;
        private ?string $email;
        private ?string $password;
        private ?string $salt;
        private bool $following = FALSE;

        // constructors
        public function __construct(PDO $db, ?string $name = null, ?string $email = null, ?int $id = null, ?string $hash = null)
        {
            $this->db = $db;
            $this->id = $id;
            $this->name = $name;
            $this->email = $email;
            $this->password = substr($hash, 32);
            $this->salt = substr($hash, 0, 32);
        }

        // properties
        public function getId() {return $this->id;}
        public function setId(int $set) {$this->id = $set;}

        public function getName() {return $this->name;}
        public function getEmail() {return $this->email;}
        public function getPassword() {return $this->password;}
        public function getSalt() {return $this->salt;}
        public function getFollowing() {return $this->following;}
        public function setFollowing(bool $set) {$this->following = $set;}

        // methods
        public static function getInstances(PDO $db)
        {
            $query = 'SELECT id, nome, email, senha FROM usuarios';
            $data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

            $instances = array();
            foreach($data as $item)
            {
                $instances[] = new User($db, $item['nome'], $item['email'], $item['id'], $item['senha']);
            }
            return $instances;  
        }

        public static function getUsersByEmail(string $email, PDO $db)
        {
            $query = 'SELECT id, nome, email, senha FROM usuarios WHERE email = :email';
            $stmt = $db->prepare($query);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $users = array();
            foreach($result as $item)
            {
                $users[] = new User($db, $item['nome'], $item['email'], $item['id'], $item['senha']);
            }
            return $users;
        }

        public static function searchName(string $pattern, PDO $db)
        {
            session_start();

            $query = 'SELECT 
                            u.id, 
                            u.nome, 
                            u.email, 
                            u.senha,
                            (SELECT
                                COUNT(*)
                            FROM
                                usuarios_seguindo as us
                            WHERE
                                us.id_usuario = :id_usuario AND us.id_seguindo = u.id
                            ) as seguindo
                        FROM 
                            usuarios as u
                        WHERE
                            u.nome LIKE :pattern';
       
            $stmt = $db->prepare($query);
            $stmt->bindValue(':pattern', '%' . $pattern . '%');
            $stmt->bindValue(':id_usuario', $_SESSION['id'], PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $users = array();
            foreach($result as $item)
            {
                $user = new User($db, $item['nome'], $item['email'], $item['id']);
                $user->following = (bool) $item['seguindo'];
                $users[] = $user;
            }
            return $users;
        }

        public function addFollowed($followedId)
        {
            $query = 'INSERT INTO usuarios_seguindo (id_usuario, id_seguindo) VALUES(:id_usuario, :id_seguindo)';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':id_seguindo', $followedId, PDO::PARAM_INT);
            $stmt->execute();
        }

        public function removeFollowed($followedId)
        {
            $query = 'DELETE FROM usuarios_seguindo WHERE id_usuario = :id_usuario AND id_seguindo = :id_seguindo';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':id_seguindo', $followedId, PDO::PARAM_INT);
            $stmt->execute();
        }

        public function getFollowedUsers()
        {
            $query = 'SELECT seguidos.id AS id, seguidos.nome AS nome, seguidos.email AS email 
                      FROM usuarios AS seguidos
                      INNER JOIN usuarios_seguindo ON id = usuarios_seguindo.id_seguindo 
                      WHERE usuarios_seguindo.id_usuario = :id_usuario';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $users = array();
            foreach($result as $item)
            {
                $users[] = new User($this->db, $item['nome'], $item['email'], $item['id']);
            }
            return $users;
        }

        public function getTweets()
        {
            return Tweet::getTweetsByEmail($this->db, $this->email);
        }

        public function getInfo()
        {
            $query = 'SELECT
                            u.id as id,
                            u.nome as name,
                            u.email as email,
                            u.senha as password,
                            (SELECT 
                                COUNT(*) 
                            FROM 
                                usuarios_seguindo AS us
                            WHERE
                                us.id_usuario = u.id) AS following,
                            (SELECT
                                COUNT(*)
                            FROM 
                                usuarios_seguindo AS us
                            WHERE us.id_seguindo = u.id) AS followers,
                            (SELECT
                                COUNT(*)
                            FROM 
                                tweets AS t
                            WHERE 
                                t.id_usuario = u.id) AS tweets
                        FROM 
                            usuarios AS u
                        WHERE 
                            u.id = :id';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        }

        public function save($password)
        {
            $query = 'INSERT INTO usuarios(nome, email, senha) VALUES(:nome, :email, :senha)';
            $statement = $this->db->prepare($query);
            $statement->bindValue(':nome', $this->name, PDO::PARAM_STR);
            $statement->bindValue(':email', $this->email, PDO::PARAM_STR);

            $hasher = new Hasher($password);
            $statement->bindValue(':senha', $hasher->getSalt() . $hasher->getPasswordHash() , PDO::PARAM_STR);
            $statement->execute();

            $this->password = $hasher->getPasswordHash();
            $this->salt = $hasher->getSalt();
        }
    }
?>