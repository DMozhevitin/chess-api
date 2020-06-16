<?php


class GameDao extends AbstractDao
{

    public function __construct($conn)
    {
        parent::__construct('game', $conn);
    }

    public function update(ChessGame $game)
    {
        $wkx = $game->getWhiteKing()->getX();
        $wky = $game->getWhiteKing()->getY();

        $bkx = $game->getBlackKing()->getX();
        $bky = $game->getBlackKing()->getY();

        $is_white_turn = $game->isWhiteTurn() ? '1' : '0';
        $is_check = $game->isCheck() ? '1' : '0';
        $is_active = $game->isActive() ? '1' : '0';

        $id = $game->getId();

        $query = "update " . $this->table_name
            . " set id=$id, is_white_turn=$is_white_turn, is_check=$is_check, is_active=$is_active, "
             . "white_king_x = $wkx, white_king_y = $wky, black_king_x=$bkx, black_king_y = $bky "
               . "where id=$id;";

        $result = mysqli_query($this->conn, $query) or die("Cannot update game in db: " . mysqli_error($this->conn));

        if ($result)
        {
            return true;
        }

        return false;
    }

    public function save(ChessGame $game)
    {
        $wkx = $game->getWhiteKing()->getX();
        $wky = $game->getWhiteKing()->getY();

        $bkx = $game->getBlackKing()->getX();
        $bky = $game->getBlackKing()->getY();

        $is_white_turn = $game->isWhiteTurn() ? '1' : '0';
        $is_check = $game->isCheck() ? '1' : '0';
        $is_active = $game->isActive() ? '1' : '0';

        $query = "insert into " . $this->table_name .
            " (is_white_turn, is_check, is_active, white_king_x, white_king_y, black_king_x, black_king_y) " .
            " values ($is_white_turn, $is_check, $is_active, $wkx, $wky, $bkx, $bky);";

        $result = mysqli_query($this->conn, $query) or die("Cannot save game to db: " . mysqli_error($this->conn));

        if ($result)
        {
            return true;
        }

        return false;
    }

    public function find()
    {
        $query = "select * from $this->table_name;";

        $result = mysqli_query($this->conn, $query) or die("Cannot get game from db: " . mysqli_error($this->conn));

        $rows = mysqli_num_rows($result);

        assert($rows === 1);

        $row = mysqli_fetch_row($result);

        return $this->constructGameFromRow($row);
    }

    private function constructGameFromRow($row)
    {
        $id = (int)$row[0];
        $is_white_turn = (bool)$row[1];
        $is_check = (bool)$row[2];
        $is_active = (bool)$row[3];

        $game = new ChessGame();
        $game->setId($id);
        $game->setIsActive($is_active);
        $game->setIsWhiteTurn($is_white_turn);
        $game->setIsCheck($is_check);

        return $game;
    }
}