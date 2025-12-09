<?php
session_start();
include '../config/conexao.php'; // Usa a conexão que criámos no Passo 1

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['password']; // O nome no teu HTML é "password"

    // Prepara a busca segura no banco
    $stmt = $conn->prepare("SELECT id, nome, senha FROM cadastros WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        
        // Verifica a senha criptografada (compatível com o teu cadastro.php)
        if (password_verify($senha, $usuario['senha'])) {
            // Login Sucesso!
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            
            // Redireciona para a home ou painel
            header("Location: index.html"); 
            exit;
        } else {
            // Senha errada - Redireciona de volta com erro
            echo "<script>alert('Senha incorreta!'); window.location.href='login.html';</script>";
        }
    } else {
        // Email não encontrado
        echo "<script>alert('Usuário não encontrado!'); window.location.href='login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>