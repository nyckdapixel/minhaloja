<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Simula uma conexão com banco de dados
session_start();
if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [];
}

function generateOrderId() {
    return 'ORD-' . strtoupper(uniqid());
}

function handleRequest() {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = isset($input['action']) ? $input['action'] : '';

    switch ($action) {
        case 'process_checkout':
            return processCheckout($input);
        default:
            return [
                'success' => false,
                'message' => 'Ação inválida'
            ];
    }
}

function processCheckout($input) {
    if (!isset($input['cart']) || empty($input['cart'])) {
        return [
            'success' => false,
            'message' => 'Carrinho vazio'
        ];
    }

    if (!isset($input['customer']) || empty($input['customer'])) {
        return [
            'success' => false,
            'message' => 'Dados do cliente não fornecidos'
        ];
    }

    $orderId = generateOrderId();
    $order = [
        'id' => $orderId,
        'date' => date('Y-m-d H:i:s'),
        'customer' => $input['customer'],
        'items' => $input['cart'],
        'total' => calculateTotal($input['cart']),
        'status' => 'pending'
    ];

    // Salva o pedido na sessão (em um sistema real, seria no banco de dados)
    $_SESSION['orders'][] = $order;

    return [
        'success' => true,
        'message' => 'Pedido processado com sucesso',
        'data' => [
            'orderId' => $orderId,
            'order' => $order
        ]
    ];
}

function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Processa a requisição
$response = handleRequest();
echo json_encode($response);