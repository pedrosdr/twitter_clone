<?php
    namespace MF\Security;

    class Hasher 
    {
        // fields
        private string $salt;
        private string $passwordHash;

        // constructor
        public function __construct(string $password, string $salt=null)
        {
            if($salt === null)
                $this->initSalt();
            else
                $this->salt = $salt;

            $this->initPasswordHash($password);
        }

        // properties
        public function getSalt() {return $this->salt;}
        public function getPasswordHash() {return $this->passwordHash;}

        // methods
        private function initPasswordHash(string $password)
        {
            $this->passwordHash = hash('sha256', '.::.:::salt::' . $this->salt . '::password::' . hash('sha256', $password) . '::.:::');
        }

        private function initSalt()
        {
            $this->salt = md5(rand());
        }
    }
?>