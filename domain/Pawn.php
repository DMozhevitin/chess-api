<?php


class Pawn extends AbstractChessPiece
{
    private $startX;

    public function __construct($x, $y, $color)
    {
        parent::__construct($x, $y, $color);
        $this->startX = $color === 1 ? 1 : 6;
    }

    private function positionChanged()
    {
        return ($this->x !== $this->startX);
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