<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - Pkz Sneakers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div>
        <nav class="navbar navbar-expand-lg" style="background-color: #000000;">
            <a class="navbar-brand mx-3" href="#"></a>
            <img src="images/logo.png" alt="" width="100" height="90">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Início</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Área Administrativa</h2>
            
            <form action="processa_login_admin.php" method="POST">
                <div class="mb-3">
                    <label for="emailInput" class="form-label">E-mail</label>
                    <input type="email" class="form-control" name="email" id="emailInput"
                        placeholder="admin@teste.com" required>
                </div>
                <div class="mb-3">
                    <label for="passwordInput" class="form-label">Senha</label>
                    <input type="password" class="form-control" name="senha" id="passwordInput"
                        placeholder="Digite sua senha de admin" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Entrar no Painel</button>
            </form>
            
            <p class="text-center mt-3"><a href="index.html" class="text-secondary">Voltar ao login de clientes</a></p>
        </div>
    </div>
</body>
</html>