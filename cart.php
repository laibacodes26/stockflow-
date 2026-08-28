<?php
include 'config.php';

if (isset($_POST['update_qty'])) {
    if (isset($_SESSION['user_id'])) {
        $cart_id = (int) $_POST['cart_id'];
        $qty     = (int) $_POST['quantity'];
        $user_id = (int) $_SESSION['user_id'];
        if ($qty > 0) {
            mysqli_query($conn, "UPDATE cart SET quantity = $qty WHERE id = $cart_id AND user_id = $user_id");
        }
    }
    header("Location: cart.php");
    exit();
}

if (isset($_GET['remove'])) {
    if (isset($_SESSION['user_id'])) {
        $cart_id = (int) $_GET['remove'];
        $user_id = (int) $_SESSION['user_id'];
        mysqli_query($conn, "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    }
    header("Location: cart.php");
    exit();
}

$cart_items = [];
$total = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = (int) $_SESSION['user_id'];
    $result = mysqli_query($conn, "SELECT cart.id, cart.quantity, cart.user_id,
                                          products.name, products.price, categories.name AS category
                                   FROM cart
                                   JOIN products ON cart.product_id = products.id
                                   JOIN categories ON products.category_id = categories.id
                                   WHERE cart.user_id = $user_id");
    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = $row;
        $total += $row['price'] * $row['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - StockFlow</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        nav {
            background: #1a1a2e;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo { color: #ff7a00; font-size: 24px; font-weight: 800; text-decoration: none; }
        .nav-links a { color: #fff; text-decoration: none; margin-left: 20px; font-size: 15px; }
        .nav-links a:hover { color: #ff7a00; }
        .page-header {
            background: linear-gradient(135deg, #1a1a2e, #0f3460);
            color: white;
            text-align: center;
            padding: 40px;
        }
        .page-header h1 { font-size: 32px; font-weight: 800; }
        .cart-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 25px;
        }
        @media (max-width: 768px) { .cart-container { grid-template-columns: 1fr; } }
        .cart-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .item-details { flex: 1; }
        .item-name { font-size: 17px; font-weight: 700; color: #1a1a2e; }
        .item-category { font-size: 12px; color: #ff7a00; text-transform: uppercase; font-weight: 600; margin-bottom: 5px; }
        .item-price { color: #ff7a00; font-weight: 700; font-size: 16px; }
        .qty-form { display: flex; align-items: center; gap: 8px; margin-top: 8px; }
        .qty-form input {
            width: 55px; padding: 6px; border: 2px solid #e0e0e0;
            border-radius: 6px; text-align: center; font-size: 15px; font-weight: 700;
        }
        .qty-form button {
            padding: 6px 12px; background: #1a1a2e; color: white;
            border: none; border-radius: 6px; cursor: pointer; font-size: 13px;
        }
        .remove-btn {
            background: #ff7a00; color: white; padding: 8px 14px;
            border-radius: 8px; text-decoration: none; font-size: 13px;
            font-weight: 600; white-space: nowrap;
        }
        .remove-btn:hover { background: #e96e00; }
        .order-summary {
            background: white; border-radius: 15px; padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            height: fit-content; position: sticky; top: 20px;
        }
        .order-summary h3 {
            font-size: 20px; font-weight: 800; color: #1a1a2e;
            margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0;
        }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px; color: #555; }
        .summary-total {
            display: flex; justify-content: space-between;
            font-size: 20px; font-weight: 800; color: #1a1a2e;
            padding-top: 15px; border-top: 2px solid #f0f0f0; margin-top: 5px;
        }
        .summary-total span:last-child { color: #ff7a00; }
        .checkout-btn {
            display: block; width: 100%; padding: 14px; background: #ff7a00;
            color: white; text-align: center; border-radius: 10px;
            text-decoration: none; font-size: 16px; font-weight: 700;
            margin-top: 20px; transition: background 0.3s, transform 0.2s;
        }
        .checkout-btn:hover { background: #e96e00; transform: translateY(-2px); }
        .continue-btn {
            display: block; width: 100%; padding: 11px; background: white;
            color: #1a1a2e; text-align: center; border-radius: 10px;
            text-decoration: none; font-size: 14px; font-weight: 600;
            margin-top: 10px; border: 2px solid #e0e0e0; transition: border-color 0.3s;
        }
        .continue-btn:hover { border-color: #1a1a2e; }
        .empty-cart { background: white; border-radius: 15px; padding: 60px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .empty-cart h3 { font-size: 22px; color: #1a1a2e; margin-bottom: 8px; }
        .empty-cart p { color: #888; margin-bottom: 25px; }
        .shop-now-btn { background: #ff7a00; color: white; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: 700; }
        .login-msg { background: white; border-radius: 15px; padding: 60px; text-align: center; }
        footer { background: #1a1a2e; color: #aaa; text-align: center; padding: 20px; margin-top: 40px; font-size: 14px; }
    </style>
</head>
<body>

<nav>
    <a href="index.php" class="logo">StockFlow</a>
    <div class="nav-links">
        <a href="products.php">Products</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>

<div class="page-header">
    <h1>My Cart</h1>
</div>

<?php if (!isset($_SESSION['user_id'])): ?>
<div style="max-width:600px; margin:40px auto; padding:0 20px;">
    <div class="login-msg">
        <h3 style="font-size:22px; margin-bottom:8px;">Login Required</h3>
        <p style="color:#888; margin-bottom:20px;">Please login to view your cart</p>
        <a href="login.php" style="background:#ff7a00; color:white; padding:12px 30px; border-radius:25px; text-decoration:none; font-weight:700;">Login</a>
    </div>
</div>

<?php elseif (empty($cart_items)): ?>
<div style="max-width:600px; margin:40px auto; padding:0 20px;">
    <div class="empty-cart">
        <h3>Cart is Empty</h3>
        <p>No products in your cart</p>
        <a href="products.php" class="shop-now-btn">Shop Now</a>
    </div>
</div>

<?php else: ?>
<div class="cart-container">
    <div class="cart-items">
        <?php foreach ($cart_items as $item): ?>
        <div class="cart-item">
            <div class="item-details">
                <div class="item-category"><?= htmlspecialchars($item['category']) ?></div>
                <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                <div class="item-price">
                    Rs. <?= number_format($item['price'], 0) ?> x <?= $item['quantity'] ?> =
                    <strong>Rs. <?= number_format($item['price'] * $item['quantity'], 0) ?></strong>
                </div>
                <form method="POST" class="qty-form">
                    <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="99">
                    <button type="submit" name="update_qty">Update</button>
                </form>
            </div>
            <a href="cart.php?remove=<?= $item['id'] ?>" class="remove-btn">Remove</a>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="order-summary">
        <h3>Order Summary</h3>
        <?php foreach ($cart_items as $item): ?>
        <div class="summary-row">
            <span><?= htmlspecialchars($item['name']) ?> x<?= $item['quantity'] ?></span>
            <span>Rs. <?= number_format($item['price'] * $item['quantity'], 0) ?></span>
        </div>
        <?php endforeach; ?>
        <div class="summary-row">
            <span>Delivery</span>
            <span style="color:#27ae60;">Free</span>
        </div>
        <div class="summary-total">
            <span>Total</span>
            <span>Rs. <?= number_format($total, 0) ?></span>
        </div>
        <a href="checkout.php" class="checkout-btn">Checkout</a>
        <a href="products.php" class="continue-btn">Continue Shopping</a>
    </div>
</div>
<?php endif; ?>

<footer>
    <p>2024 StockFlow - Web Project</p>
</footer>

</body>
</html>
