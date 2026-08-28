CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    original_price DECIMAL(10,2) DEFAULT NULL,
    stock INT NOT NULL DEFAULT 0,
    image_url VARCHAR(2048),
    badge VARCHAR(50) DEFAULT NULL,
    rating DECIMAL(2,1) DEFAULT 4.0,
    reviews_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart (user_id, product_id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    address TEXT NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    placed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY one_review_per_user (user_id, product_id)
);

INSERT INTO categories (name, slug) VALUES
('Electronics & Gadgets', 'electronics'),
('Sneakers & Footwear', 'sneakers'),
('Art & Painting', 'art'),
('Home Decoration', 'decor'),
('Goodies & Lifestyle', 'goodies');

INSERT INTO products (category_id, name, description, price, original_price, stock, image_url, badge, rating, reviews_count) VALUES
-- Electronics & Gadgets
(1, 'Apple AirPods Pro 2nd Gen', 'Active Noise Cancellation, 30-hour battery, MagSafe charging case. Water resistant IP54.', 15999, 19999, 45, 'https://images.unsplash.com/photo-1606220945770-b5b6c2c55bf1?w=900&auto=format&fit=crop&q=80', 'Hot', 4.9, 1820),
(1, 'Samsung Galaxy S24 Ultra', '6.8 inch AMOLED display, 200MP camera, built-in S Pen, 5000mAh battery.', 89999, 99999, 20, 'https://images.unsplash.com/photo-1610945415295-d9bbf067e9c6?w=900&auto=format&fit=crop&q=80', 'New', 4.8, 960),
(1, 'Sony WH-1000XM5 Headphones', 'Industry-leading noise cancellation, 30-hour battery, multipoint Bluetooth.', 22999, 29999, 60, 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?w=900&auto=format&fit=crop&q=80', 'Sale', 4.8, 2410),
(1, 'iPad Air M2 2024', '11 inch Liquid Retina display, Apple M2 chip, USB-C, Wi-Fi 6E.', 54999, 64999, 35, 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=900&auto=format&fit=crop&q=80', 'New', 4.7, 540),
(1, 'Anker 20000mAh Power Bank', '140W output, charges laptop and phone together. LED battery indicator.', 3999, 5999, 120, 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=900&auto=format&fit=crop&q=80', 'Sale', 4.6, 3120),
(1, 'Logitech MX Master 3S Mouse', 'Ergonomic wireless mouse, 8K DPI, 70-day battery, silent click.', 6999, 8999, 80, 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=900&auto=format&fit=crop&q=80', NULL, 4.7, 870),
(1, 'GoPro HERO12 Black', '5.3K video, waterproof to 10m, HyperSmooth stabilization, live streaming.', 24999, 29999, 28, 'https://images.unsplash.com/photo-1606986628253-05620e9b6802?w=900&auto=format&fit=crop&q=80', 'Hot', 4.8, 1530),
(1, 'Xiaomi Smart Band 8 Pro', 'AMOLED display, GPS, 14-day battery, heart rate monitor, 150 workout modes.', 2999, 3999, 200, 'https://images.unsplash.com/photo-1575311373937-040b8e1fd6b0?w=900&auto=format&fit=crop&q=80', 'Hot', 4.5, 4800),
(1, 'Apple Watch Series 9', 'Always-On Retina display, advanced health sensors, GPS, 18-hour battery.', 39999, 45999, 50, 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=900&auto=format&fit=crop&q=80', 'New', 4.8, 1340),
(1, 'JBL Flip 6 Bluetooth Speaker', 'Bold sound, IP67 waterproof and dustproof, 12-hour playtime.', 8999, 11999, 90, 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=900&auto=format&fit=crop&q=80', 'Sale', 4.6, 2050),

-- Sneakers & Footwear
(2, 'Nike Air Jordan 1 Retro High', 'Chicago colorway. Full-grain leather upper, Air-Sole unit, rubber outsole.', 8999, 12999, 50, 'https://images.unsplash.com/photo-1556906781-9a412961c28c?w=900&auto=format&fit=crop&q=80', 'Hot', 4.9, 6700),
(2, 'Adidas Samba OG', 'Suede and leather upper, gum rubber outsole. Timeless street icon.', 5999, NULL, 75, 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=900&auto=format&fit=crop&q=80', 'New', 4.8, 3200),
(2, 'New Balance 550 White Green', 'Leather and mesh upper, chunky cupsole. Retro basketball silhouette.', 5499, 6999, 60, 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=900&auto=format&fit=crop&q=80', NULL, 4.7, 2100),
(2, 'Converse Chuck 70 High Top', 'Canvas upper, cushioned footbed, star-and-chevron outsole. Classic elevated.', 3999, NULL, 90, 'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?w=900&auto=format&fit=crop&q=80', NULL, 4.6, 1800),
(2, 'Puma Suede Classic XXI', 'Suede leather upper, formstripe detail. Streetwear legend since 1968.', 3499, 4999, 100, 'https://images.unsplash.com/photo-1515955656352-a1fa3ffcd111?w=900&auto=format&fit=crop&q=80', 'Sale', 4.5, 2900),
(2, 'Nike Air Max 270', 'Large Air unit for plush cushioning, breathable mesh upper.', 9999, 13999, 65, 'https://images.unsplash.com/photo-1605348532760-6753d2c43329?w=900&auto=format&fit=crop&q=80', 'Hot', 4.7, 3100),
(2, 'Vans Old Skool Black/White', 'Iconic side stripe, sturdy canvas and suede upper, padded collar.', 4499, NULL, 110, 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=900&auto=format&fit=crop&q=80', 'New', 4.6, 2700),

-- Art & Painting
(3, 'Winsor & Newton Watercolour Set', '24 half-pan watercolours in metal tin. Artists-quality, vibrant pigments.', 4999, 6999, 40, 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=900&auto=format&fit=crop&q=80', 'Hot', 4.9, 720),
(3, 'Faber-Castell 72 Colour Pencils', 'Oil-based, break-resistant pencils. Exceptional lightfastness and colour range.', 5999, NULL, 35, 'https://images.unsplash.com/photo-1513883049090-d0b7439799bf?w=900&auto=format&fit=crop&q=80', 'New', 4.8, 1100),
(3, 'Mont Marte Canvas Roll 10m', '100% cotton, triple-primed canvas. Perfect for large-format artworks.', 2499, 3499, 55, 'https://images.unsplash.com/photo-1579783901586-d88db74b4fe4?w=900&auto=format&fit=crop&q=80', 'Sale', 4.6, 430),
(3, 'Liquitex Acrylic Paint Set 12pcs', 'Professional high-viscosity acrylics. Permanent, lightfast, flexible when dry.', 3999, NULL, 45, 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=900&auto=format&fit=crop&q=80', NULL, 4.7, 680),
(3, 'Arteza Premium Brush Set 20pcs', 'Synthetic Taklon bristles. Round, flat, fan, liner shapes. Multi-media use.', 1299, 1999, 150, 'https://images.unsplash.com/photo-1452860606245-08befc0ff44b?w=900&auto=format&fit=crop&q=80', 'Sale', 4.5, 1950),
(3, 'Moleskine Art Sketchbook A4', 'Hard cover, 104 pages, 165gsm ivory paper. Acid-free, multi-media.', 1799, NULL, 80, 'https://images.unsplash.com/photo-1531346680769-a1d79b57de5c?w=900&auto=format&fit=crop&q=80', NULL, 4.8, 2300),
(3, 'Wooden Easel Stand Adjustable', 'Solid beech wood, adjustable height, holds canvases up to 32 inches.', 3299, 4499, 30, 'https://images.unsplash.com/photo-1536924940846-227afb31e2a5?w=900&auto=format&fit=crop&q=80', 'New', 4.7, 410),

-- Home Decoration
(4, 'Boho Macrame Wall Hanging', 'Handcrafted 100% cotton rope. 90x45cm. Earthy tones for any interior.', 1999, 2999, 70, 'https://images.unsplash.com/photo-1615529328331-f8917597711f?w=900&auto=format&fit=crop&q=80', 'Hot', 4.8, 1440),
(4, 'Ceramic Nordic Vase Set 3pcs', 'Minimalist matte finish, organic shapes. 10cm, 18cm, 24cm heights.', 2499, NULL, 55, 'https://images.unsplash.com/photo-1612196808214-b7e239e5d5e9?w=900&auto=format&fit=crop&q=80', 'New', 4.7, 830),
(4, 'Rattan Pendant Lamp Shade', 'Natural handwoven rattan, 35cm diameter. Warm ambient light. E27 socket.', 2999, 3999, 40, 'https://images.unsplash.com/photo-1565538810643-b5bdb714032a?w=900&auto=format&fit=crop&q=80', NULL, 4.6, 560),
(4, 'Scented Soy Candle Trio', 'Lavender, Sandalwood, Fresh Linen. 200g each, 40-hour burn, frosted jar.', 1799, 2499, 120, 'https://images.unsplash.com/photo-1603006905003-be475563bc59?w=900&auto=format&fit=crop&q=80', 'Sale', 4.9, 3800),
(4, 'Framed Abstract Canvas Print Set', 'Gallery-wrapped prints, 40x50cm each. Gold metallic frame set of 2.', 3999, NULL, 35, 'https://images.unsplash.com/photo-1531913764164-f85c52e6e654?w=900&auto=format&fit=crop&q=80', NULL, 4.7, 490),
(4, 'Dried Pampas Grass Bouquet', 'Natural dried pampas in Blush Pink and Beige. 70cm stems, lasts 1-3 years.', 1299, 1799, 200, 'https://images.unsplash.com/photo-1610701596061-2ecf227e85b2?w=900&auto=format&fit=crop&q=80', 'Hot', 4.8, 5200),
(4, 'Fairy String Lights 10m', 'Warm white LED copper wire lights, USB powered, 8 lighting modes.', 1499, 2199, 150, 'https://images.unsplash.com/photo-1467810563316-b5476525c0f9?w=900&auto=format&fit=crop&q=80', 'Sale', 4.6, 2980),

-- Goodies & Lifestyle
(5, 'Stanley Quencher Tumbler 40oz', 'Vacuum insulated, cold 48hrs, hot 7hrs. Dishwasher safe, BPA-free.', 2999, 3999, 95, 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=900&auto=format&fit=crop&q=80', 'Hot', 4.9, 7600),
(5, 'Aesthetic Desk Organiser Set', 'Marble-finish acrylic, 6-piece set. Pen holder, phone stand, tray and more.', 1999, 2999, 70, 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?w=900&auto=format&fit=crop&q=80', 'New', 4.7, 1200),
(5, 'Fujifilm Instax Mini 12 Camera', 'Pastel Mint. Auto exposure, selfie mirror. Instant credit-card size prints.', 7999, 9999, 50, 'https://images.unsplash.com/photo-1574607383476-f517f260d30b?w=900&auto=format&fit=crop&q=80', 'Hot', 4.8, 4300),
(5, 'Custom Neon LED Sign', 'Flexible LED neon, 12 colours, remote brightness control, USB-C powered.', 3499, NULL, 60, 'https://images.unsplash.com/photo-1614036634955-ae5e90f9b9eb?w=900&auto=format&fit=crop&q=80', 'New', 4.6, 870),
(5, 'Luxury Self-Care Gift Box', 'Rose quartz roller, vitamin-C serum, face masks, soy candle, silk scrunchie.', 4999, 6999, 40, 'https://images.unsplash.com/photo-1612817288484-6f916006741a?w=900&auto=format&fit=crop&q=80', 'Sale', 4.9, 2100),
(5, 'Matcha Starter Kit', 'Ceremonial Uji Matcha 40g, bamboo whisk, scoop, ceramic bowl. Perfect cup.', 2499, NULL, 85, 'https://images.unsplash.com/photo-1564890369478-c89ca6d9cde9?w=900&auto=format&fit=crop&q=80', NULL, 4.7, 1680),
(5, 'Leather Travel Backpack', 'Water-resistant canvas, padded laptop sleeve, USB charging port.', 4499, 5999, 65, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=900&auto=format&fit=crop&q=80', 'Sale', 4.7, 1990);
