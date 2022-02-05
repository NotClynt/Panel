<?php

class Database
{
    private $dbHost = "HOST";
    private $dbUser = "DATABASE USER";
    private $dbPass = "DATABASE PASSWORD";
    private $dbName = "DATABASE NAME";

    protected $statement;
    protected $error;

    protected function connect()
    {
        try {
            $dsn = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName;
            $pdo = new PDO($dsn, $this->dbUser, $this->dbPass);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            return $pdo;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    protected function query($sql)
    {
        $this->statement = $this->connect()->query($sql);
    }

    protected function prepare($sql)
    {
        $this->statement = $this->connect()->prepare($sql);
    }
}
