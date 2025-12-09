<?php
session_start();
include('../config/conexao.php');

// VERIFICAÇÃO DE SEGURANÇA RIGOROSA
// 1. Verifica se existe sessão
// 2. Verifica se o tipo de acesso é EXATAMENTE 'admin'
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['tipo_acesso']) || $_SESSION['tipo_acesso'] != 'admin') {
    
    // Se falhar em qualquer um desses, destrói tudo e chuta para o login
    session_destroy();
    header("Location: ../login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - Pkz Sneakers</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        header { background: #333; color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
        .menu-container { display: flex; gap: 20px; margin-top: 50px; flex-wrap: wrap; }
        .card { 
            border: 1px solid #ccc; 
            padding: 20px; 
            width: 250px; 
            text-align: center; 
            border-radius: 8px; 
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .card:hover { transform: translateY(-5px); box-shadow: 4px 4px 10px rgba(0,0,0,0.2); }
        .card h3 { margin-top: 0; }
        .btn { display: inline-block; text-decoration: none; background: #007bff; color: white; padding: 10px 15px; border-radius: 5px; margin-top: 10px; }
        .btn-logout { background: #dc3545; color: white; text-decoration: none; padding: 8px 12px; border-radius: 4px; }
    </style>
</head>
<body>

    <header>
        <h1>Painel Admin - Pkz Sneakers</h1>
        <div>
            Olá, <?php echo $_SESSION['email_admin']; ?> | 
            <a href="../controllers/logout_admin.php" class="btn-logout">Sair</a>
        </div>
    </header>

    <div class="menu-container">
        
        <div class="card">
            <h3>Produtos</h3>
            <p>Cadastrar novos produtos.</p>
            <a href="admin_cadastro_produto.php" class="btn">Gerenciar Produtos</a>
        </div>

        <div class="card">
            <h3>Usuários</h3>
            <p>Ver quem está cadastrado no site.</p>
            <a href="admin_usuarios.php" class="btn">Gerenciar Logins</a>
        </div>

        <div class="card">
            <h3>Pedidos</h3>
            <p>Acompanhar status das vendas.</p>
            <a href="admin_pedidos.php" class="btn">Ver Pedidos</a>
        </div>

        <div class="card">
            <h3>Estoque</h3>
            <p>Cadastrar, editar ou remover sneakers.</p>
            <a href="admin_produtos.php" class="btn">Gerenciar Estoque</a>
        </div>

    </div>

</body>
</html>