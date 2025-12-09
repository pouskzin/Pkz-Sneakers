<?php
session_start();
// AJUSTE 1: Caminho da conexão
include('../config/conexao.php');

// AJUSTE 2: Caminho do login
if (!isset($_SESSION['tipo_acesso']) || $_SESSION['tipo_acesso'] != 'admin') {
    header("Location: ../login.html");
    exit;
}

// Verifica se veio o ID
if (!isset($_GET['id'])) {
    header("Location: produtos.php"); // AJUSTE 3: Nome do arquivo da lista
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM produtos WHERE id = $id";
$query = $conn->query($sql);

if ($query->num_rows == 0) {
    die("Produto não encontrado.");
}

$produto = $query->fetch_assoc();

// AJUSTE 4: Lógica para exibir a imagem corretamente estando na pasta admin
$img = $produto['imagem'];
if (strpos($img, 'assets/') === false) { 
    $img = '../assets/' . $img; // Caminho antigo
} else { 
    $img = '../' . $img; // Caminho novo (ex: ../assets/images/...)
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f4; padding: 20px; }
        .form-container { background: white; padding: 30px; border-radius: 10px; max-width: 800px; margin: 0 auto; }
        .img-preview { width: 150px; border-radius: 5px; margin-bottom: 10px; border: 1px solid #ddd; padding: 5px; }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Editar Sneaker</h2>
        <a href="produtos.php" class="btn btn-secondary">Cancelar</a>
    </div>

    <div class="form-container">
        <form action="../controllers/processa_edicao_produto.php" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nome do Produto</label>
                    <input type="text" name="nome" class="form-control" required value="<?php echo $produto['nome']; ?>">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Preço (R$)</label>
                    <input type="text" name="preco" class="form-control" required value="<?php echo str_replace('.', ',', $produto['preco']); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" rows="3"><?php echo $produto['descricao']; ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Tamanhos</label>
                <input type="text" name="tamanhos" class="form-control" value="<?php echo $produto['tamanhos']; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Imagem Atual</label><br>
                <img src="<?php echo $img; ?>" class="img-preview">
                <br>
                <label class="form-label text-muted mt-2">Alterar Imagem (Deixe vazio para manter a atual)</label>
                <input type="file" name="imagem" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">Salvar Alterações</button>
        </form>
    </div>
</div>

</body>
</html>