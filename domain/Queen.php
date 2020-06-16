<?php


class Queen extends AbstractChessPiece
{
    function __construct($x, $y, $color)
    {
        parent::__construct($x, $y, $color);

        $fakeRook = new Rook($x, $y, $color);
        $fakeBishop = new Bishop($x, $y, $color);

        $this->dx = array_merge($fakeRook->getDx(), $fakeBishop->getDx());
        $this->dy = array_merge($fakeRook->getDy(), $fakeBishop->getDy());
    }

    public function getDx()
    {
        return $this->dx;
    }

    public function getDy()
    {
        return $this->dy;
    }

    public function str()
    {
        return "Q";
    }

    public static function copyFrom(ChessPiece &$other)
    {
        return new self($other->getX(), $other->getY(), $other->getColor());
    }
}