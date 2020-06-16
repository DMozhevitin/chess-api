<?php


class ChessBoard
{
    private array $board;

    private static int $SIZE = 8;

    public static function newBoard()
    {
        $b = new self();
        $b->board = array(
            self::createPiecesRow(0, 1),
            self::createPawnsRow(1, 1),
            array_fill(0, self::$SIZE, null),
            array_fill(0, self::$SIZE, null),
            array_fill(0, self::$SIZE, null),
            array_fill(0, self::$SIZE, null),
            self::createPawnsRow(6, 0),
            self::createPiecesRow(7, 0)
        );

        return $b;
    }

    public static function emptyBoard()
    {
        $b = new self();
        for ($i = 0; $i < self::$SIZE; $i++) {
            $b->board[] = array_fill(0, self::$SIZE, null);
        }

        return $b;
    }

    private function __construct()
    {}

    private static function createPawnsRow($x, $color)
    {
        $pawns = array();
        for ($y = 0; $y < self::$SIZE; $y++) {
            $pawns[$y] = new Pawn($x, $y, $color);
        }

        return $pawns;
    }

    private static function createPiecesRow($x, $color)
    {
        return array(
            new Rook($x, 0, $color),
            new Knight($x, 1, $color),
            new Bishop($x, 2, $color),
            new Queen($x, 3, $color),
            new King($x, 4, $color),
            new Bishop($x, 5, $color),
            new Knight($x, 6, $color),
            new Rook($x, 7, $color)
        );
    }

    public function get($x, $y)
    {
        return $this->board[$x][$y];
    }

    public function set($x, $y, &$piece)
    {
        if (!self::validateCoords($x, $y)) {
            throw new Exception("Invalid cell coordinates");
        }

        $this->board[$x][$y] = $piece;

        if ($piece) {
            $piece->setX($x);
            $piece->setY($y);
        }
    }

    public function getJson()
    {
        $boardView = array();
        for ($i = 0; $i < self::$SIZE; $i++) {
            $boardView[] = array();
            for ($j = 0; $j < self::$SIZE; $j++) {
                $piece = $this->get($i, $j);
                if ($piece) {
                    $clr = $piece->isWhite() ? 'white' : 'black';
                    $symb = $piece->str();
                    $boardView[$i][] = (object) Array('color' => $clr, 'piece' => $symb);
                } else {
                    $boardView[$i][] = null;
                }
            }
        }

        return $boardView;
    }

    public static function validateCoords($x, $y): bool
    {
        return $x >= 0 && $x < self::$SIZE && $y >= 0 && $y < self::$SIZE;
    }

}