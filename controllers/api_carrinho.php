<?php

session_start();
include('../config/conexao.php');
header('Content-Type: application/json');

$response = [
    'itens' => [],
    'total' => 0,
    'total_formatado' => 'R$ 0,00',
    'vazio' => true
];

if (isset($_SESSION['carrinho']) && count($_SESSION['carrinho']) > 0) {
    
    $response['vazio'] = false;
    $total = 0;

    foreach ($_SESSION['carrinho'] as $key => $item) {
        $id = intval($item['id']);
        
        // Busca dados atualizados no banco (Preço e Nome)
        $sql = "SELECT nome, preco, imagem FROM produtos WHERE id = $id";
        $query = $conn->query($sql);
        
        if ($query && $query->num_rows > 0) {
            $prod = $query->fetch_assoc();
            
            // Corrige caminho da imagem
            $img = $prod['imagem'];
            if (strpos($img, 'assets/') === false) { $img = 'assets/' . $img; }

            // Cálculos
            $subtotal = $prod['preco'] * $item['quantidade'];
            $total += $subtotal;

            // Adiciona à lista de resposta
            $response['itens'][] = [
                'id_sessao' => $key, // Usado para remover
                'nome' => $prod['nome'],
                'imagem' => $img,
                'tamanho' => $item['tamanho'],
                'quantidade' => $item['quantidade'],
                'preco' => 'R$ ' . number_format($prod['preco'], 2, ',', '.'),
                'subtotal' => 'R$ ' . number_format($subtotal, 2, ',', '.')
            ];
        }
    }

    $response['total'] = $total;
    $response['total_formatado'] = 'R$ ' . number_format($total, 2, ',', '.');
}

echo json_encode($response);
?>