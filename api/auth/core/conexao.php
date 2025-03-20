<?php 

$host = 'localhost';
$dbname = 'controlz';
$user = 'root';
$password = "";


try {
    $pdo = new PDO("mysql:host=$host; dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){
    die("Erro de conexão: " . $e->getMessage());    
}


// Validar Token gerado
require_once dirname(__DIR__) . "/jwt/JWT.php";
require_once dirname(__DIR__). "/jwt/Key.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "7975857f-7bde-43d6-ae1c-6eda1abc566b";

function verificarToken() {
    $headers = getallheaders();

    if (!isset($headers["Authorization"])){
        http_response_code(401);
        echo json_encode(["erro" => "Token nãoo fornecido"]);
        exit;
    }

    $token = str_replace("Bearer ", "", $headers["Authorization"]);

    try {
        return JWT::decode($token, new Key($GLOBALS["secret_key"], "HS256"));
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["erro" => "Token inválido"]);
        exit;
    }
}

?>