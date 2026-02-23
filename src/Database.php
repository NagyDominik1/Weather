<?php

class Database
{
    public static function getConnection(): PDO
    {
        if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
            // HELYI ADATOK (XAMPP)
            $host = 'localhost';
            $db   = 'weather_app';
            $user = 'root';
            $pass = '';
        } else {
            // EGYETEMI ADATOK (Virtualmin)
            $host = 'localhost';
            $db   = 'ee'; // Írd ide az egyetemi DB nevet!
            $user = 'ee'; // Írd ide az egyetemi felhasználót!
            $pass = '2Sh4ttzzQBsGWul'; // Írd ide az egyetemi jelszót!
        }

        return new PDO(
            "mysql:host=$host;dbname=$db;charset=utf8mb4",
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
}
