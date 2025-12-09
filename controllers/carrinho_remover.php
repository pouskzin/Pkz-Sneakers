<?php
session_start();

if (isset($_GET['item'])) {
    $item_id = $_GET['item'];
    
    // Remove o item específico da sessão
    if (isset($_SESSION['carrinho'][$item_id])) {
        unset($_SESSION['carrinho'][$item_id]);
    }
}

header("Location: ../carrinho.html");
exit;
?>