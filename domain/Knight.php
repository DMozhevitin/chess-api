<?php


class Knight extends AbstractChessPiece
{

    public function __construct($x, $y, $color)
    {
        parent::__construct($x, $y, $color);

        $this->dx = Array(-2, -2, -1, 1, 2, 2, 1, -1);
        $this->dy = Array(-1, 1, 2, 2, 1, -1, -2, -2);
    }

    public function getDx()
    {
        return $this->dx;
    }

    public function getDy()
    {
        return $this->dy;
    }

    public function validateTurn($toX, $toY, ChessBoard $board)
    {
        return $this->checkRulesCorresponding($toX, $toY, $board);
    }

    public function str()
    {
        return "N";
    }

    public static function copyFrom(ChessPiece &$other)
    {
        return new self($other->getX(), $other->getY(), $other->getColor());
    }
}