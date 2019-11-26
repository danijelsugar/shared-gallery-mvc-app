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

    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        if (!$user || !password_verify($password, $user->password)) {
            return false;
        } else {
            return $user;
        }
    }

    protected function validate($user)
    {

    }

}