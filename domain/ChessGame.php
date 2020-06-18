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


    private static $symbol2PieceName = Array(
        'P' => 'pawn',
        'K' => 'king',
        'Q' => 'queen',
        'R' => 'rook',
        'N' => 'knight',
        'B' => 'bishop');

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

        if (!$this->isActive) {
            throw new ChessException("The game is finished");
        }

        if (!$from) {
            throw new ChessException("There is no piece on $turn->fromStr");
        }

        if ($from->str() !== $turn->fromPiece) {
            $name = self::$symbol2PieceName[$turn->fromPiece];
            throw new ChessException("There is no $name on $turn->fromStr");
        }

        if ($from->isWhite() !== $this->isWhiteTurn) {
            throw new ChessException("The order of turn is violated");
        }

        if (!$from->validateTurn($turn->toX, $turn->toY, $this->board)) {
            throw new ChessException("Invalid turn");
        }

        $to = $this->board->get($turn->toX, $turn->toY);
        $nullPiece = null;

        $this->board->set($turn->toX, $turn->toY, $from);
        $this->board->set($turn->fromX, $turn->fromY, $nullPiece);

        if ($this->currentPlayerKing()->isUnderAttack($this->board)) //rollback
        {
            $this->board->set($turn->fromX, $turn->fromY, $from);
            $this->board->set($turn->toX, $turn->toY, $to);
            throw new ChessException("Your king will be under attack");
        }

        if ($this->opponentKing()->isUnderAttack($this->board)) {
            $this->isCheck = true;

            if (!$this->opponentKing()->canGoSomewhere($this->board)) {
                $this->isActive = false;
            }
        }

        $this->isWhiteTurn = !$this->isWhiteTurn;
    }


    //examples: e2-e4, Ng1-f3
    private static function fromChessNotation(string $turnDescription): ChessTurn
    {
        $pieceSymbols = array('K', 'Q', 'R', 'N', 'B');
        $spl = explode("-", $turnDescription);
        if (count($spl) !== 2 || (strlen($spl[0]) !== 3 && strlen($spl[0]) !== 2) || strlen($spl[1]) !== 2) {
            throw new ChessException("Invalid turn description");
        }

        $p = null;
        if (strlen($spl[0]) === 3) {
             $p = $spl[0][0];;
            if (in_array($p, $pieceSymbols)) {
                $spl[0] = substr($spl[0], 1);
            } else {
                throw new ChessException('Invalid piece symbol');
            }
        } else {
            $p = 'P';
        }

        $yFrom = ord(strtoupper($spl[0])) - 65;
        $xFrom = 8 - (int)$spl[0][1];

        $yTo = ord(strtoupper($spl[1])) - 65;
        $xTo = 8 - (int)$spl[1][1];

        $res = new ChessTurn($xFrom, $yFrom, $xTo, $yTo);
        $res->fromStr = $spl[0];
        $res->toStr = $spl[1];
        $res->fromPiece = $p;
        return $res;
    }

    private function currentPlayerKing()
    {
        return $this->isWhiteTurn ? $this->whiteKing : $this->blackKing;
    }

    private function opponentKing()
    {
        return $this->isWhiteTurn ? $this->blackKing : $this->whiteKing;
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
        $w = $this->isActive ? null : ($this->isWhiteTurn ? 'black' : 'white');
        $gameView = array('white_turn' => $this->isWhiteTurn,
            'check' => $this->isCheck, 'active' => $this->isActive,
            'winner' => $w);

        if ($withBoard) {
            $gameView['board'] = $this->board->getJson();
        }

        return $gameView;
    }
}