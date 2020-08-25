<?php
    class User {
        private $link;
        private $table_name = 'users';

        private $id;
        private $firstname;
        private $lastname;
        private $age;
        private $email;
        private $password;
        private $avatar;
        private $city;
        private $region;
        private $country;
        private $created_at;

        public function __construct($link){
            $this->link = $link;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setFirstname($firstname) {
            $this->firstname = $firstname;
        }

        public function setLastname($lastname) {
            $this->lastname = $lastname;
        }

        public function setAge($age) {
            $this->age = $age;
        }
        
        public function setEmail($email) {
            $this->email = $email;
        }

        public function setPassword($password) {
            $this->password = $password;
        }

        public function setAvatar($avatar) {
            $this->avatar = $avatar;
        }

        public function setCity($city) {
            $this->city = $city;
        }

        public function setRegion($region) {
            $this->region = $region;
        }

        public function setCountry($country) {
            $this->country = $country;
        }

        public function setCreatedAt($created_at) {
            $this->created_at = $created_at;
        }

        public function getId() {
            return $this->id;
        }

        public function getFirstname() {
            return $this->firstname;
        }

        public function getLastname() {
            return $this->lastname;
        }

        public function getAge() {
            return $this->age;
        }
        
        public function getEmail() {
            return $this->email;
        }

        public function getPassword() {
            return $this->password;
        }

        public function getAvatar() {
            return $this->avatar;
        }

        public function getCity() {
            return $this->city;
        }

        public function getRegion() {
            return $this->region;
        }

        public function getCountry() {
            return $this->country;
        }

        public function getCreatedAt() {
            return $this->created_at;
        }

        public function create() {
            $success = false;
            try {
                $stmt = $this->link->prepare("INSERT INTO users (`firstname`, `lastname`, `age`, `email`, `password`, `avatar`, `city`, `region`, `country`) 
                VALUES (?,?,?,?,?,?,?,?,?)");
                $stmt->bind_param('ssissssss', $this->firstname, $this->lastname, $this->age, $this->email, $this->password, $this->avatar, $this->city, $this->region, $this->country);
                $stmt->execute();
                if ($stmt->affected_rows == 1) {
                    $this->setId($this->link->insert_id);
                    $success = true;
                }
            } Catch (Exception $ex) {
                return $success;
            }
            return $success;
        }

        public function getAll() {
            $users = array();
            $sql = "SELECT * FROM users ORDER BY created_at DESC";
            if ($result = $this->link->query($sql)) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        $u = $this->prepareUser($row);
                        $users[] = $u;
                    }
                }
            }
            return $users;
        }

        public function getUser($id) {
            $user = null;
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->link->prepare($sql);
            $param_id = trim($id);
            $stmt->bind_param("i", $param_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $user = $this->prepareUser($row);
            }
            return $user;
        }

        public function updatePassword($password) {
            $success = false;
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $this->link->prepare($sql);
            $stmt->bind_param("si", $password, $this->id);
            $stmt->execute();
            if ($stmt->affected_rows == 1) {
                $this->setPassword($password);
                $success = true;
            }
            return $success;
        }

        public function prepareUser($data) {
            $user = new User($this->link);
            $user->setId($data['id']);
            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);
            $user->setAge($data['age']);
            $user->setEmail($data['email']);
            $user->setPassword($data['password']);
            $user->setAvatar($data['avatar']);
            $user->setCity($data['city']);
            $user->setRegion($data['region']);
            $user->setCountry($data['country']);
            $user->setCreatedAt($data['created_at']);
            return $user;
        }
    }
?>