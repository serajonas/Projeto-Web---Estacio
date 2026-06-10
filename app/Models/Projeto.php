<?php

namespace App\Models;

class Projeto
{
    public int $id;
    public string $nome;
    public string $descricao;
    public string $categoria;
    public string $data_projeto;

    public function __construct(array $data)
    {
        $this->id = (int) ($data['id'] ?? 0);
        $this->nome = trim((string) ($data['nome'] ?? ''));
        $this->descricao = trim((string) ($data['descricao'] ?? ''));
        $this->categoria = trim((string) ($data['categoria'] ?? ''));
        $this->data_projeto = trim((string) ($data['data_projeto'] ?? $data['data'] ?? ''));
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'categoria' => $this->categoria,
            'data_projeto' => $this->data_projeto,
        ];
    }
}
