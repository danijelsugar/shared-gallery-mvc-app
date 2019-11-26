<?php

class Token
{
    
    protected $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * @param string User id 
     * @param boolean
     */
    public function getTokenByUserId($id, $expired)
    {
        $id = intval($id);
        $db = $this->db;
        $stmt = $db->prepare('select * from token_auth where user=:id and isExpired=:isExpired');
        $stmt->bindValue('id', $id); 
        $stmt->bindValue('isExpired', $expired);
        $stmt->execute();
        
        $token = $stmt->fetch();
        
        return $token;
        
    }

    /**
     * @param string Token id
     */
    public function setTokenExpired($tokenId)
    {
        $tokenId = intval($tokenId);
        $db = $this->db;
        $stmt = $db->prepare('UPDATE token_auth SET isExpired=:isExpired WHERE id=:tokenId');
        $stmt->bindValue('isExpired', 1);
        $stmt->bindValue('tokenId', $tokenId);
        $stmt->execute();
    }

    public function insertNewToken($userId, $randomPasswordHash, $randomSelectorHash, $expiryDate)
    {
        $userId = intval($userId);
        $db = $this->db;
        $stmt = $db->prepare('INSERT INTO token_auth (user,passwordHash,selectorHash,expiryDate) 
                            VALUES (:user,:passwordHash,:selectorHash,:expiryDate)');
        $stmt->bindValue('user', $userId);
        $stmt->bindValue('passwordHash', $randomPasswordHash);
        $stmt->bindValue('selectorHash', $randomSelectorHash);
        $stmt->bindValue('expiryDate', $expiryDate);
        $stmt->execute();
    }
}