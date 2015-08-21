<?php
 
namespace Ofertas\Db;
 
class Db
{
    private $dsn;
    private $username;
    private $password;
 
    public function setDsn($dsn)
    {
        $this->dsn = $dsn;
    }
 
    public function setPassword($password)
    {
        $this->password = $password;
    }
 
    public function setUsername($username)
    {
        $this->username = $username;
    }
 
    /** @return \PDO */
    public function getPDO()
    {
        $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
        return new \PDO($this->dsn, $this->username, $this->password, $options);
    }
}