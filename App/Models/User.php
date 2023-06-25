<?php
    namespace App\Models;

    class User
    {
        // fields
        private ?int $id;
        private ?string $email;
        private ?string $password;
        private ?string $salt;

        // constructors
        public function __construct(?int $id, ?string $email, ?string $hash)
        {
            
        }
    }
?>