<?php

header("Content-Type: application/json");

require_once "core/conexao.php";
require_once __DIR__ . "/jwt/JWT.php";
require_once __DIR__ . "/jwt/Key.php";

use Firebase\JWT\JWT;

$secret_Key = "7975857f-7bde-43d6-ae1c-6eda1abc566b";

// Obtém os dados da requisição, independentemente do método
$input = file_get_contents("php://input");
$data = json_decode($input, true);

$method = $_SERVER["REQUEST_METHOD"];

// Se houver erro na decodificação JSON, retorna erro
if($method == 'POST') {
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["erro" => "Erro ao processar JSON: " . json_last_error_msg()]);
        exit;
    }
    
    // Valida se os campos necessários foram enviados
    if (!isset($data["email"]) || !isset($data["senha"])) {
        echo json_encode(["erro" => "E-mail e senha obrigatórios"]);
        exit;
    }
    
    // Verifica no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([":email" => $data["email"]]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario && password_verify($data["senha"], $usuario["senha"])) {
        // Dados que serão incluídos no token
        $payload = [
            "id" => $usuario["id"],
            "nome" => $usuario["nome"],
            "iat" => time(),
            "exp" => time() + 3600
        ];
    
        // Gera o token JWT
        $token = JWT::encode($payload, $secret_Key, "HS256");
    
        echo json_encode([
            "token" => $token,
            "email" => $usuario["email"],
            "nome" => $usuario["nome"],
            "id_ixc" => $usuario["id_ixc"]
        ]);
    } else {
        echo json_encode(["erro" => "Credenciais inválidas"]);
    }
} else {
    echo json_encode(["erro" => "Riquisição inválida"]);
}
?>
