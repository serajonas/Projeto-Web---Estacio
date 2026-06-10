<?php

namespace App\Repositories;

use PDO;
use App\Models\Projeto;

class ProjetoRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT id, nome, descricao, categoria, data_projeto FROM projetos ORDER BY criado_em DESC');
        return array_map(fn(array $row) => (new Projeto($row))->toArray(), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function create(array $data): int
    {
        $projeto = new Projeto($data);
        $this->validate($projeto);

        $stmt = $this->pdo->prepare('INSERT INTO projetos (nome, descricao, categoria, data_projeto) VALUES (:nome, :descricao, :categoria, :data_projeto)');
        $stmt->execute([
            ':nome' => $projeto->nome,
            ':descricao' => $projeto->descricao,
            ':categoria' => $projeto->categoria,
            ':data_projeto' => $projeto->data_projeto,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $projeto = new Projeto(array_merge($data, ['id' => $id]));
        $this->validate($projeto);

        $stmt = $this->pdo->prepare('UPDATE projetos SET nome = :nome, descricao = :descricao, categoria = :categoria, data_projeto = :data_projeto WHERE id = :id');
        $stmt->execute([
            ':nome' => $projeto->nome,
            ':descricao' => $projeto->descricao,
            ':categoria' => $projeto->categoria,
            ':data_projeto' => $projeto->data_projeto,
            ':id' => $projeto->id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM projetos WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    private function validate(Projeto $projeto): void
    {
        if ($projeto->nome === '' || $projeto->descricao === '' || $projeto->categoria === '' || $projeto->data_projeto === '') {
            throw new \InvalidArgumentException('Todos os campos do projeto são obrigatórios.');
        }
    }
}
