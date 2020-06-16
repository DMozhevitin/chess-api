<?php

class Rook extends AbstractChessPiece
{
    function __construct($x, $y, $color)
    {
        parent::__construct($x, $y, $color);

        $dx = Array();
        $dy = Array();

        for ($i = 1; $i < 8; $i++)
        {
            $dx[] = $i;
            $dy[] = 0;

            $dx[] = -$i;
            $dy[] = 0;

            $dy[] = $i;
            $dx[] = 0;

            $dy[] = -$i;
            $dx[] = 0;
        }

        $this->dx = $dx;
        $this->dy = $dy;
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
        return "R";
    }

    public static function copyFrom(ChessPiece &$other)
    {
        return new self($other->getX(), $other->getY(), $other->getColor());
    }
}