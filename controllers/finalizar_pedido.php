<?php
session_start();
include('../config/conexao.php');

// Verifica login
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Faça login para comprar!'); window.location.href='../login.html';</script>";
    exit;
}

// Verifica carrinho
if (!isset($_SESSION['carrinho']) || count($_SESSION['carrinho']) == 0) {
    echo "<script>alert('Seu carrinho está vazio.'); window.location.href='../produtos.php';</script>";
    exit;
}

$id_cliente = $_SESSION['usuario_id'];
$total_pedido = 0;
$itens_para_inserir = [];

// 1. PREPARAÇÃO: Calcula total e prepara os dados dos itens
foreach ($_SESSION['carrinho'] as $item) {
    $id_prod = intval($item['id']);
    $qtd = intval($item['quantidade']);
    $tamanho = $item['tamanho'];
    
    // Busca dados atualizados do produto (Preço e Nome)
    $sql = "SELECT nome, preco FROM produtos WHERE id = $id_prod";
    $query = $conn->query($sql);
    
    if($query->num_rows > 0) {
        $prod = $query->fetch_assoc();
        $preco_atual = $prod['preco'];
        
        $total_pedido += ($preco_atual * $qtd);
        
        // Guarda na memória para salvar depois que criarmos o pedido
        $itens_para_inserir[] = [
            'id_produto' => $id_prod,
            'nome' => $prod['nome'],
            'tamanho' => $tamanho,
            'qtd' => $qtd,
            'preco' => $preco_atual
        ];
    }
}

// 2. CRIA O PEDIDO (O Cabeçalho)
$status = "Pago - Aprovado";
$sql_pedido = "INSERT INTO pedidos (id_cliente, valor_total, status) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql_pedido);
$stmt->bind_param("ids", $id_cliente, $total_pedido, $status);

if ($stmt->execute()) {
    
    // --- O PULO DO GATO ---
    // Pegamos o ID do pedido que acabou de ser criado
    $id_novo_pedido = $conn->insert_id;
    
    // 3. SALVA OS ITENS (Os Tênis)
    $sql_item = "INSERT INTO itens_pedido (id_pedido, id_produto, nome_produto_snapshot, tamanho, quantidade, preco_unitario) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_item = $conn->prepare($sql_item);
    
    foreach ($itens_para_inserir as $item) {
        $stmt_item->bind_param(
            "iissid", 
            $id_novo_pedido, 
            $item['id_produto'], 
            $item['nome'], 
            $item['tamanho'], 
            $item['qtd'], 
            $item['preco']
        );
        $stmt_item->execute();
    }
    
    // 4. Limpa e Redireciona
    unset($_SESSION['carrinho']);
    echo "<script>alert('Compra realizada com sucesso!'); window.location.href='../perfil.php?aba=pedidos';</script>";

} else {
    echo "<script>alert('Erro ao processar pedido: " . $conn->error . "'); window.history.back();</script>";
}
?>