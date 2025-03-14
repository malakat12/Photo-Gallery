<?php

    class UserSkeleton
    {

        private $id;

        private $full_name;

        private $email;

        private $password;

        

        public function __construct($id, $full_name, $email, $password) {
            $this->id = $id;
            $this->full_name = $full_name;
            $this->email=$email;
            $this->password=$password;
        }
    
        public function getId()
        {
            return $this->id;
        }
        public function getName()
        {
            return $this->full_name;
        }
        public function getEmail()
        {
            return $this->email;
        }
        public function getPassword()
        {
            return $this->password;
        }

        public function setId($id)
        {
            $this->id = $id;
        }
    
        public function setName($full_name)
        {
            $this->full_name = $full_name;
        }
    
        public function setEmail($email)
        {
            $this->email = $email;
        }
    
        public function setPassword($password)
        {
            $this->password = $password;
        }

    }
?>