# Projeto Web Estacio 2

Este projeto é uma landing page dinâmica que consome dados de uma API PHP e armazena projetos em um banco de dados MySQL.

## Estrutura

- `index.html` — interface principal do site
- `css/style.css` — estilos de layout e cards
- `js/script.js` — consumo da API e atualização dinâmica da página
- `api.php` — entry point da API RESTful em PHP
- `conexao.php` — configuração de conexão com o banco MySQL
- `app/` — código do backend organizado em controllers, repositories e models
- `dados.php` — alias para `api.php` (compatibilidade)
- `schema.sql` — script para criar o banco e dados iniciais

## Setup

1. Crie o banco de dados MySQL e as tabelas executando `schema.sql`.
2. Ajuste `conexao.php` com as credenciais do seu servidor MySQL:

```php
$host = '127.0.0.1';
$nomeBanco = 'projeto_web_estacio';
$usuario = 'root';
$senha = '';
```

3. Abra o projeto via servidor local (XAMPP, WAMP ou PHP built-in).
4. Acesse `http://localhost/estacio2/index.html` no navegador.

## Legenda da API

### Endpoints principais

| Método | Endpoint | Descrição | Corpo JSON |
| --- | --- | --- | --- |
| GET | `/api.php` | Retorna descrição + lista de projetos | - |
| GET | `/api.php?resource=descricao` | Retorna apenas a descrição | - |
| GET | `/api.php?resource=projetos` | Retorna apenas os projetos | - |
| POST | `/api.php?resource=projetos` | Cria um novo projeto | `{ "nome": "...", "descricao": "...", "categoria": "...", "data": "..." }` |
| PUT | `/api.php?resource=descricao` | Atualiza a descrição | `{ "descricao": "..." }` |
| PUT | `/api.php?resource=projetos` | Atualiza projeto existente | `{ "id": 1, "nome": "...", "descricao": "...", "categoria": "...", "data": "..." }` |
| DELETE | `/api.php?resource=projetos&id=ID` | Remove o projeto de ID informado | - |

### Observações

- `dados.php` redireciona para `api.php` para manter compatibilidade com implementações anteriores.
- O front-end já consome a API de forma dinâmica com `fetch`.
- `api.php` utiliza `PDO` e prepared statements para segurança.

## Boas práticas aplicadas

- Separação de responsabilidades entre front-end e backend
- Organização em controllers, repositories e models para código PHP mais limpo
- Uso de JSON para comunicação entre cliente e servidor
- Validação de dados no backend
- Tratamento de erros no front-end e no backend
- Uso de event delegation para ações de exclusão

---

## Publicação

### Publicação local
- Use o XAMPP ou outro servidor PHP local.
- Coloque a pasta do projeto dentro de `C:\xampp\htdocs\estacio2`.
- Abra no navegador: `http://localhost/estacio2/index.html`.
- Nunca abra o arquivo HTML diretamente via `file://` quando o front-end depende da API PHP.

### Publicação em hospedagem PHP gratuita ou paga
- Hospedagens que suportam PHP e MySQL: 000webhost, InfinityFree, Hostinger, Locaweb, Umbler.
- Envie todos os arquivos do projeto para a pasta pública do servidor.
- Crie o banco MySQL na hospedagem e atualize `conexao.php` com os dados recebidos.
- Acesse o URL público do site e verifique `api.php` funcionando.

### Publicação em GitHub Pages
- GitHub Pages não suporta PHP diretamente.
- Para usar este projeto com PHP você precisa de hospedagem que suporte PHP/MySQL.
- Use GitHub apenas para versionar o código, e publique em um host PHP separado.

---

### Como testar a API manualmente

Exemplo de requisição `POST` usando cURL:

```bash
curl -X POST http://localhost/estacio2/api.php?resource=projetos \
  -H "Content-Type: application/json" \
  -d '{"nome":"Novo Projeto","descricao":"Descrição...","categoria":"Frontend","data":"Julho 2026"}'
```

Exemplo de requisição `DELETE`:

```bash
curl -X DELETE "http://localhost/estacio2/api.php?resource=projetos&id=1"
```
