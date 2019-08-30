<?php

class Token
{
    private $id;

    private $user;

    private $passwordHash;

    private $selectorHash;

    private $isExpired;

    private $expiryDate;

    public function __construct($id, $user, $passwordHash, $selectorHash, $isExpired, $expiryDate)
    {
        $this->setId($id);
        $this->setUser($user);
        $this->setPasswordHash($passwordHash);
        $this->setSelectorHash($selectorHash);
        $this->setIsExpired($isExpired);
        $this->setExpiryDate($expiryDate);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }

    public function __call($name, $arguments)
    {
        $function = substr($name, 0, 3);
        if ($function === 'set') {
            $this->__set(strtolower(substr($name, 3)), $arguments[0]);
            return $this;
        } elseif ($function === 'get') {
            return $this->__get(strtolower(substr($name, 3)));
        }
        return $this;
    }

    /**
     * @param int 
     * @param boolean
     */
    public static function getTokenByUserId($id, $expired)
    {
        $id = intval($id);
        $db = Db::connect();
        $stmt = $db->prepare('select * from token_auth where user=:id and isExpired=:isExpired');
        $stmt->bindValue('id', $id); 
        $stmt->bindValue('isExpired', $expired);
        $stmt->execute();
        $token = $stmt->fetchAll();
        
        if (!empty($token)) {
            return new Token($token[0]->id, $token[0]->user, $token[0]->passwordHash, $token[0]->selectorHash, $token[0]->isExpired, $token[0]->expiryDate);
        } else {
            return null;
        }
        
    }
}