<?php

namespace Gallery\Model;

use Gallery\Core\Db;

class User
{
    protected $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /*
    public function addNewUser($user)
    {
        $this->validate($user);
    }*/

    /**
     * @param string user email
     * @return object
     */
    public function findByEmail($email)
    {
        $db = $this->db;
        $stmt = $db->prepare('SELECT * FROM user WHERE email=:email');
        $stmt->bindValue('email', $email);
        $stmt->execute();

        $user = $stmt->fetch();
        return $user;
    }

    /**
     * Find user by id
     * 
     * @param int $id user id
     * 
     * @return object
     */
    public function findById(int $id)
    {
        $db = $this->db;
        $stmt = $db->prepare('SELECT id, firstName, lastName, userName, address FROM user WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $user = $stmt->fetch();

        return $user;
    }

    /**
     * Authenticate user if correct credentials
     * 
     * @param string $email user email
     * @param string $password entered password
     * 
     * @return boolean|object if wrong credentials return false, if correct returns user object
     */
    public function authenticate(string $email, string $password)
    {
        $user = $this->findByEmail($email);
        if (!$user || !password_verify($password, $user->password)) {
            return false;
        } else {
            return $user;
        }
    }

    /**
     * Inserts new user
     * 
     * @param string $firstName user first name
     * @param string $lastName user last name
     * @param string $username username
     * @param string $email user email
     * @param string $address user address
     * @param string $password password
     * 
     * @return boolean
     * 
     */
    public function addUser(
        string $firstName,
        string $lastName,
        string $username,
        string $email,
        string $address,
        string $password
    ) {
        $db = $this->db;
        $stmt = $db->prepare('insert into user (firstName,lastName,userName,email,address,password) 
                values (:firstName,:lastName,:userName,:email,:address,:password)');
        $stmt->bindValue('firstName', $firstName);
        $stmt->bindValue('lastName', $lastName);
        $stmt->bindValue('userName', $username);
        $stmt->bindValue('email', $email);
        $stmt->bindValue('address', $address);
        $stmt->bindValue('password', password_hash($password, PASSWORD_BCRYPT));
        return $stmt->execute();
    }

    /**
     * Edit existing user
     * 
     * @param string $firstName user first name
     * @param string $lastName user last name
     * @param string $username username
     * @param string $address user address
     * 
     * @return boolean
     * 
     */
    public function editUser(string $firstName, string $lastName, string $username, string $address, int $id)
    {
        $db = $this->db;
        $stmt = $db->prepare('UPDATE user SET firstName=:firstName, lastName=:lastName, userName=:userName, 
                            address=:address WHERE id=:id');
        $stmt->bindValue('firstName', $firstName);
        $stmt->bindValue('lastName', $lastName);
        $stmt->bindValue('userName', $username);
        $stmt->bindValue('address', $address);
        $stmt->bindValue('id', $id);
        return $stmt->execute();
    }

    /**
     * Return password hash from database
     * 
     * @param int $id user id
     * 
     * @return string|boolean user password hash or false if user not found
     */
    public function getPasswordById(int $id)
    {
        $db = $this->db;
        $stmt = $db->prepare('SELECT password FROM user WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $password = $stmt->fetch();

        return $password;
    }

    /**
     * Changes user current password
     * 
     * @param string $password new password hash
     * @param int id user id
     * 
     * @return boolean
     */
    public function changePassword(string $password, int $id)
    {
        $db = $this->db;
        $stmt = $db->prepare('UPDATE user SET password=:password WHERE id=:id');
        $stmt->bindValue('password', $password);
        $stmt->bindValue('id', $id);
        return $stmt->execute();
    }

    /*
    protected function validate($user)
    {

    }*/
}
