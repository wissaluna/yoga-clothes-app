<?php

session_start();


header('Content-Type: application/json');


$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (isset($data['product_id'])) {
    $productId = $data['product_id'];

    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]++;
    } else {
        $_SESSION['cart'][$productId] = 1;
    }

    
    $totalItems = array_sum($_SESSION['cart']);

    
    echo json_encode(['success' => true, 'totalItems' => $totalItems]);
    exit();
}


echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>