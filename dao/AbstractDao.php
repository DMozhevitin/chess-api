<?php


class AbstractDao
{
    protected $table_name;

    protected $conn;

    protected function __construct($table_name, $conn)
    {
        $this->conn = $conn;
        $this->table_name = $table_name;
    }

    public function deleteAll()
    {
        $query = "delete from $this->table_name;";
        $result = mysqli_query($this->conn, $query) or die("Cannot delete game: " . mysqli_error($this->conn));
    }
}