<?php
use PHPUnit\Framework\TestCase;

require_once 'config/Database.php';
require_once 'classes/User.php';

class SimpleDashboardTest extends TestCase {
    public function testGetUsers() {
        $database = new Database();
        $link = $database->getLink();
        $userObject = new User($link);

        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $result = $link->query($sql);
        $expected = $result->num_rows;
        $users = $userObject->getAll();
        $actual = count($users);
        $this->assertEquals($expected, $actual);
    }

    public function testAddUser() {
        $database = new Database();
        $link = $database->getLink();
        $userObject = new User($link);
        $expected = true;

        $userObject->setFirstname('Timothy TEST');
        $userObject->setLastname('Malando TEST');
        $userObject->setAge(29);
        $userObject->setEmail('anthony.timothy90@gmail.com');
        $userObject->setPassword(password_hash('123456', PASSWORD_DEFAULT));
        $userObject->setAvatar('johndoe.jpg');
        $userObject->setCity('Kibaha');
        $userObject->setRegion('Pwani');
        $userObject->setCountry('Tanzania');

        $actual = $userObject->create();
        $this->assertEquals($expected, $actual);

        // Remove user
        $sql = "DELETE FROM `users` WHERE `users`.`firstname` = ?";
        $stmt = $link->prepare($sql);
        $firstname = $userObject->getFirstname();
        $stmt->bind_param("s", $firstname);
        $stmt->execute();
    }

    public function testAddUserFewData() {
        $database = new Database();
        $link = $database->getLink();
        $userObject = new User($link);

        $userObject->setFirstname('Timothy TEST');
        $userObject->setLastname('Malando TEST');

        $expected = true;
        $actual = $userObject->create();
        $this->assertNotEquals($expected, $actual);
    }

    public function testGetUser() {
        $database = new Database();
        $link = $database->getLink();
        $userObject = new User($link);

        $id = 1;
        $sql  = 'SELECT * FROM `users` where id = ?';
        $stmt = $link->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $expected = $row['firstname'];

        $user = $userObject->getUser($id);
        $actual = $user->getFirstname();
        $this->assertEquals($expected, $actual);
    }

    public function testGetUserWrongId() {
        $database = new Database();
        $link = $database->getLink();
        $userObject = new User($link);

        $expected = null;
        $actual = $userObject->getUser('s');
        $this->assertEquals($expected, $actual);
    }

    public function testUpdateUserPassword() {
        $database = new Database();
        $link = $database->getLink();
        $userObject = new User($link);

        $user = $userObject->getUser(1);
        $expected = $user->getPassword();

        $new_password =  password_hash('newpass1234', PASSWORD_DEFAULT);
        $user->updatePassword($new_password);
        $actual = $user->getPassword();
        $this->assertNotEquals($expected, $actual);

        // Revert password
        $user->updatePassword($expected);
    }
}