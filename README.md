# 👟 Pkz Sneakers - E-commerce Streetwear

![Status](https://img.shields.io/badge/Status-Finalizado-success)
![PHP](https://img.shields.io/badge/Backend-PHP%208-blue)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange)
![Bootstrap](https://img.shields.io/badge/Frontend-Bootstrap%205-purple)

## 📄 Sobre o Projeto

O **Pkz Sneakers** é uma plataforma de comércio eletrônico desenvolvida do zero como Trabalho de Conclusão de Curso (TCC) para o curso Técnico em Informática do IFPR - Campus Jacarezinho. 

O objetivo foi criar uma loja virtual completa "do zero" (sem uso de frameworks pesados no backend), focando na implementação da arquitetura **MVC (Model-View-Controller)**, segurança de dados e experiência do usuário.

O sistema conta com um diferencial de integração com **Discord**, enviando notificações automáticas para a equipe administrativa sempre que uma venda é realizada.

---

## 🚀 Funcionalidades

### 👤 Área do Cliente
- **Autenticação Segura:** Login e Cadastro com criptografia de senha (Bcrypt).
- **Catálogo Dinâmico:** Visualização de produtos vindos do banco de dados.
- **Carrinho de Compras:** Gestão de itens via Sessão PHP (`$_SESSION`) sem perder dados ao navegar.
- **Perfil do Usuário:** Dashboard para visualizar dados pessoais e histórico de pedidos.
- **Checkout:** Simulação de finalização de compra com persistência em banco relacional.

### 🛡️ Área Administrativa
- **Gestão de Produtos:** CRUD completo (Criar, Ler, Atualizar, Deletar).
- **Upload de Imagens:** Sistema seguro de envio de fotos para o servidor.
- **Proteção:** Rotas protegidas que impedem acesso direto sem login de admin.

### 🤖 Automação (Diferencial)
- **Bot Discord:** Integração via Webhook que notifica o canal da administração em tempo real sobre novos pedidos e cadastros.

---

## 🛠️ Tecnologias Utilizadas

- **Back-end:** PHP (Puro/Nativo)
- **Front-end:** HTML5, CSS3, JavaScript (AJAX/Fetch API), Bootstrap 5
- **Banco de Dados:** MySQL / MariaDB
- **Versionamento:** Git & GitHub
- **Arquitetura:** MVC (Model-View-Controller)

---

## 📂 Estrutura do Projeto

O projeto segue estritamente o padrão MVC para organização e escalabilidade:

```text
Pkz-Sneakers/
│
├── admin/                 # Visões do Painel Administrativo
├── assets/                # Arquivos estáticos (CSS, JS, Imagens)
├── config/                # Conexão com o Banco de Dados
├── controllers/           # Lógica de Negócio (Login, Carrinho, Pedidos)
│
├── index.html             # Página Inicial
├── produtos.php           # Catálogo
├── perfil.php             # Área do Cliente
├── carrinho.html          # Frontend do Carrinho
│
└── README.md              # Documentação
```

## 🚀 Instalação e Configuração

Siga os passos abaixo para rodar o projeto na sua máquina local.

### 📋 Pré-requisitos
Antes de começar, você vai precisar ter instalado:
* [XAMPP](https://www.apachefriends.org/pt_br/index.html) (Apache + MySQL)
* [Git](https://git-scm.com/downloads)
* [Node.js](https://nodejs.org/pt). Baixar a biblioteca discord.js para rodar o bot.

---

### 🔧 Passo a Passo

- **1. Clone o repositório**
Abra o seu terminal (Git Bash ou CMD) e digite:
```bash
git clone [https://github.com/pouskzin/Pkz-Sneakers.git](https://github.com/pouskzin/Pkz-Sneakers.git)
```
- **2. Configure o Banco de Dados**

Abra o phpMyAdmin acessando http://localhost/phpmyadmin.
Crie um novo banco de dados chamado: pkzsneakers
Clique na aba SQL e cole o script de criação das tabelas (disponível na seção "Modelagem" abaixo).

- **3. Configure a Conexão**
Vá até a pasta do projeto e abra o arquivo: config/conexao.php
Verifique se o usuário e senha batem com o seu XAMPP (Geralmente usuário root e senha vazia).

- **4. Acesse o Projeto Tudo pronto! Abra o navegador e acesse:**
http://localhost/Pkz-Sneakers

## 🗄️ Modelagem do Banco de Dados (Resumo)
Para rodar o projeto, execute estes comandos SQL no seu banco:

```SQL
-- Tabela de Usuários
CREATE TABLE cadastros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Contatos
CREATE TABLE mensagens_contato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    mensagem TEXT NOT NULL,
    status_envio TINYINT(1) DEFAULT 0, -- 0 = Pendente, 1 = Enviado
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Produtos
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    descricao TEXT,
    preco DECIMAL(10,2),
    tamanhos VARCHAR(50),
    imagem VARCHAR(255)
);

-- Tabela de Pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    valor_total DECIMAL(10,2),
    status VARCHAR(50),
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES cadastros(id)
);

-- Tabela de Itens do Pedido
CREATE TABLE itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_produto INT,
    nome_produto_snapshot VARCHAR(255),
    tamanho VARCHAR(10),
    quantidade INT,
    preco_unitario DECIMAL(10,2),
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id)
);


```
## 👨‍💻 Autor

<img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/u/SEU_ID_GITHUB?v=4" width="100px;" alt=""/>
<br />
<sub><b>Kaio Augusto</b></sub>
<br />

Entre em contato:
- 💼 [LinkedIn](https://www.linkedin.com/in/kaio-augusto-de-abreu-freire-27609121a/)
- ✉️ [Email](mailto:kaioaugustofreire@gmail.com)
