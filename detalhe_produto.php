<?php
session_start();
include('config/conexao.php');

if (!isset($_GET['id'])) {
    header("Location: produtos.php");
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM produtos WHERE id = $id";
$query = $conn->query($sql);

if ($query->num_rows == 0) {
    echo "Produto não encontrado.";
    exit;
}

$produto = $query->fetch_assoc();
$lista_tamanhos = explode(',', $produto['tamanhos']);

// --- CORREÇÃO DA IMAGEM ---
$img_corrigida = $produto['imagem'];
if (strpos($img_corrigida, 'assets/') === false) {
    $img_corrigida = 'assets/' . $img_corrigida;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $produto['nome']; ?> - Pkz Sneakers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css"> <style>
        .sizes { display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .sizes label { position: relative; cursor: pointer; }
        .sizes input[type="radio"] { display: none; }
        .sizes span {
            display: inline-block; padding: 0.5rem 1rem;
            border: 1px solid #000; border-radius: 4px;
            color: #fff; background-color: #000; transition: background-color 0.3s;
        }
        .sizes input[type="radio"]:checked + span {
            background-color: #ffcc00; color: #000;
        }
        .sizes span:hover { background-color: #333; }
        .price { font-size: 1.5rem; color: #000; font-weight: bold; margin: 1rem 0; }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg" style="background-color: #000000;">
        <div class="container-fluid"> <a class="navbar-brand mx-3" href="#"></a>
            <img src="assets/images/logo.png" alt="" width="100" height="90">
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="index.html">Início</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="produtos.php">Produtos</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="contato.html">Contato</a></li>
                </ul>
                
                <div class="iconsHeader">
                    <a href="carrinho.html" class="nav-link iconShop"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="login.html" class="nav-link icon" id="link-usuario"><i class="fa-solid fa-user"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-6 text-center">
                <img src="<?php echo $img_corrigida; ?>" class="img-fluid mb-4 rounded shadow" alt="Foto do Produto">
            </div>

            <div class="col-md-6">
                <h1 id="productName"><?php echo $produto['nome']; ?></h1>
                
                <p class="price" id="productPrice">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                
                <p><?php echo nl2br($produto['descricao']); ?></p>

                <form action="controllers/carrinho_adicionar.php" method="POST">
                    
                    <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">

                    <div class="size-selection mt-4">
                        <h5 style="color: #000000;">Escolha o Tamanho:</h5>
                        <div class="sizes">
                            <?php foreach($lista_tamanhos as $tamanho): ?>
                                <label>
                                    <input type="radio" name="tamanho" value="<?php echo trim($tamanho); ?>" required>
                                    <span><?php echo trim($tamanho); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark mt-4 w-100 btn-lg">Adicionar ao Carrinho</button>
                </form>

            </div>
        </div>
    </div>

    <footer class="bg-dark text-light py-5 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Sobre Pkz Sneakers</h5>
                    <p>Somos uma loja de sneakers com estilo streetwear.</p>
                </div>
                <div class="col-md-6 text-center">
                    <h5>Redes Sociais</h5>
                    <a href="#" class="text-light"><i class="fab fa-instagram fa-2x"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="assets/js/sessao.js"></script> 

    </body>
</html>