<?php
session_start();
// AJUSTE: Aponta para a pasta config criada
include('config/conexao.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Pkz Sneakers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .card-img-top {
            width: 100%;
            height: 250px;
            object-fit: contain;
            padding: 10px;
        }
        .card { height: 100%; transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg" style="background-color: #000000;">
        <a class="navbar-brand mx-3" href="#"></a>
        <img src="assets/images/logo.png" alt="" width="100" height="90">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.html">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="produtos.php">Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contato.html">Contato</a>
                </li>
            </ul>
            <!-- Contêiner para os ícones de carrinho e usuário -->
            <div class="iconsHeader">
                <a href="carrinho.html" class="nav-link iconShop">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
                <a href="login.html" class="nav-link icon" id="link-usuario">
                         <i class="fa-solid fa-user"></i>
                        </a>
                </div>
                <script src="assets/js/session.js"></script>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Nossos Produtos</h1>
        <div class="row">
            <?php
            // Busca os produtos no banco
            $sql = "SELECT * FROM produtos ORDER BY id DESC";
            $query = $conn->query($sql);

            if ($query && $query->num_rows > 0) {
                while ($prod = $query->fetch_assoc()) {
                    // Ajusta o caminho da imagem (se tiver 'images/' no banco, adiciona 'assets/')
                    $img = $prod['imagem'];
                    if (!str_starts_with($img, 'assets/')) {
                        $img = 'assets/' . $img;
                    }
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card product-card">
                        <img src="<?php echo $img; ?>" class="card-img-top" alt="<?php echo $prod['nome']; ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo $prod['nome']; ?></h5>
                            <p class="card-text fw-bold">R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></p>
                            <a href="detalhe_produto.php?id=<?php echo $prod['id']; ?>" class="btn btn-dark">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
            <?php 
                } 
            } else {
                echo "<p class='text-center'>Nenhum produto encontrado. Cadastre no painel admin!</p>";
            }
            ?>
        </div>
    </div>

    <footer class="bg-dark text-light py-5">
                    <div class="container">
                        <div class="row">
                            <!-- Seção Sobre -->
                            <div class="col-md-4">
                                <h5>Sobre Pkz Sneakers</h5>
                                <p>Somos uma loja de sneakers com estilo streetwear, trazendo os lançamentos mais recentes e
                                    exclusivos para você.</p>
                                <p>&copy; 2024 Pkz Sneakers. Todos os direitos reservados.</p>
                            </div>
                            <!-- Links Rápidos -->
                            <div class="col-md-2">
                                <h5>Links Rápidos</h5>
                                <ul class="list-unstyled">
                                    <li><a href="index.html" class="text-light">Início</a></li>
                                    <li><a href="produtos.php" class="text-light">Produtos</a></li>
                                    <li><a href="contato.html" class="text-light">Contato</a></li>
                                </ul>
                            </div>
                            <!-- Redes Sociais -->
                            <div class="col-md-6 text-center">
                                <h5>Redes Sociais</h5>
                                <a href="https://www.instagram.com/pouskzin" class="text-light">
                                    <i class="fab fa-instagram fa-2x"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sessao.js"></script>
</body>
</html>