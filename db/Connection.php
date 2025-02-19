<?php


namespace db;

use Core\Response;
use PDO;
use PDOException;

class Connection
{
    /** @var PDO */
    private static PDO $connection;

    /**
     * @param array $config
     */
    public static function init(array $config = []): void
    {
        try {
            self::$connection = new PDO($config['dsn'], $config['username'], $config['password']);
        } catch (PDOException $е) {
            die(' Невозможно установить соединение с базой данных ');
        }
    }

    public static function getInstance(): PDO
    {
        return self::$connection;
    }
}
