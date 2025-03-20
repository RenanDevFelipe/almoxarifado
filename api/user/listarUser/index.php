<?php

header("Access-Control-Allow-Origin: *");
header("Content-Typr: application/json");


require_once "../../core/conexao.php";

$usuario = verificarToken();

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'GET') {
    $stmt = $pdo->query("SELECT id, nome, email FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}

?>