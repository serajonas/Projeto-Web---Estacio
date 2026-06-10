<?php

namespace App\Repositories;

use PDO;

class DescricaoRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(): string
    {
        $stmt = $this->pdo->prepare('SELECT texto FROM descricao WHERE id = 1');
        $stmt->execute();

        return (string) $stmt->fetchColumn();
    }

    public function update(string $texto): void
    {
        if (trim($texto) === '') {
            throw new \InvalidArgumentException('Descrição não pode estar vazia.');
        }

        $stmt = $this->pdo->prepare('UPDATE descricao SET texto = :texto WHERE id = 1');
        $stmt->execute([':texto' => trim($texto)]);
    }
}
