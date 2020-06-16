<?php

include_once "ChessPiece.php";
include_once "ChessBoard.php";
abstract class AbstractChessPiece implements ChessPiece
{
    private $table_name = 'piece';

    protected $id;

    protected $color;

    protected $x;

    protected $y;

    protected array $dx;

    protected array $dy;

    protected function __construct($x, $y, $color)
    {
        $this->x = $x;
        $this->y = $y;
        $this->color = $color;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    protected function checkRulesCorresponding($x, $y, ChessBoard &$board)
    {
        $dx = $this->getDx();
        for ($i = 0; $i < count($dx); $i++)
        {
            $newX = $this->x + $dx[$i];
            $newY = $this->y + $this->getDy()[$i];

            if (!ChessBoard::validateCoords($newX, $newY))
            {
                continue;
            }

            if ($x === $newX && $y === $newY)
            {
                $oppPiece = $board->get($x, $y);
                return !$oppPiece || ($oppPiece->isWhite() !== $this->isWhite());
            }
        }

        return false;
    }

    public function validateTurn($toX, $toY, ChessBoard $board)
    {
        if (!$this->checkRulesCorresponding($toX, $toY, $board))
        {
            return false;
        }

        $x = $this->x;
        $y = $this->y;

        $xDir = 0;
        $yDir = 0;

        $deltaX = $toX - $this->x;
        $deltaY = $toY - $this->y;

        if ($deltaX !== 0)
        {
            $xDir = $deltaX / abs($deltaX);
        }

        if ($deltaY !== 0)
        {
            $yDir = $deltaY / abs($deltaY);
        }

        $x += $xDir;
        $y += $yDir;

        while ($x !== $toX && $y !== $toY)
        {
            if ($board->get($x, $y))
            {
                return false;
            }

            $x += $xDir;
            $y += $yDir;
        }

        return true;
    }

    public abstract function getDx();

    public abstract function getDy();

    public function getX()
    {
        return $this->x;
    }

    public function setX($x): void
    {
        $this->x = $x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function setY($y): void
    {
        $this->y = $y;
    }

    public function isWhite(): bool
    {
        return $this->color === 0;
    }

    public function getColor(): int
    {
        return $this->color;
    }

    public function getId()
    {
        return $this->id;
    }

}