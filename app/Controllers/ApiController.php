<?php

namespace App\Controllers;

use App\Database;
use App\Response;
use App\Repositories\DescricaoRepository;
use App\Repositories\ProjetoRepository;

class ApiController
{
    private Database $database;
    private ProjetoRepository $projetoRepository;
    private DescricaoRepository $descricaoRepository;

    public function __construct()
    {
        $this->database = new Database();
        $pdo = $this->database->getPdo();
        $this->projetoRepository = new ProjetoRepository($pdo);
        $this->descricaoRepository = new DescricaoRepository($pdo);
    }

    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            Response::json([], 204);
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $resource = $this->getResourcePath();
        $payload = $this->getJsonBody();

        try {
            match (true) {
                $method === 'GET' && $resource === 'descricao' => $this->respondDescription(),
                $method === 'GET' && $resource === 'projetos' => $this->respondProjects(),
                $method === 'GET' && ($resource === '' || $resource === null) => $this->respondAll(),
                $method === 'POST' && $resource === 'projetos' => $this->createProject($payload),
                $method === 'PUT' && $resource === 'descricao' => $this->updateDescricao($payload),
                $method === 'PUT' && $resource === 'projetos' => $this->updateProject($payload),
                $method === 'DELETE' && $resource === 'projetos' => $this->deleteProject(),
                default => Response::json(['erro' => 'Recurso ou método não suportado'], 405),
            };
        } catch (\InvalidArgumentException $error) {
            Response::json(['erro' => $error->getMessage()], 400);
        }
    }

    private function respondDescription(): void
    {
        Response::json(['descricao' => $this->descricaoRepository->get()]);
    }

    private function respondProjects(): void
    {
        Response::json(['projetos' => $this->projetoRepository->getAll()]);
    }

    private function respondAll(): void
    {
        Response::json(array_merge(
            ['descricao' => $this->descricaoRepository->get()],
            ['projetos' => $this->projetoRepository->getAll()]
        ));
    }

    private function createProject(array $payload): void
    {
        $id = $this->projetoRepository->create($payload);
        Response::json(['mensagem' => 'Projeto criado com sucesso', 'id' => $id], 201);
    }

    private function updateDescricao(array $payload): void
    {
        $this->descricaoRepository->update($payload['descricao'] ?? '');
        Response::json(['mensagem' => 'Descrição atualizada com sucesso']);
    }

    private function updateProject(array $payload): void
    {
        $id = isset($payload['id']) ? (int) $payload['id'] : 0;
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID de projeto inválido.');
        }

        $this->projetoRepository->update($id, $payload);
        Response::json(['mensagem' => 'Projeto atualizado com sucesso']);
    }

    private function deleteProject(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID de projeto inválido.');
        }

        $this->projetoRepository->delete($id);
        Response::json(['mensagem' => 'Projeto apagado com sucesso']);
    }

    private function getJsonBody(): array
    {
        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT'], true)) {
            $payload = json_decode(file_get_contents('php://input'), true);
            return is_array($payload) ? $payload : [];
        }

        return [];
    }

    private function getResourcePath(): ?string
    {
        if (!empty($_SERVER['PATH_INFO'])) {
            return trim($_SERVER['PATH_INFO'], '/');
        }

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
        $script = $_SERVER['SCRIPT_NAME'];

        if (strpos($uri, $script) === 0) {
            $route = substr($uri, strlen($script));
        } else {
            $route = $uri;
        }

        $path = trim($route, '/');

        if ($path === '' && isset($_GET['resource'])) {
            $path = trim((string) $_GET['resource'], '/');
        }

        return $path;
    }
}
