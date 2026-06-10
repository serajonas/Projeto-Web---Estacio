CREATE DATABASE IF NOT EXISTS projeto_web_estacio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE projeto_web_estacio;

CREATE TABLE IF NOT EXISTS descricao (
  id TINYINT PRIMARY KEY DEFAULT 1,
  texto TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS projetos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  descricao TEXT NOT NULL,
  categoria VARCHAR(100) NOT NULL,
  data_projeto VARCHAR(80) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO descricao (id, texto)
VALUES (1, 'Desenvolvedor Web FullStack | Especialista em PHP, JavaScript e MySQL')
ON DUPLICATE KEY UPDATE texto = VALUES(texto);

INSERT INTO projetos (nome, descricao, categoria, data_projeto) VALUES
('Sistema de Vendas', 'Projeto completo em PHP e MySQL com painel administrativo, relatórios e integração de pagamento', 'Backend', 'Junho 2024'),
('Portfólio Responsivo', 'Website moderno responsivo utilizando HTML5, CSS3 e JavaScript vanilla com animações suaves', 'Frontend', 'Maio 2024'),
('Sistema ERP', 'Integração web para gerenciamento de recursos empresariais com API RESTful', 'FullStack', 'Abril 2024'),
('App de Tarefas', 'Aplicação web para gerenciar tarefas com localStorage e interface intuitiva', 'Frontend', 'Março 2024'),
('Dashboard Analytics', 'Painel de análise de dados com gráficos interativos usando Chart.js', 'FullStack', 'Fevereiro 2024');

