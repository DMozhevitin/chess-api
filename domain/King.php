<?php


class King extends AbstractChessPiece
{
    function __construct($x, $y, $color)
    {
        parent::__construct($x, $y, $color);
        $this->dx = Array(-1, -1, -1, 0, 1, 1, 1, 0);
        $this->dy = Array(0, 1, 1, 1, 0, -1, -1, -1);
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
        return "K";
    }


    private function makeFakeMoves(ChessPiece $piece, ChessBoard $board, $str)
    {
        for ($i = 0; $i < count($piece->getDx()); $i++)
        {
            $x = $this->x + $piece->getDx()[$i];
            $y = $this->y + $piece->getDy()[$i];

            if (!ChessBoard::validateCoords($x, $y))
            {
                continue;
            }

            $oppF = $board->get($x, $y);
            if ($piece->validateTurn($x, $y, $board) && $oppF && $str === $oppF->str())
            {
                return true;
            }
        }

        return false;
    }

    public function isUnderAttack(ChessBoard &$board): bool
    {
        $fakePieces = Array(
            Bishop::copyFrom($this),
            Knight::copyFrom($this),
            Queen::copyFrom($this),
            Rook::copyFrom($this)
        );

        foreach ($fakePieces as $f)
        {
            if ($this->makeFakeMoves($f, $board, $f->str()))
            {
                return true;
            }
        }

        return false;
    }

    public function canGoSomewhere(ChessBoard &$board)
    {
        for ($i = 0; $i < count($this->dx); $i++)
        {
            $newX = $this->x + $this->dx[$i];
            $newY = $this->y + $this->dy[$i];

            if ($this->validateTurn($newX, $newY, $board))
            {
                if (!(new King($newX, $newY, $this->color))->isUnderAttack($board))
                {
                    return true;
                }
            }
        }

        return false;
    }

    public static function copyFrom(ChessPiece &$other)
    {
        return new self($other->getX(), $other->getY(), $other->getColor());
    }
}