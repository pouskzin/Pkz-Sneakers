<?php
// Exibir erros para ajudar no diagnóstico (Remova após funcionar)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once(__DIR__ . '/../config/conexao.php');

// Verifica conexão
if (!isset($conn)) {
    die("Erro Crítico: Conexão com banco de dados falhou.");
}

// Verifica se dados foram enviados
if (isset($_POST['email']) && isset($_POST['senha'])) {
    
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha']; // A senha digitada pelo usuário

    // ==========================================================
    // 1. VERIFICAÇÃO DE ADMIN (Tabela 'admins')
    // ==========================================================
    $sql_admin = "SELECT * FROM admins WHERE email = '$email'";
    $query_admin = $conn->query($sql_admin);

    if ($query_admin && $query_admin->num_rows == 1) {
        $usuario_admin = $query_admin->fetch_assoc();
        
        // Admins geralmente têm senha normal ou hash? Vamos testar os dois.
        if ($senha === $usuario_admin['senha'] || password_verify($senha, $usuario_admin['senha'])) {
            $_SESSION['usuario_id'] = $usuario_admin['id'];
            $_SESSION['tipo_acesso'] = 'admin';

            $_SESSION['email_admin'] = $usuario_admin['email'];
            
            header("Location: ../admin/admin_painel.php");
            exit;
        }
    }

    // ==========================================================
    // 2. VERIFICAÇÃO DE CLIENTE (Tabela 'cadastros')
    // ==========================================================
    $sql_cliente = "SELECT * FROM cadastros WHERE email = '$email'";
    $query_cliente = $conn->query($sql_cliente);

    if (!$query_cliente) {
        die("Erro no SQL Clientes: " . $conn->error);
    }

    if ($query_cliente->num_rows == 1) {
        $cliente = $query_cliente->fetch_assoc();
        $senha_banco = $cliente['senha']; // O que está salvo no banco

        // --- LÓGICA HÍBRIDA (O PULO DO GATO) ---
        
        $login_sucesso = false;

        // TENTATIVA 1: Verifica se é uma senha CRIPTOGRAFADA (Hash)
        if (password_verify($senha, $senha_banco)) {
            $login_sucesso = true;
        }
        // TENTATIVA 2: Verifica se é uma senha TEXTO PURO (Antiga ou manual)
        elseif ($senha === $senha_banco) {
            $login_sucesso = true;
        }

        if ($login_sucesso) {
            $_SESSION['usuario_id'] = $cliente['id'];
            $_SESSION['nome'] = $cliente['nome'];
            $_SESSION['tipo_acesso'] = 'cliente';

            header("Location: ../index.html");
            exit;
        } else {
            // Cliente existe, mas senha não bate nem com hash nem texto puro
            echo "<script>alert('Senha incorreta!'); window.history.back();</script>";
            exit;
        }
    }

    // ==========================================================
    // 3. NÃO ACHOU NINGUÉM
    // ==========================================================
    echo "<script>alert('E-mail não cadastrado.'); window.history.back();</script>";
    exit;

} else {
    header("Location: ../index.html");
    exit;
}
?>