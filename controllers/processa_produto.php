<?php
session_start();
include('../config/conexao.php');

// 1. Verificação de Segurança (Admin)
if (!isset($_SESSION['tipo_acesso']) || $_SESSION['tipo_acesso'] != 'admin') {
    // Retorna um JSON para consistência ou redireciona
    die(json_encode(['status' => 'error', 'message' => 'Acesso negado']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Captura os dados básicos
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $tamanhos = $_POST['tamanhos']; 
    
    // Tratamento do Preço (Troca vírgula por ponto e garante que é numérico)
    $preco = str_replace(',', '.', $_POST['preco']); 
    $preco = floatval($preco); // Garante que o banco entenda como número

    // --- UPLOAD DA IMAGEM ---
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        
        $pasta_destino = "../assets/images/sneakers/";
        
        // Garante que a pasta existe
        if(!is_dir($pasta_destino)) {
            mkdir($pasta_destino, 0777, true);
        }

        $nome_arquivo_original = $_FILES['imagem']['name'];
        $extensao = strtolower(pathinfo($nome_arquivo_original, PATHINFO_EXTENSION));
        
        // 2. Validação de Segurança: Extensões permitidas
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($extensao, $extensoes_permitidas)) {
            
            // Gera nome único
            $novo_nome_imagem = uniqid() . "." . $extensao;
            
            $caminho_completo = $pasta_destino . $novo_nome_imagem;
            $caminho_banco = "assets/images/sneakers/" . $novo_nome_imagem;

            // Move o arquivo
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
                
                // --- 3. SALVAR NO BANCO COM PREPARED STATEMENTS ---
                // O '?' é um placeholder que será substituído pelos dados de forma segura
                $sql = "INSERT INTO produtos (nome, descricao, preco, imagem, tamanhos) VALUES (?, ?, ?, ?, ?)";
                
                $stmt = $conn->prepare($sql);
                
                if ($stmt) {
                    // "ssdds" indica os tipos: s=string, d=double(número decimal)
                    // Ordem: nome(s), descricao(s), preco(d), imagem(s), tamanhos(s)
                    $stmt->bind_param("ssdss", $nome, $descricao, $preco, $caminho_banco, $tamanhos);
                    if ($stmt->execute()) {
                        echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='../admin/admin_painel.php';</script>";
                    } else {
                        echo "<script>alert('Erro ao salvar no banco: " . $stmt->error . "'); window.history.back();</script>";
                    }
                    $stmt->close(); // Fecha a declaração
                } else {
                     echo "<script>alert('Erro na preparação do SQL: " . $conn->error . "'); window.history.back();</script>";
                }           
            } else {
                echo "<script>alert('Erro ao mover a imagem para a pasta.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Formato de arquivo inválido. Apenas JPG, PNG ou WEBP.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Selecione uma imagem válida.'); window.history.back();</script>";
    }
}
?>