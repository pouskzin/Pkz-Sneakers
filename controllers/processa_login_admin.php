<?php
session_start(); // Inicia a sessão para guardar o login
include('../config/conexao.php'); // Importa a conexão que consertamos antes

if (!isset($conn)) {
    die("Erro crítico: A variável de conexão (\$conn) não foi encontrada. Verifique se o arquivo conexao.php está correto.");
}

// Verifica se os campos foram enviados
if (isset($_POST['email']) && isset($_POST['senha'])) {
    
    // Limpa os dados para evitar injeção de SQL básica
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha']; // A senha digitada

    // Busca o admin pelo email
    $sql_code = "SELECT * FROM admins WHERE email = '$email'";
    $sql_query = $conn->query($sql_code) or die("Falha na execução do código SQL: " . $conn->error);

    // Verifica se encontrou algum admin com esse email
    if ($sql_query->num_rows == 1) {
        
        $usuario = $sql_query->fetch_assoc();

        // COMPARAÇÃO DE SENHA
        // Nota: Como no seu banco a senha foi inserida manualmente como texto puro ('admin123'),
        // vamos comparar diretamente. Em um sistema real futuro, usaríamos password_hash().
        if ($senha === $usuario['senha']) {
            
            // Login Sucesso: Criamos a sessão
            $_SESSION['id_admin'] = $usuario['id'];
            $_SESSION['email_admin'] = $usuario['email'];

            header("Location: admin_painel.php"); // Redireciona para o painel
            exit;
            
        } else {
            echo "<script>alert('Senha incorreta!'); window.location.href='login_admin.php';</script>";
        }

    } else {
        echo "<script>alert('Email não encontrado na base de administradores.'); window.location.href='login_admin.php';</script>";
    }

} else {
    // Se tentar abrir o arquivo direto sem enviar o formulário
    header("Location: login_admin.php");
}
?>