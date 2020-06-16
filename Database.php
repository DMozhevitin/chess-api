<?php


class Database
{
    //Set up your database configuration here.
    private $host = "";
    private $database = "";
    private $username = "";
    private $password = "";
    public $conn;

    public function getConnection()
    {
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        return $this->conn;
    }
}