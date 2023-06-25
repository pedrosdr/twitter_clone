<?php
    namespace App\Models;

use PDO;

    class User
    {
        // fields
        private ?int $id;
        private ?string $name;
        private ?string $email;
        private ?string $password;
        private ?string $salt;

        // constructors
        public function __construct(?int $id, ?string $name, ?string $email, ?string $hash)
        {
            $this->id = $id;
            $this->name = $name;
            $this->email = $email;
            $this->password = substr($hash, 32);
            $this->salt = substr($hash, 0, 32);
        }

        // properties
        public function getId() {return $this->id;}
        public function getName() {return $this->name;}
        public function getEmail() {return $this->email;}
        public function getPassword() {return $this->password;}
        public function getSalt() {return $this->salt;}

        // methods
        public static function getInstances(PDO $db)
        {
            $query = 'SELECT id, nome, email, senha FROM usuarios';
            $data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

            $instances = array();
            foreach($data as $item)
            {
                $instances[] = new User($item['id'], $item['nome'], $item['email'], $item['senha']);
            }
            return $instances;  
        }
    }
?>