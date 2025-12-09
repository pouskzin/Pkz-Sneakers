<?php
session_start();
// AJUSTE: Caminho da conexão (sai de admin, entra em config)
include('../config/conexao.php');

// Segurança
if (!isset($_SESSION['tipo_acesso']) || $_SESSION['tipo_acesso'] != 'admin') {
    header("Location: ../login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos - Admin</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f4f4f4; padding: 20px; }
        .painel-container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        .img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
        .btn-action { margin-right: 5px; }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gerenciar Estoque</h2>
        <div>
            <a href="../admin/admin_painel.php" class="btn btn-secondary">Voltar</a>
            <a href="../admin/admin_cadastro_produto.php" class="btn btn-success">+ Novo Produto</a>
        </div>
    </div>

    <div class="painel-container">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM produtos ORDER BY id DESC";
                $query = $conn->query($sql);

                if ($query->num_rows > 0) {
                    while ($prod = $query->fetch_assoc()) {
                        // Ajuste visual da imagem
                        $img = $prod['imagem'];
                        if (strpos($img, 'assets/') === false) { $img = '../assets/' . $img; } 
                        else { $img = '../' . $img; }
                ?>
                    <tr>
                        <td>#<?php echo $prod['id']; ?></td>
                        <td><img src="<?php echo $img; ?>" class="img-thumb"></td>
                        <td><?php echo $prod['nome']; ?></td>
                        <td>R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></td>
                        <td>
                            <a href="../controllers/excluir_produto.php?id=<?php echo $prod['id']; ?>" 
                               class="btn btn-danger btn-sm btn-action"
                               onclick="return confirm('Tem certeza que deseja apagar este produto?');">
                                <i class="fa-solid fa-trash"></i> Excluir
                            </a>
                            
                            <a href="../admin/admin_editar_produto.php?id=<?php echo $prod['id']; ?>" class="btn btn-primary btn-sm btn-action">
                                <i class="fa-solid fa-pen"></i> Editar
                            </a>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Nenhum produto encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>