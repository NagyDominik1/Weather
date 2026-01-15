<?php

class Database
{
    public static function getConnection(): PDO
    {
        return new PDO(
            "mysql:host=localhost;dbname=weather_app;charset=utf8mb4",
            "root",
            "",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
}
