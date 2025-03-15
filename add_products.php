<?php
// Definir o caminho do arquivo JSON
$productsFile = 'products.json';

// Criar a pasta de uploads se não existir
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $variations = $_POST["variations"] ?? []; // Se não houver variações, define um array vazio

    // Processar o upload da imagem
    $imageName = $_FILES["image"]["name"];
    $imageTmpName = $_FILES["image"]["tmp_name"];
    $imagePath = $uploadDir . uniqid() . "_" . basename($imageName);

    if (move_uploaded_file($imageTmpName, $imagePath)) {
        // Carregar os produtos existentes
        $products = [];
        if (file_exists($productsFile)) {
            $json = file_get_contents($productsFile);
            $products = json_decode($json, true);
        }

        // Criar um novo produto
        $newProduct = [
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "category" => $category,
            "variations" => $variations,
            "image" => $imagePath
        ];

        // Adicionar o novo produto à lista
        $products[] = $newProduct;

        // Salvar no JSON
        file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT));

        echo "<script>alert('Produto adicionado com sucesso!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Erro ao fazer upload da imagem.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Requisição inválida.'); window.history.back();</script>";
}
?>
