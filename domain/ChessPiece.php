<?php


interface ChessPiece
{

    function validateTurn($toX, $toY, ChessBoard $board);

    function isWhite();

    function str();

    function getX();

    function getY();

    function setX($x);

    function setY($y);

    function getColor();

    function getId();
}