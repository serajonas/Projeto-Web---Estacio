<?php

namespace App;

use PDO;
use PDOException;

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        require_once __DIR__ . '/../conexao.php';

        $config = getDatabaseConfig();
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config['host'], $config['database'], $config['charset']);

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['charset']}"
            ]);
        } catch (PDOException $error) {
            Response::json(['erro' => 'Erro ao conectar ao banco de dados: ' . $error->getMessage()], 500);
        }
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
