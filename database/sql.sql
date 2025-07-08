CREATE TABLE mensagens (
  id INT PRIMARY KEY,
  -- Usamos INT porque o PHP gera o ID
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  comentario TEXT
);