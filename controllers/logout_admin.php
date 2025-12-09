<?php
session_start();

// Destrói todas as variáveis de sessão (limpa o login)
session_destroy();

// Redireciona para a tela de login principal (HTML)
header("Location: ../login.html"); 
exit;
?>