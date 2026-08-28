<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$success = false;
$error = "";

$cart_result = mysqli_query($conn, "SELECT cart.id, cart.quantity, products.id as product_id, products.name, products.price
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = $user_id");

$cart_items = [];
$total = 0;
while ($row = mysqli_fetch_assoc($cart_result)) {
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity'];
}

if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city    = mysqli_real_escape_string($conn, $_POST['city']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment']);

    if (empty($name) || empty($phone) || empty($address) || empty($city)) {
        $error = "All fields are required!";
    } else {
        $full_address = "$address, $city | Phone: $phone | Payment: $payment";

        mysqli_query($conn, "INSERT INTO orders (user_id, total_amount, address, status) VALUES ($user_id, $total, '$full_address', 'pending')");
        $order_id = mysqli_insert_id($conn);

        foreach ($cart_items as $item) {
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']})");
        }

        mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - StockFlow</title>
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
        .page-header {
            background: linear-gradient(135deg, #1a1a2e, #0f3460);
            color: white;
            text-align: center;
            padding: 40px;
        }
        .page-header h1 { font-size: 32px; font-weight: 800; }
        .checkout-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 25px;
        }
        @media (max-width: 768px) { .checkout-container { grid-template-columns: 1fr; } }
        .form-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .form-card h3 {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; color: #444; font-size: 14px; }
        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: border 0.3s;
            outline: none;
            font-family: inherit;
        }
        input:focus, select:focus, textarea:focus { border-color: #ff7a00; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .payment-options { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .payment-option {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s;
        }
        .payment-option input[type="radio"] { display: none; }
        .payment-option:has(input:checked) { border-color: #ff7a00; background: #fff5f7; }
        .payment-option .p-name { font-size: 13px; font-weight: 600; margin-top: 4px; }
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #ff7a00;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s, transform 0.2s;
        }
        .submit-btn:hover { background: #e96e00; transform: translateY(-2px); }
        .order-summary {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            height: fit-content;
        }
        .order-summary h3 {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; color: #555; }
        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 20px;
            font-weight: 800;
            color: #1a1a2e;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
            margin-top: 10px;
        }
        .summary-total span:last-child { color: #ff7a00; }
        .error {
            background: #ffe0e0;
            color: #c0392b;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            border-left: 4px solid #e74c3c;
        }
        .success-page { max-width: 600px; margin: 60px auto; padding: 0 20px; text-align: center; }
        .success-card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.1);
        }
        .success-card h2 { font-size: 28px; color: #1a1a2e; margin-bottom: 12px; }
        .success-card p { color: #666; margin-bottom: 8px; }
        .order-num {
            background: #f0fff4;
            border: 2px solid #27ae60;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            font-size: 18px;
            font-weight: 700;
            color: #27ae60;
        }
        .home-btn {
            background: #ff7a00;
            color: white;
            padding: 13px 35px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 700;
            display: inline-block;
            margin-top: 15px;
        }
        footer { background: #1a1a2e; color: #aaa; text-align: center; padding: 20px; margin-top: 40px; font-size: 14px; }
    </style>
</head>
<body>

<nav>
    <a href="index.php" class="logo">StockFlow</a>
    <div class="nav-links">
        <a href="products.php">Products</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="page-header">
    <h1>Checkout</h1>
</div>

<?php if ($success): ?>
<div class="success-page">
    <div class="success-card">
        <h2>Order Placed Successfully!</h2>
        <p>Your order has been placed.</p>
        <p>We will deliver soon.</p>
        <div class="order-num">Order Confirmed</div>
        <p style="color:#888; font-size:14px;">Total Amount: <strong style="color:#ff7a00;">Rs. <?= number_format($total, 0) ?></strong></p>
        <a href="index.php" class="home-btn">Back to Home</a>
    </div>
</div>

<?php else: ?>
<div class="checkout-container">
    <div class="form-card">
        <h3>Delivery Details</h3>

        <?php if($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="Your name" value="<?= htmlspecialchars($_SESSION['user_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" placeholder="03XX-XXXXXXX" required>
                </div>
            </div>
            <div class="form-group">
                <label>Home Address</label>
                <textarea name="address" rows="3" placeholder="Street, Area, Colony" required></textarea>
            </div>
            <div class="form-group">
                <label>City</label>
                <select name="city" required>
                    <option value="">Select City</option>
                    <option value="Karachi">Karachi</option>
                    <option value="Lahore">Lahore</option>
                    <option value="Islamabad">Islamabad</option>
                    <option value="Rawalpindi">Rawalpindi</option>
                    <option value="Faisalabad">Faisalabad</option>
                    <option value="Multan">Multan</option>
                    <option value="Peshawar">Peshawar</option>
                    <option value="Quetta">Quetta</option>
                </select>
            </div>

            <h3 style="margin: 20px 0 15px;">Payment Method</h3>
            <div class="payment-options">
                <label class="payment-option">
                    <input type="radio" name="payment" value="Cash on Delivery" checked>
                    <div class="p-name">Cash on Delivery</div>
                </label>
                <label class="payment-option">
                    <input type="radio" name="payment" value="JazzCash">
                    <div class="p-name">JazzCash</div>
                </label>
                <label class="payment-option">
                    <input type="radio" name="payment" value="EasyPaisa">
                    <div class="p-name">EasyPaisa</div>
                </label>
                <label class="payment-option">
                    <input type="radio" name="payment" value="Bank Transfer">
                    <div class="p-name">Bank Transfer</div>
                </label>
            </div>

            <button type="submit" class="submit-btn">Place Order - Rs. <?= number_format($total, 0) ?></button>
        </form>
    </div>

    <div class="order-summary">
        <h3>Your Order</h3>
        <?php foreach ($cart_items as $item): ?>
        <div class="summary-item">
            <span><?= htmlspecialchars($item['name']) ?> x<?= $item['quantity'] ?></span>
            <span>Rs. <?= number_format($item['price'] * $item['quantity'], 0) ?></span>
        </div>
        <?php endforeach; ?>
        <div class="summary-total">
            <span>Total</span>
            <span>Rs. <?= number_format($total, 0) ?></span>
        </div>
        <div style="margin-top:15px; background:#f0fff4; border-radius:8px; padding:12px; font-size:13px; color:#27ae60;">
            Free Delivery - Delivered in 2-3 working days
        </div>
    </div>
</div>
<?php endif; ?>

<footer>
    <p>2024 StockFlow - Web Project</p>
</footer>

</body>
</html>
