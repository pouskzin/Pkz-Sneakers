<?php
session_start();
// AJUSTE: Caminho da conexão (sai de controllers, entra em config)
include('../config/conexao.php');

// Só admin pode excluir
if (!isset($_SESSION['tipo_acesso']) || $_SESSION['tipo_acesso'] != 'admin') {
    die("Acesso negado.");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 1. Busca a imagem para deletar o arquivo físico
    $sql_busca = "SELECT imagem FROM produtos WHERE id = $id";
    $query = $conn->query($sql_busca);
    
    if ($query->num_rows > 0) {
        $produto = $query->fetch_assoc();
        
        $caminho_banco = $produto['imagem']; // Ex: images/sneakers/foto.jpg
        
        // Tenta achar o arquivo para apagar. 
        // Como estamos na pasta 'controllers', precisamos voltar (..) e entrar em 'assets'
        
        // Tenta caminho novo (assets/images...)
        $caminho_fisico = "../assets/" . str_replace('assets/', '', $caminho_banco);
        
        // Se não achar, tenta o caminho antigo (legacy)
        if (!file_exists($caminho_fisico)) {
             $caminho_fisico = "../" . $caminho_banco;
        }

        // Apaga a foto da pasta
        if (file_exists($caminho_fisico)) {
            unlink($caminho_fisico);
        }

        // 2. Apaga do Banco de Dados
        $sql_delete = "DELETE FROM produtos WHERE id = $id";
        if ($conn->query($sql_delete)) {
            // AJUSTE: Redireciona de volta para a pasta admin
            echo "<script>alert('Produto excluído!'); window.location.href='../admin/admin_produtos.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir do banco.'); window.history.back();</script>";
        }
    }
} else {
    // Se acessar direto sem ID
    header("Location: ../admin/admin_produtos.php");
}
?>