<?php


class Pawn extends AbstractChessPiece
{
    private $startX;
    private $startY;

    public function __construct($x, $y, $color)
    {
        parent::__construct($x, $y, $color);
        $this->startX = $x;
        $this->startY = $y;
    }

    private function positionChanged()
    {
        return ($this->x !== $this->startX || $this->y !== $this->startY);
    }

    public function getDx()
    {
        if ($this->isWhite())
        {
            return $this->positionChanged() ? Array(-1) : Array(-1, -2);
        } else
        {
            return $this->positionChanged() ? Array(1) : Array(1, 2);
        }
    }

    public function getDy()
    {
        return Array(0, 0);
    }

    public function str()
    {
        return "P";
    }

    public static function copyFrom(ChessPiece &$other)
    {
        return new self($other->getX(), $other->getY(), $other->getColor());
    }
}