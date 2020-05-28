<?php

class Image
{
    protected $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function addImage($user, $name) 
    {
        $db = $this->db;
        $stmt = $db->prepare('INSERT INTO images (user,imgLocation) VALUES (:user,:imgLocation)');
        $stmt->bindValue('user', $user);
        $stmt->bindValue('imgLocation', $name);
        $stmt->execute();
    }
}