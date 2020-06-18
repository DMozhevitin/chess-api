## The easiest way to start
* clone this repository
* open it with *PhpStorm*
* set up your `mysql` database settings in *Database.php*
* run `scripts/create_tables.sql` script
* create `PHP Built-in Web Server` run configuration.
* run it with *PhpStorm*

## Api
* `POST /game/start` - starts new game
* `POST /game/turn?turn=Ng1-f3` - make turn. Turn description must be similar to [long algebraic notation](https://en.wikipedia.org/wiki/Chess_notation). Small difference: even when attacking opponent's piece put `-`, not `x` between cells.
* `GET /game/status` -  `JSON` info about the current state of game. Example: 
```
{
    "white_turn": true,
    "check": false,
    "active": true,
    "winner": "white",
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
    
