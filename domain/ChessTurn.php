<?php


class ChessTurn
{
    public int $fromX;

    public int $fromY;

    public int $toX;

    public int $toY;

    public function __construct(int $fromX, int $fromY, int $toX, int $toY)
    {
        $this->fromX = $fromX;
        $this->fromY = $fromY;
        $this->toX = $toX;
        $this->toY = $toY;
    }

}