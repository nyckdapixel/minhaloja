<?php
$servername = "localhost";
$username = "root";  // Altere se necessário
$password = "";  // Altere se necessário
$dbname = "loja_db";  // Nome do seu banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
