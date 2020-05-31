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

    public function getImages()
    {
        $db = $this->db;
        $stmt = $db->prepare('SELECT a.id, a.user, b.userName, a.name, b.email, b.address, a.imgLocation 
                        FROM images a 
                        INNER JOIN user b 
                            ON a.user=b.id');
        $stmt->execute();
        $images = $stmt->fetchAll();

        return $images;
    }

    public function deleteImg($id)
    {
        
    }
}