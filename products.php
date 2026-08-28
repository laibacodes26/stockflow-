<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - StockFlow</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f3f6; }

        /* Top utility bar */
        .topbar {
            background: #0f1115;
            color: #cfd2d8;
            font-size: 12px;
            padding: 6px 30px;
            text-align: center;
            letter-spacing: 0.3px;
        }

        /* Main nav */
        nav {
            background: linear-gradient(90deg, #1a1a2e, #16213e);
            padding: 14px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 12px rgba(0,0,0,0.25);
            gap: 20px;
            flex-wrap: wrap;
        }
        .logo {
            color: #fff;
            font-size: 24px;
            font-weight: 800;
            text-decoration: none;
            white-space: nowrap;
        }
        .logo span { color: #ff7a00; }
        .search-bar {
            flex: 1;
            display: flex;
            max-width: 600px;
            min-width: 180px;
        }
        .search-bar input {
            flex: 1;
            padding: 11px 16px;
            border: none;
            border-radius: 25px 0 0 25px;
            font-size: 14px;
            outline: none;
        }
        .search-bar button {
            background: #ff7a00;
            border: none;
            color: white;
            padding: 0 22px;
            border-radius: 0 25px 25px 0;
            cursor: pointer;
            font-size: 15px;
            font-weight: 700;
        }
        .nav-links { display: flex; align-items: center; gap: 18px; }
        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .nav-links a:hover { color: #ff7a00; }
        .nav-links .cart-btn {
            background: #ff7a00;
            padding: 9px 20px;
            border-radius: 25px;
            font-weight: 700;
        }
        .nav-links .cart-btn:hover { background: #e96e00; color: #fff; }

        /* Hero banner */
        .hero {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 60%, #ff7a00 140%);
            color: white;
            text-align: center;
            padding: 50px 20px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 20%, rgba(255,122,0,0.25), transparent 50%),
                        radial-gradient(circle at 10% 90%, rgba(255,255,255,0.08), transparent 40%);
        }
        .hero h1 { font-size: 36px; font-weight: 800; position: relative; letter-spacing: 0.5px; }
        .hero p { opacity: 0.85; margin-top: 10px; font-size: 15px; position: relative; }
        .hero .tags {
            margin-top: 18px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            position: relative;
        }
        .hero .tags span {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(4px);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* Filters */
        .filters {
            background: white;
            padding: 16px 30px;
            display: flex;
            gap: 10px;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            flex-wrap: wrap;
            position: sticky;
            top: 76px;
            z-index: 90;
        }
        .filters span.label { font-weight: 800; color: #1a1a2e; font-size: 14px; margin-right: 4px; }
        .filter-btn {
            padding: 8px 20px;
            border: 2px solid #e8eaed;
            border-radius: 25px;
            background: white;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            color: #555;
            transition: all 0.25s;
        }
        .filter-btn:hover, .filter-btn.active {
            background: #ff7a00;
            border-color: #ff7a00;
            color: white;
            transform: translateY(-1px);
        }

        /* Product grid */
        .products-section { padding: 28px 30px; max-width: 1400px; margin: 0 auto; }
        .section-title {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title::before {
            content: '';
            width: 6px; height: 22px;
            background: #ff7a00;
            border-radius: 3px;
            display: inline-block;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 18px;
        }
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            transition: transform 0.25s, box-shadow 0.25s;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 30px rgba(0,0,0,0.13);
        }
        .product-img {
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f6fa, #e9ebf0);
            position: relative;
        }
        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
            display: block;
        }
        .product-card:hover .product-img img { transform: scale(1.08); }
        .product-img.no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }
        .product-img.no-image::after {
            content: attr(data-name);
            color: #9aa3b2;
            font-weight: 700;
            font-size: 15px;
            line-height: 1.4;
        }
        .sale-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        .badge-new { background: #2ed573; }
        .badge-hot { background: #ff4757; }
        .badge-sale { background: #ffa502; }
        .wishlist-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 32px; height: 32px;
            background: rgba(255,255,255,0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: #aaa;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .product-info { padding: 14px; display: flex; flex-direction: column; flex: 1; }
        .product-category {
            font-size: 11px;
            color: #ff7a00;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .product-name {
            font-size: 14.5px;
            font-weight: 700;
            margin: 5px 0 4px;
            color: #1a1a2e;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .product-rating {
            font-size: 12px;
            color: #ffa502;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .product-rating .count { color: #999; }
        .product-desc {
            font-size: 12px;
            color: #888;
            margin-bottom: 8px;
            height: 32px;
            overflow: hidden;
        }
        .price-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
            flex-wrap: wrap;
        }
        .product-price { font-size: 19px; font-weight: 800; color: #1a1a2e; }
        .old-price { font-size: 13px; color: #aaa; text-decoration: line-through; }
        .discount { font-size: 11px; color: #2ed573; font-weight: 800; background: #e9fbf0; padding: 2px 6px; border-radius: 4px; }
        .stock-badge { font-size: 11px; color: #2ed573; margin-bottom: 10px; font-weight: 600; }
        .btn-add-cart {
            width: 100%;
            padding: 10px;
            background: #1a1a2e;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: background 0.3s;
            margin-top: auto;
        }
        .btn-add-cart:hover { background: #ff7a00; }

        footer {
            background: #1a1a2e;
            color: #aaa;
            text-align: center;
            padding: 25px;
            margin-top: 35px;
            font-size: 13px;
        }
        footer span { color: #ff7a00; font-weight: 700; }
    </style>
</head>
<body>

<div class="topbar">Free Delivery on orders above Rs. 2000 &nbsp;|&nbsp; 7-Day Easy Returns &nbsp;|&nbsp; Cash on Delivery Available</div>

<nav>
    <a href="index.php" class="logo">Stock<span>Flow</span></a>
    <form class="search-bar" action="products.php" method="get">
        <input type="text" name="q" placeholder="Search for products, brands and more..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
        <button type="submit">🔍</button>
    </form>
    <div class="nav-links">
        <a href="products.php">Products</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="cart.php" class="cart-btn">🛒 Cart</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="cart.php" class="cart-btn">🛒 Cart</a>
        <?php endif; ?>
    </div>
</nav>

<div class="hero">
    <h1>Shop Everything You Love</h1>
    <p>Top brands, unbeatable prices, delivered fast across Pakistan</p>
    <div class="tags">
        <span>⚡ Flash Deals</span>
        <span>🚚 Free Delivery</span>
        <span>⭐ Top Rated</span>
        <span>🔒 Secure Checkout</span>
    </div>
</div>

<div class="filters">
    <span class="label">Categories:</span>
    <a href="products.php" class="filter-btn <?= (!isset($_GET['category']) && !isset($_GET['q'])) ? 'active' : '' ?>">All</a>
    <?php
    $cats = mysqli_query($conn, "SELECT * FROM categories ORDER BY id");
    while($c = mysqli_fetch_assoc($cats)):
    ?>
    <a href="products.php?category=<?= $c['slug'] ?>"
       class="filter-btn <?= (isset($_GET['category']) && $_GET['category'] == $c['slug']) ? 'active' : '' ?>">
        <?= htmlspecialchars($c['name']) ?>
    </a>
    <?php endwhile; ?>
</div>

<div class="products-section">
    <div class="section-title">
        <?php
        if (isset($_GET['q']) && $_GET['q'] !== '') {
            echo 'Search results for "' . htmlspecialchars($_GET['q']) . '"';
        } elseif (isset($_GET['category'])) {
            echo htmlspecialchars(ucfirst($_GET['category']));
        } else {
            echo 'Featured Products';
        }
        ?>
    </div>
    <div class="products-grid">
        <?php
        $where = [];
        if (isset($_GET['category'])) {
            $cat = mysqli_real_escape_string($conn, $_GET['category']);
            $where[] = "c.slug = '$cat'";
        }
        if (isset($_GET['q']) && $_GET['q'] !== '') {
            $q = mysqli_real_escape_string($conn, $_GET['q']);
            $where[] = "(p.name LIKE '%$q%' OR p.description LIKE '%$q%' OR c.name LIKE '%$q%')";
        }
        $sql = "SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        $sql .= " ORDER BY p.id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0): ?>
            <div style="text-align:center; padding:60px; color:#888; grid-column:1/-1;">No products found.</div>
        <?php else: ?>
        <?php while($row = mysqli_fetch_assoc($result)):
            $discount = 0;
            if ($row['original_price'] > 0) {
                $discount = round((($row['original_price'] - $row['price']) / $row['original_price']) * 100);
            }
            $badge_class = 'badge-hot';
            if($row['badge'] == 'New') $badge_class = 'badge-new';
            if($row['badge'] == 'Sale') $badge_class = 'badge-sale';

            $rating = (float) $row['rating'];
            $full_stars = floor($rating);
            $stars = str_repeat('★', $full_stars) . str_repeat('☆', 5 - $full_stars);
        ?>
        <div class="product-card">
            <?php if($row['badge']): ?>
                <div class="sale-badge <?= $badge_class ?>"><?= htmlspecialchars($row['badge']) ?></div>
            <?php endif; ?>
            <div class="wishlist-icon">♡</div>
            <div class="product-img">
                <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" loading="lazy"
                     onerror="this.onerror=null;this.style.display='none';this.parentElement.classList.add('no-image');this.parentElement.setAttribute('data-name', '<?= htmlspecialchars(addslashes($row['name'])) ?>');">
            </div>
            <div class="product-info">
                <div class="product-category"><?= htmlspecialchars($row['category_name']) ?></div>
                <div class="product-name"><?= htmlspecialchars($row['name']) ?></div>
                <div class="product-rating">
                    <span><?= $stars ?></span>
                    <span class="count">(<?= number_format($row['reviews_count']) ?>)</span>
                </div>
                <div class="product-desc"><?= htmlspecialchars(substr($row['description'], 0, 65)) ?>...</div>
                <div class="price-row">
                    <div class="product-price">Rs. <?= number_format($row['price'], 0) ?></div>
                    <?php if($row['original_price'] > 0): ?>
                        <div class="old-price">Rs. <?= number_format($row['original_price'], 0) ?></div>
                        <div class="discount">-<?= $discount ?>%</div>
                    <?php endif; ?>
                </div>
                <div class="stock-badge">✓ In Stock (<?= $row['stock'] ?>)</div>
                <button class="btn-add-cart" onclick="addToCart(<?= $row['id'] ?>)">Add to Cart</button>
            </div>
        </div>
        <?php endwhile; endif; ?>
    </div>
</div>

<footer>
    <p>© 2024 Stock<span>Flow</span> — Web Project. Best Selection, Best Price.</p>
</footer>

<script>
function addToCart(productId) {
    fetch('cart_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=add&product_id=' + productId
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Item added to cart!');
        } else {
            alert(data.message);
        }
    });
}
</script>

</body>
</html>
