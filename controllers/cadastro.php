<?php
header('Content-Type: application/json');
include '../config/conexao.php';

$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("SELECT * FROM cadastros WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response["message"] = "Este e-mail já está cadastrado.";
    } else {
        $stmt = $conn->prepare("INSERT INTO cadastros (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha_hash);

        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Usuário cadastrado com sucesso!";
        } else {
            $response["message"] = "Erro ao cadastrar o usuário. Tente novamente.";
        }
    }

    $stmt->close();
    $conn->close();
}

echo json_encode($response);
?>
