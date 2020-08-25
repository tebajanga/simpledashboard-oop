<?php
    require_once __DIR__ . '/../environment.php';

    class Database {
        private $db_host;
        private $db_user;
        private $db_password;
        private $db_name;
        public $link;

        public function __construct() {
            $this->db_host = DB_HOST;
            $this->db_user = DB_USER;
            $this->db_password = DB_PASSWORD;
            $this->db_name = DB_NAME;
        }

        public function getLink() {
            $this->link = null;
            if (!$this->link = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name)) {
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
            return $this->link;
        }
    }
?>
