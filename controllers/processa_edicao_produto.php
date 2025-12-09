<?php
session_start();
include('../config/conexao.php');

// 1. Verificação de Segurança
if (!isset($_SESSION['tipo_acesso']) || $_SESSION['tipo_acesso'] != 'admin') {
    die(json_encode(['status' => 'error', 'message' => 'Acesso negado']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $tamanhos = $_POST['tamanhos'];
    
    // Tratamento de preço
    $preco = str_replace(',', '.', $_POST['preco']);
    $preco = floatval($preco);

    // --- LÓGICA DA IMAGEM ---
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        
        $pasta_destino = "../assets/images/sneakers/";
        $nome_arquivo_original = $_FILES['imagem']['name'];
        $extensao = strtolower(pathinfo($nome_arquivo_original, PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        // Valida extensão
        if (in_array($extensao, $extensoes_permitidas)) {
            
            // --- 1. Apagar a imagem antiga (Tua lógica excelente!) ---
            // Primeiro, buscamos qual é a imagem atual
            $sql_busca = "SELECT imagem FROM produtos WHERE id = ?";
            $stmt_busca = $conn->prepare($sql_busca);
            $stmt_busca->bind_param("i", $id);
            $stmt_busca->execute();
            $result_busca = $stmt_busca->get_result();
            $produto_antigo = $result_busca->fetch_assoc();
            
            // Se existir imagem antiga e arquivo físico, deleta
            if ($produto_antigo['imagem']) {
                $arquivo_antigo = "../" . $produto_antigo['imagem']; // Ajusta caminho para o sistema de arquivos
                if (file_exists($arquivo_antigo)) {
                    unlink($arquivo_antigo);
                }
            }
            $stmt_busca->close();

            // --- 2. Upload da Nova ---
            $novo_nome_imagem = uniqid() . "." . $extensao;
            $caminho_fisico = $pasta_destino . $novo_nome_imagem;
            
            // O PULO DO GATO: Salva no banco SEM "../" para funcionar na loja
            $caminho_banco = "assets/images/sneakers/" . $novo_nome_imagem;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_fisico)) {
                
                // SQL COM ATUALIZAÇÃO DE IMAGEM
                $sql = "UPDATE produtos SET nome=?, descricao=?, preco=?, tamanhos=?, imagem=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                // "ssdssi" -> string, string, double, string, string, integer
                $stmt->bind_param("ssdssi", $nome, $descricao, $preco, $tamanhos, $caminho_banco, $id);

            } else {
                echo "<script>alert('Erro ao mover a nova imagem.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Formato de imagem inválido.'); window.history.back();</script>";
            exit;
        }

    } else {
        // --- CASO SEM MUDANÇA DE IMAGEM ---
        $sql = "UPDATE produtos SET nome=?, descricao=?, preco=?, tamanhos=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        // "ssdsi" -> string, string, double, string, integer
        $stmt->bind_param("ssdsi", $nome, $descricao, $preco, $tamanhos, $id);
    }

    // --- EXECUÇÃO FINAL ---
    if ($stmt->execute()) {
        // Verifica se o nome do arquivo da lista é 'admin_produtos.php' ou 'produtos.php' e ajusta aqui se necessário
        echo "<script>alert('Produto atualizado com sucesso!'); window.location.href='../admin/admin_painel.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar: " . $stmt->error . "'); window.history.back();</script>";
    }
    
    $stmt->close();
}
?>