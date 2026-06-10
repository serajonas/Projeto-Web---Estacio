<?php

namespace App\Models;

class Descricao
{
    public string $texto;

    public function __construct(string $texto)
    {
        $this->texto = trim($texto);
    }

    public function toArray(): array
    {
        return ['descricao' => $this->texto];
    }
}
