<?php


class Bishop extends AbstractChessPiece
{

    function __construct($x, $y, $color)
    {
        parent::__construct($x, $y, $color);

        $dx = array();
        $dy = array();

        for ($i = 1; $i < 8; $i++) {
            $dx[] = $i;
            $dy[] = $i;

            $dx[] = $i;
            $dy[] = -$i;

            $dx[] = -$i;
            $dy[] = $i;

            $dx[] = -$i;
            $dy[] = -$i;
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
        return "B";
    }

    public static function copyFrom(ChessPiece &$other)
    {
        return new self($other->getX(), $other->getY(), $other->getColor());
    }
}