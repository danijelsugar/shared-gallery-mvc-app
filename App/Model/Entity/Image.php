<?php

class Image
{
    protected $db;

    /**
     * @param Db $db Database instance
     */
    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * Insert in images table
     * 
     * @param int $id id of user uploading image
     * @param string $name unique generated image name
     */
    public function addImage(int $user, $name) 
    {
        $db = $this->db;
        $stmt = $db->prepare('INSERT INTO images (user,imgLocation) VALUES (:user,:imgLocation)');
        $stmt->bindValue('user', $user);
        $stmt->bindValue('imgLocation', $name);
        $stmt->execute();
    }

    /**
     * Gets images from all users
     * 
     * @return array array of image objects
     */
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

    /**
     * Delete image from database
     * 
     * @param int $id Image id
     */
    public function deleteImg(int $id)
    {
        $db = $this->db;
        $stmt = $db->prepare('DELETE FROM images WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

    /**
     * Return images count
     * 
     * @return mixed Number of images
     */
    public function getImageCount()
    {
        $db = $this->db;
        $stmt = $db->prepare('SELECT count(id) FROM images');
        $stmt->execute();
        $imgCount = $stmt->fetchColumn();

        return $imgCount;
    }

    /**
     * Find image by id
     * 
     * @param int $id Image id
     * 
     * @return Object
     */
    public function findImgById(int $id)
    {
        $db = $this->db;
        $stmt = $db->prepare('SELECT * FROM images WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $img = $stmt->fetch();

        return $img;
    }
}