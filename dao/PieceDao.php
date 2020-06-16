<?php


class PieceDao extends AbstractDao
{

    public function __construct($conn)
    {
        parent::__construct('piece', $conn);
    }

    public function update(ChessPiece $piece)
    {
        $symbol = "'" . $piece->str() . "'";
        $id = $piece->getId() ? $piece->getId() : 'null';
        $x = $piece->getX();
        $y = $piece->getY();
        $color = $piece->getColor();

        $id=$piece->getId();

        $query = "update " . $this->table_name
            . " set id=$id, x=$x, y=$y, color=$color where id=$id;";

        $result = mysqli_query($this->conn, $query) or die("Cannot update game in db: " . mysqli_error($this->conn));

        if ($result)
        {
            return true;
        }

        return false;
    }

    public function save(ChessPiece $piece)
    {
        $symbol = "'" . $piece->str() . "'";
        $id = $piece->getId() ? $piece->getId() : 'null';
        $x = $piece->getX();
        $y = $piece->getY();
        $color = $piece->getColor();

        $query = "insert into " . $this->table_name
            . "(id, x, y, color, symbol) values ($id, $x, $y, $color, $symbol);";

        $result = mysqli_query($this->conn, $query) or die("Cannot save game to db: " . mysqli_error($this->conn));

        if ($result)
        {
            return true;
        }

        return false;
    }

    public function findAll()
    {
        $pieces = Array();
        $query = "select * from $this->table_name;";

        $result = mysqli_query($this->conn, $query) or die("Cannot get pieces from db: " . mysqli_error($this->conn));

        $rows = mysqli_num_rows($result);

        for ($i = 0; $i < $rows; $i++)
        {
            $row = mysqli_fetch_row($result);
            $pieces[] = $this->constructPieceFromRow($row);
        }

        return $pieces;
    }

    public function deleteAll()
    {
        $query = "delete from $this->table_name;";
        $result = mysqli_query($this->conn, $query) or die("Cannot delete game: " . mysqli_error($this->conn));
    }

    private function constructPieceFromRow($row)
    {
        $id = (int)$row[0];
        $x = (int)$row[1];
        $y = (int)$row[2];
        $color = (int)$row[3];
        $symbol = $row[4];

        $piece = null;
        switch ($symbol) {
            case 'K': {
                $piece = new King($x, $y, $color);
                break;
            }

            case 'Q': {
                $piece = new Queen($x, $y, $color);
                break;
            }

            case 'R': {
                $piece = new Rook($x, $y, $color);
                break;
            }

            case 'N': {
                $piece = new Knight($x, $y, $color);
                break;
            }

            case 'B': {
                $piece = new Bishop($x, $y, $color);
                break;
            }

            case 'P': {
                $piece = new Pawn($x, $y, $color);
                break;
            }

            default: {
                throw new Exception('Unknown piece type: ' . $symbol);
            }
        }

        $piece->setId($id);
        return $piece;
    }
}