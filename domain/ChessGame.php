<?php

include_once "exception\ChessException.php";

class ChessGame
{

    private ChessBoard $board;

    private $id;

    private bool $isWhiteTurn;

    private bool $isCheck;

    private bool $isActive;

    private $whiteKing;

    private $blackKing;

    public static function newGame()
    {
        $game = new self();
        $game->board = ChessBoard::newBoard();
        $game->isWhiteTurn = true;
        $game->isCheck = false;
        $game->isActive = true;
        $wk = $game->board->get(7, 4);
        $game->whiteKing = &$wk;
        $bk = $game->board->get(0, 4);
        $game->blackKing = &$bk;

        return $game;
    }

    public function __construct()
    {
        $this->board = ChessBoard::emptyBoard();
    }

    public function makeTurn(string &$turnDescription)
    {
        $turn = self::fromChessNotation($turnDescription);
        $from = $this->board->get($turn->fromX, $turn->fromY);

        if (!$from || $from->isWhite() !== $this->isWhiteTurn)
        {
            throw new ChessException("Invalid turn");
        }

        $from->validateTurn($turn->toX, $turn->toY, $this->board);
        $to = $this->board->get($turn->toX, $turn->toY);
        $nullPiece = null;

        $this->board->set($turn->toX, $turn->toY, $from);
        $this->board->set($turn->fromX, $turn->fromY, $nullPiece);

        if ($this->currentPlayerKing()->isUnderAttack($this->board)) //rollback
        {
            $this->board->set($turn->fromX, $turn->fromY, $from);
            $this->board->set($turn->toX, $turn->toY, $to);
            throw new ChessException("Your king is under attack after this turn($turnDescription");
        }

        if ($this->opponentKing()->isUnderAttack($this->board))
        {
            $this->isCheck = true;

            if (!$this->opponentKing()->canGoSomewhere($this->board))
            {
                $this->isActive = false;
            }
        }

        $this->isWhiteTurn = !$this->isWhiteTurn;
    }


    //example: e2-e4.
    private static function fromChessNotation(string $turnDescription): ChessTurn
    {
        $spl = explode("-", $turnDescription);
        if (count($spl) !== 2 || strlen($spl[0]) !== 2 || strlen($spl[1]) !== 2)
        {
            throw new ChessException("Invalid turn description");
        }

        $yFrom = ord(strtoupper($spl[0])) - 65;
        $xFrom = 8 - (int)$spl[0][1];

        $yTo = ord(strtoupper($spl[1])) - 65;
        $xTo = 8 - (int)$spl[1][1];

        return new ChessTurn($xFrom, $yFrom, $xTo, $yTo);
    }

    private function currentPlayerKing()
    {
        return $this->isWhiteTurn ? $this->whiteKing : $this->blackKing;
    }

    private function opponentKing()
    {
        return $this->isWhiteTurn ? $this->blackKing : $this->whiteKing;
    }

    public function printBoard()
    {
        $this->board->print();
    }

    public function isWhiteTurn(): bool
    {
        return $this->isWhiteTurn;
    }


    public function isCheck(): bool
    {
        return $this->isCheck;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getWhiteKing()
    {
        return $this->whiteKing;
    }


    public function getBlackKing()
    {
        return $this->blackKing;
    }

    public function getBoard()
    {
        return $this->board;
    }

    public function setBoard(ChessBoard $board): void
    {
        $this->board = $board;
    }

    public function setIsWhiteTurn(bool $isWhiteTurn): void
    {
        $this->isWhiteTurn = $isWhiteTurn;
    }

    public function setIsCheck(bool $isCheck): void
    {
        $this->isCheck = $isCheck;
    }


    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setWhiteKing($whiteKing): void
    {
        $this->whiteKing = $whiteKing;
    }

    public function setBlackKing($blackKing): void
    {
        $this->blackKing = $blackKing;
    }

    public function getJson($withBoard)
    {
        $gameView = Array('white_turn' => $this->isWhiteTurn,
            'check' => $this->isCheck, 'active' => $this->isActive);

        if ($withBoard)
        {
            $gameView['board'] = $this->board->getJson();
        }

        return $gameView;
    }
}