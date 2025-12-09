<?php
// Inicia a sessão para ler as variáveis guardadas no login
session_start();

// Diz ao navegador que a resposta é um JSON (dados), não um site
header('Content-Type: application/json');

// Verifica se existe sessão E se é um cliente
if (isset($_SESSION['usuario_id']) && isset($_SESSION['tipo_acesso']) && $_SESSION['tipo_acesso'] == 'cliente') {
    // Retorna VERDADEIRO
    echo json_encode([
        'logado' => true, 
        'nome' => $_SESSION['nome'] ?? 'Cliente' // Usa 'Cliente' se não tiver nome
    ]);
} else {
    // Retorna FALSO
    echo json_encode([
        'logado' => false
    ]);
}
?>