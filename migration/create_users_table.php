<?php
    // Include config file
    require_once '../config/database.php';

    if ($link) {
        $sql = "CREATE TABLE IF NOT EXISTS `users` (
            `id` int(255) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `firstname` varchar(255) NOT NULL,
            `lastname` varchar(255) NOT NULL,
            `age` int(3) NOT NULL,
            `email` varchar(255) NOT NULL,
            `password` text NOT NULL,
            `avatar` text NOT NULL,
            `city` varchar(255) NOT NULL,
            `region` varchar(255) NOT NULL,
            `country` varchar(255) NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
          
        if(mysqli_query($link, $sql)){
            echo 'Users table created successfully.';
        } else {
            echo 'Users table did not created. Please verify your configuration and try again.';
        }
    } else {
        echo 'Please verify your database server configuration and try again.';
    }
?>