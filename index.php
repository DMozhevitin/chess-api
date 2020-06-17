<?php
require 'vendor/autoload.php';
include_once 'Database.php';
use FastRoute\Dispatcher;

foreach (glob("domain/*.php") as $filename) {
    include_once $filename;
}

foreach (glob("dao/*.php") as $filename) {
    include_once $filename;
}

$database = new Database();
$conn = $database->getConnection();
$gameDao = new GameDao($conn);
$pieceDao = new PieceDao($conn);

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('POST', '/game/start', 'newGame');
    $r->addRoute('POST', '/game/turn', 'turn');
    $r->addRoute('GET', '/game/status', 'status');
});

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);
$routeInfo = $dispatcher->dispatch($method, $uri);

switch ($routeInfo[0]) {
    case Dispatcher::FOUND:
    {
        header('Content-type: application/json');
        $handler = $routeInfo[1];
        switch ($handler) {
            case 'turn':
            {
                $handler($_REQUEST["turn"]);
                break;
            }

            case 'newGame':
            {
                $handler();
                break;
            }

            case 'status':
            {
                $handler();
            }
        }

        break;
    }

    case Dispatcher::NOT_FOUND:
    {
        http_response_code(404);
    }
}

function newGame()
{
    global $gameDao;
    global $pieceDao;

    $game = ChessGame::newGame();

    $pieceDao->deleteAll();
    $gameDao->deleteAll();

    $gameDao->save($game);
    $board = $game->getBoard();

    for ($i = 0; $i < 8; $i++)
    {
        for ($j = 0; $j < 8; $j++)
        {
            $piece = $board->get($i, $j);
            if ($piece)
            {
                $pieceDao->save($piece);
            }
        }
    }
};

function turn($turn)
{
    global $pieceDao;
    global $gameDao;

    $game = $gameDao->find();
    $pieces = $pieceDao->findAll();

    for ($i = 0; $i < count($pieces); $i++)
    {
        $piece = $pieces[$i];
        $game->getBoard()->set($piece->getX(), $piece->getY(), $piece);

        if ($piece instanceof King)
        {
            if ($piece->isWhite())
            {
                $game->setWhiteKing($piece);
            } else
            {
                $game->setBlackKing($piece);
            }
        }
    }

    try {
        $game->makeTurn($turn);
    } catch (ChessException $e) {
        http_response_code(400);
        echo json_encode((object) Array('error' => $e->getMessage()));
        return;
    } catch (Exception $e) {
        http_response_code(500);
        return;
    }

    $gameDao->update($game);

    for ($i = 0; $i < 8; $i++)
    {
        for ($j = 0; $j < 8; $j++)
        {
            $piece = $game->getBoard()->get($i, $j);
            if ($piece)
            {
                $pieceDao->update($piece);
            }
        }
    }
};

function status()
{
    global $gameDao;
    global $pieceDao;

    $game = $gameDao->find();
    $pieces = $pieceDao->findAll();

    for ($i = 0; $i < count($pieces); $i++)
    {
        $piece = $pieces[$i];
        $game->getBoard()->set($piece->getX(), $piece->getY(), $piece);

        if ($piece instanceof King)
        {
            if ($piece->isWhite())
            {
                $game->setWhiteKing($piece);
            } else
            {
                $game->setBlackKing($piece);
            }
        }
    }

    print_r(json_encode($game->getJson(true)));
};

