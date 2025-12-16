<?php
// Configurações do Banco de Dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "";
$port = ;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
