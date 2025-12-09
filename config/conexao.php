<?php
// Configurações do Banco de Dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pkzsneakers";
$port = 3307; // <--- ATENÇÃO: Se mudaste o XAMPP para 3307, muda este número para 3307!

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>