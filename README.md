#Sistema de Gerenciamento de Publicações Acadêmicas  
CRUD completo em **PHP + PDO + MySQL**, desenvolvido para controle de **Autores, Convidados, Divulgações e Publicações**.

Este projeto foi criado como parte de um estudo de desenvolvimento web utilizando PHP com boas práticas, separação de responsabilidades e banco de dados relacional.

---

##Tecnologias Utilizadas
- **PHP 7/8**  
- **PDO (PHP Data Objects)**  
- **MySQL / MariaDB**  
- **HTML + CSS + JavaScript**  
- **Fetch API** (Carregamento dinâmico de páginas)  

---

##Estrutura do Projeto
/projeto/
│── autor.php
│── convidado.php
│── divulgacao.php
│── publicacao.php
│── conexao.php
│── index.php
│── bd_publicacoes.sql
---

##Funcionalidades

### ✔ CRUD de Autor
- Cadastro, edição, exclusão
- Seleção de classificação
- Relacionamento com publicações

### ✔ CRUD de Convidado
- Cadastro, edição, exclusão
- Associação com divulgações

### ✔ CRUD de Divulgação
- Define eventos
- Associa convidado
- Relacionada às publicações

### ✔ CRUD de Publicação
- Título, resumo, data
- Autor + Divulgação + Tipo de Publicação
- Tabela relacional completa

---

## 🛢 Banco de Dados

O arquivo `banco.sql` contém:
- Criação de todas as tabelas
- PK e FK configuradas
- Inserts de exemplo (opcional)

---

## ▶ Como Rodar o Projeto

1. Clone o repositório:
```bash
git clone https://github.com/SEU-USUARIO/NOME-DO-REPO.git

Importe o banco:

import banco.sql no PHPMyAdmin ou MySQL Workbench


Configure o arquivo conexao.php:

$pdo = new PDO("mysql:host=localhost;dbname=crud_eventos;charset=utf8", "root", "");


Inicie o servidor PHP:

php -S localhost:8000


Acesse no navegador:

http://localhost:8000/index.php



