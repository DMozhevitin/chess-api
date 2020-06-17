## The easiest way to start
* clone this repository
* open it with *PhpStorm*
* set up your `mysql` database settings in *Database.php*
* create `PHP Built-in Web Server` run configuration.
* run it with *PhpStorm*

## Api
* `POST /game/start` - starts new game
* `POST /game/turn?turn=e2-e4` - make turn
* `GET /game/status` -  `JSON` info about the current state of game. Example: 
```
{
    "white_turn": true,
    "check": false,
    "active": true,
    "board": [
        [
            {
                "color": "black",
                "piece": "R"
            },
        ...
       ]
    ]
}
```

## Piece symbols:
* `K` - King
* `Q` - Queen
* `R` - Rook
* `N` - Knight
* `B` - Bishop
* `P` - Pawn
    
