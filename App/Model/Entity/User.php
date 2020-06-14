<?php 

class User
{
    protected $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function addNewUser($user)
    {
        $this->validate($user);
    }

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
     * @return Object
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

    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        if (!$user || !password_verify($password, $user->password)) {
            return false;
        } else {
            return $user;
        }
    }

    public function addUser($firstName, $lastName, $username, $email, $address, $password)
    {
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

    public function editUser($firstName, $lastName, $username, $address)
    {
        $db = $this->db;
        $stmt = $db->prepare('UPDATE user SET firstName=:firstName, lastName=:lastName, userName=:userName, address=:address');
        $stmt->bindValue('firstName', $firstName);
        $stmt->bindValue('lastName', $lastName);
        $stmt->bindValue('userName', $username);
        $stmt->bindValue('address', $address);
        return $stmt->execute();
        
    }

    protected function validate($user)
    {

    }

}