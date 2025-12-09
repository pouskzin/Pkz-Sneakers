<?php
// enviar-contato.php
session_start();
include '../config/conexao.php'; // Usa a conexão padrão do projeto

// Responde apenas JSON (para o AJAX do jQuery funcionar bem)
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Verifica se a conexão existe (segurança)
    if (!isset($conn)) {
        echo json_encode(['status' => 'error', 'message' => 'Erro de conexão com banco de dados.']);
        exit;
    }

    $nome = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['phone'] ?? '';
    $mensagem = $_POST['message'] ?? '';

    // Previne SQL Injection
    $nome = $conn->real_escape_string($nome);
    $email = $conn->real_escape_string($email);
    $telefone = $conn->real_escape_string($telefone);
    $mensagem = $conn->real_escape_string($mensagem);

    // Insere na tabela nova com status 0 (Pendente)
    $sql = "INSERT INTO mensagens_contato (nome, email, telefone, mensagem, status_envio) 
            VALUES ('$nome', '$email', '$telefone', '$mensagem', 0)";

    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Mensagem salva com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar no banco: ' . $conn->error]);
    }
    
    // Não fechamos a conexão aqui se outros scripts precisarem, mas é boa prática em scripts únicos
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido.']);
}
?>