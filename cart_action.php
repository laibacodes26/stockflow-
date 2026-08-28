<?php
include 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to cart.']);
    exit();
}

$user_id    = (int) $_SESSION['user_id'];
$action     = $_POST['action']     ?? '';
$product_id = (int)($_POST['product_id'] ?? 0);
$cart_id    = (int)($_POST['cart_id']    ?? 0);
$quantity   = (int)($_POST['quantity']   ?? 1);

if ($action === 'add') {
    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product.']);
        exit();
    }

    $check = mysqli_query($conn, "SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    $existing = mysqli_fetch_assoc($check);

    if ($existing) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE id = {$existing['id']}");
    } else {
        mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }

    echo json_encode(['success' => true]);
    exit();
}

if ($action === 'remove') {
    mysqli_query($conn, "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    echo json_encode(['success' => true]);
    exit();
}

if ($action === 'update') {
    if ($quantity < 1) $quantity = 1;
    mysqli_query($conn, "UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = $user_id");
    echo json_encode(['success' => true]);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);
