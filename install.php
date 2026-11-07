<?php
// Auto-install: gumagawa ng tables at nagse-seed ng products kapag wala pa.
// Self-contained: hindi na kailangan ng database.sql sa folder.
function gemini_schema_sql(): string {
    return <<<'SQLEND'
-- Gemini Bakery Express - database schema
-- I-import sa phpMyAdmin (Import tab) o: mysql -u root < database.sql

CREATE DATABASE IF NOT EXISTS gemini_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gemini_db;

-- Para sa login.php / index.php
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,            -- password_hash() ang laman, hindi plain text
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Para sa admin.php (Name, Email, created_at)
CREATE TABLE IF NOT EXISTS gemini_baked (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Email VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Mga produkto (kapareho ng PRODUCTS sa script.js, para pwede nang ilipat sa DB)
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    category ENUM('BREAD & BUNDLES','PALAMAN SECTION','KAKANIN SECTION','CAKES & PASTRIES') NOT NULL,
    price DECIMAL(8,2) NOT NULL,
    description VARCHAR(255),
    image_url VARCHAR(500),
    is_available TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

-- Customer accounts (optional: pwede pa ring mag-order bilang guest)
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(80) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone CHAR(11) NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Mga order (kapareho ng newOrder object sa script.js)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NULL,                      -- NULL kapag guest order
    order_code VARCHAR(40) NOT NULL UNIQUE,    -- hal. GEMINI-2026-12345678-123
    queue_no VARCHAR(10) NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    phone CHAR(11) NOT NULL,
    address VARCHAR(255) NOT NULL,
    order_type ENUM('PICKUP','DELIVERY') NOT NULL,
    schedule VARCHAR(80) NOT NULL,
    payment_method VARCHAR(30) NOT NULL,
    payment_status VARCHAR(30) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    shipping_fee DECIMAL(10,2) NOT NULL DEFAULT 0,
    grand_total DECIMAL(10,2) NOT NULL,
    status ENUM('Placed','Ready for Pickup','Out for Delivery','Completed','Cancelled') NOT NULL DEFAULT 'Placed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (status), INDEX (created_at), INDEX (phone), INDEX (customer_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(120) NOT NULL,        -- naka-save para di magbago ang resibo kapag nag-iba ang presyo
    unit_price DECIMAL(8,2) NOT NULL,
    qty INT NOT NULL CHECK (qty > 0),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Review ng produkto
CREATE TABLE IF NOT EXISTS product_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Service feedback (yung nasa "To Review" page)
CREATE TABLE IF NOT EXISTS service_feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment VARCHAR(300),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Mga produkto (galing sa script.js)
INSERT IGNORE INTO products (id, name, category, price, description, image_url) VALUES
(1, 'Pandesal (Bundle of 10)', 'BREAD & BUNDLES', 50, 'Classic warm, fluffy Lucena pandesal rolled in breadcrumbs.', 'https://i.pinimg.com/736x/b4/5d/c2/b45dc2cb0979047ec161c728a526b038.jpg'),
(2, 'Monay (Bundle of 5)', 'BREAD & BUNDLES', 25, 'Dense, slightly sweet traditional Filipino bread rolls.', 'https://i.pinimg.com/1200x/46/28/a4/4628a4a9f90dd521b7f3cff1538e487a.jpg'),
(3, 'Tasty/Sliced Bread (Loaf)', 'BREAD & BUNDLES', 65, 'Soft sliced white loaf bread baked fresh daily.', 'https://i.pinimg.com/736x/61/a3/41/61a341ddfcbdd8846d1d3271528b69fd.jpg'),
(4, 'Spanish Bread', 'BREAD & BUNDLES', 7, 'Rolled sweet bread filled with buttery sugary filling.', 'https://i.pinimg.com/1200x/88/32/2d/88322df735d2aeddfb60334ceb4b28c0.jpg'),
(5, 'Ensaymada', 'BREAD & BUNDLES', 8, 'Soft brioche topped with grated cheese and butter cream.', 'https://i.pinimg.com/1200x/80/0a/7b/800a7bba7612fff3f2c8938f3d4b6d15.jpg'),
(6, 'Pan de Coco', 'BREAD & BUNDLES', 7, 'Sweet bread bun filled with grated sweetened coconut.', 'https://i.pinimg.com/1200x/4b/ff/32/4bff3237b0cfa3321a55eabbf619bccb.jpg'),
(7, 'Cheese Bread', 'BREAD & BUNDLES', 8, 'Bite-sized sweet rolls coated in creamy cheese bits.', 'https://i.pinimg.com/736x/e8/c6/63/e8c663a2469d7d4f743d52be35ecc81d.jpg'),
(8, 'Star Bread', 'BREAD & BUNDLES', 7, 'Star-shaped dense sweet bread with a light glaze crust.', 'https://i.pinimg.com/1200x/fe/61/a4/fe61a48b718ea6b13e9b004f09229823.jpg'),
(9, 'Pinagong', 'BREAD & BUNDLES', 7, 'Famous turtle-shaped firm sweet bread standard.', 'https://i.pinimg.com/736x/6d/b2/f9/6db2f9cc811fbf231fe55c4f8571683e.jpg'),
(10, 'Kababayan', 'BREAD & BUNDLES', 5, 'Gong-shaped moist Filipino muffin cake.', 'https://i.pinimg.com/1200x/e3/bf/26/e3bf261c5ebd82b815f92297cc20ee4a.jpg'),
(11, 'Kalihim (Pan de Regla)', 'BREAD & BUNDLES', 7, 'Soft bread filled with signature sweet red pudding.', 'https://i.pinimg.com/736x/ad/25/e2/ad25e218bbb8156689fc99fd7d8737c5.jpg'),
(12, 'Yema Spread (Jar)', 'PALAMAN SECTION', 120, 'Rich and creamy sweet custard egg-yolk spread.', 'https://i.pinimg.com/736x/48/ed/81/48ed81dbd1d8c85a6d0aec4a65feaf79.jpg'),
(13, 'Coco Jam (Jar)', 'PALAMAN SECTION', 95, 'Authentic coconut milk and latik caramelized jam.', 'https://i.pinimg.com/1200x/07/ae/f0/07aef021a89e3ba7d4df404781c472a4.jpg'),
(14, 'Peanut Butter (Jar)', 'PALAMAN SECTION', 60, 'Homemade smooth and creamy roasted peanut spread.', 'https://i.pinimg.com/1200x/43/2e/23/432e23b8890a5c46f3d2a5d40870c64d.jpg'),
(15, 'Margarine Blend', 'PALAMAN SECTION', 45, 'Rich butter-flavored margarine spread for warm breads.', 'https://i.pinimg.com/1200x/8c/f2/7e/8cf27e3d59b72bd3e783c14209de0bdf.jpg'),
(16, 'Condensed Milk Dip', 'PALAMAN SECTION', 45, 'Sweetened condensed dip for breadsticks and rolls.', 'https://i.pinimg.com/1200x/49/76/30/4976309f19b4cf2dde3f8aca4ea116b1.jpg'),
(37, 'Special Bibingka', 'KAKANIN SECTION', 25, 'Baked rice cake topped with salted egg and cheese.', 'https://i.pinimg.com/1200x/51/57/ab/5157abe0608454ce42e817c75176faef.jpg'),
(17, 'Puto Lucena (Box)', 'KAKANIN SECTION', 10, 'Fluffy steamed rice cakes topped with melting cheese.', 'https://i.pinimg.com/1200x/5b/e0/a7/5be0a7459c79507fb3d82e5d5743834a.jpg'),
(18, 'Kutsinta w/ Coconut', 'KAKANIN SECTION', 10, 'Chewy steamed rice cakes served with freshly grated coconut.', 'https://i.pinimg.com/1200x/6f/54/25/6f5425e4faeeadf176806660ee12d838.jpg'),
(19, 'Sapin-Sapin Slice', 'KAKANIN SECTION', 15, 'Layered sticky glutinous rice cake with jackfruit and ube.', 'https://i.pinimg.com/736x/17/48/f7/1748f7c57f46753aad467937c4fb9276.jpg'),
(20, 'Cassava Cake', 'KAKANIN SECTION', 15, 'Grated cassava cake with caramelized custard topping.', 'https://i.pinimg.com/736x/ad/7e/e2/ad7ee24ee8e006a1a7678c7b1a6821e6.jpg'),
(21, 'Maja Blanca', 'KAKANIN SECTION', 15, 'Creamy coconut milk pudding with sweet corn kernels.', 'https://i.pinimg.com/1200x/4f/3f/c5/4f3fc5bda7add465f82ab3d2fa98a02f.jpg'),
(22, 'Roll Cake (Chocolate)', 'CAKES & PASTRIES', 20, 'Fluffy chocolate sponge cake roll filled with fudge.', 'https://i.pinimg.com/736x/b0/4c/d1/b04cd16f0b8ed4dd3829441136257918.jpg'),
(23, 'Roll Cake (Mocha)', 'CAKES & PASTRIES', 20, 'Mocha coffee infused sponge roll with buttercream.', 'https://i.pinimg.com/1200x/2c/ef/36/2cef36843f7fc164ad3ea5d5cd710847.jpg'),
(24, 'Roll Cake (Ube)', 'CAKES & PASTRIES', 20, 'Ube infused sponge roll with ube flavor.', 'https://i.pinimg.com/736x/06/5b/c1/065bc1f0513591154022c9200afcfd9d.jpg'),
(25, 'Chiffon Cake Slice', 'CAKES & PASTRIES', 20, 'Light, airy slice of vanilla sponge chiffon cake.', 'https://i.pinimg.com/736x/3b/5c/af/3b5cafd677242f029ac2242edc6b4fd0.jpg'),
(26, 'Egg Pie (Whole)', 'CAKES & PASTRIES', 160, 'Rich, creamy egg custard pie in a flaky pastry crust.', 'https://i.pinimg.com/1200x/38/fb/34/38fb340666d2d1803f80b8ba8d61c31f.jpg'),
(27, 'Egg Pie (Slice)', 'CAKES & PASTRIES', 25, 'Slice of rich egg custard pie with golden toasted top.', 'https://i.pinimg.com/1200x/3d/7b/a1/3d7ba161d0553b76af188cbd82df51d4.jpg'),
(28, 'Brownies (Box)', 'CAKES & PASTRIES', 10, 'Fudgy chocolate brownie squares topped with nuts.', 'https://i.pinimg.com/736x/27/83/69/278369350d38276479a291db7f62525a.jpg'),
(29, 'Banana Bread Loaf', 'CAKES & PASTRIES', 20, 'Moist banana bread made with sweet local bananas.', 'https://i.pinimg.com/1200x/80/e4/43/80e4433103ee934029466974fb4ff9d8.jpg'),
(30, 'Chocolate Crinkles (Tub)', 'CAKES & PASTRIES', 7, 'Soft chocolate fudge cookies rolled in powdered sugar.', 'https://i.pinimg.com/1200x/6f/8a/05/6f8a05c6be94f746d8929ba158d3c094.jpg'),
(31, 'Uraro Cookies (Pack)', 'CAKES & PASTRIES', 80, 'Melt-in-your-mouth arrowroot starch cookies.', 'https://i.pinimg.com/736x/e4/35/2c/e4352ce0d63371c4cef7014cbf82c7ab.jpg'),
(32, 'Classic Bread Pudding (Slice)', 'CAKES & PASTRIES', 15, 'Traditional baked sweet bread pudding with brown sugar and vanilla.', 'https://i.pinimg.com/1200x/23/07/5a/23075aeb84180ade5546f24550ce2f45.jpg'),
(33, 'Dark Chocolate Pudding', 'CAKES & PASTRIES', 15, 'Rich and dense dark chocolate infused bread pudding slice.', 'https://images.squarespace-cdn.com/content/v1/65c3e29954a54703b46fe0ae/8294ee8c-f3f4-4d23-aae0-f6d425e79420/IMG_6297+2.jpg'),
(34, 'Pineapple Pie Slice', 'CAKES & PASTRIES', 25, 'Flaky pastry crust filled with sweet pineapple filling.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1GdPAosdZV7HQ9RA-FmURtZ0JwnrbwY6CL0-UY0tGLQ&s=10'),
(35, 'Taisan Cake Slice', 'CAKES & PASTRIES', 20, 'Soft sponge cake brushed with melted butter and sprinkled with sugar.', 'https://i.pinimg.com/736x/69/6b/16/696b1691e992c817e933c75118501118.jpg'),
(36, 'Pianono Roll', 'CAKES & PASTRIES', 15, 'Traditional mini jelly roll cake filled with butter and sugar.', 'https://i.pinimg.com/1200x/82/98/dd/8298dd758992826ebce04a8bd8b915ec.jpg');

SQLEND;
}

function gemini_install(mysqli $conn): void {
    $has = mysqli_query($conn, "SHOW TABLES LIKE 'customers'");
    if (mysqli_num_rows($has) > 0) return;

    $dbName = mysqli_fetch_row(mysqli_query($conn, "SELECT DATABASE()"))[0];
    mysqli_multi_query($conn, str_replace("gemini_db", $dbName, gemini_schema_sql()));
    do {
        if ($r = mysqli_store_result($conn)) mysqli_free_result($r);
    } while (mysqli_more_results($conn) && mysqli_next_result($conn));

    // Para sa orders table na ginawa ng lumang bersyon
    $c = mysqli_query($conn, "SHOW COLUMNS FROM orders LIKE 'customer_id'");
    if (mysqli_num_rows($c) === 0) {
        mysqli_query($conn, "ALTER TABLE orders ADD COLUMN customer_id INT NULL AFTER id, ADD INDEX (customer_id)");
    }

    // Kung may lumang products table na walang laman
    $n = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM products"))["c"];
    if ((int) $n === 0) {
        if (preg_match('/INSERT IGNORE INTO products.*?;\\s*$/s', gemini_schema_sql(), $m)) {
            mysqli_query($conn, $m[0]);
        }
    }
}
