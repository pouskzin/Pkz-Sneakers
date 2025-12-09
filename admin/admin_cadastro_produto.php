<?php
session_start();
// Segurança: Só admin entra
if (!isset($_SESSION['tipo_acesso']) || $_SESSION['tipo_acesso'] != 'admin') {
    header("Location: ../login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Produto - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f4f4f4; padding: 20px; }
        .form-container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto; }
        .preview-img { max-width: 200px; margin-top: 10px; border-radius: 8px; display: none; border: 2px solid #ddd; }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Cadastrar Novo Sneaker</h2>
        <a href="admin_painel.php" class="btn btn-secondary">Voltar ao Painel</a>
    </div>

    <div class="form-container">
        <form action="../controllers/processa_produto.php" method="POST" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nome do Produto</label>
                    <input type="text" name="nome" class="form-control" required placeholder="Ex: Nike Dunk Low">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Preço (R$)</label>
                    <input type="text" name="preco" class="form-control" required placeholder="Ex: 350,00">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" rows="3" placeholder="Detalhes do tênis..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Tamanhos Disponíveis (Separe por vírgula)</label>
                <input type="text" name="tamanhos" class="form-control" value="38,39,40,41,42,43" placeholder="38,39,40...">
            </div>

            <div class="mb-3">
                <label class="form-label">Foto do Produto</label>
                <input type="file" name="imagem" id="inputImagem" class="form-control" accept="image/*" required>
                <img id="preview" class="preview-img">
            </div>

            <button type="submit" class="btn btn-dark w-100 py-2">Salvar Produto</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('inputImagem').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('preview');
                img.src = e.target.result;
                img.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>

</body>
</html>