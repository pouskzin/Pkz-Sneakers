<?php
session_start();

// Verifica se os dados necessários chegaram
if (isset($_POST['id_produto']) && isset($_POST['tamanho'])) {
    
    $id = intval($_POST['id_produto']);
    $tamanho = $_POST['tamanho'];
    
    // Cria o array do carrinho se não existir
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    // Cria um identificador único (ID + Tamanho)
    $item_id = $id . '_' . $tamanho;

    // Se já existe, aumenta a quantidade. Se não, cria.
    if (isset($_SESSION['carrinho'][$item_id])) {
        $_SESSION['carrinho'][$item_id]['quantidade']++;
    } else {
        $_SESSION['carrinho'][$item_id] = [
            'id' => $id,
            'tamanho' => $tamanho,
            'quantidade' => 1
        ];
    }

    // SUCESSO: Manda para a tela do carrinho HTML
    header("Location: ../carrinho.html");
    exit;

} else {
    // ERRO: Faltou tamanho
    echo "<script>alert('Por favor, escolha um tamanho.'); window.history.back();</script>";
    exit;
}
?>