<?php
// Backend ng ordering, reviews at feedback. Presyo ay kinukuha sa products table, hindi sa browser.
header("Content-Type: application/json; charset=utf-8");
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(["httponly" => true, "samesite" => "Lax"]);
    session_start();
}
include "db.php";

set_exception_handler(function ($e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "error" => "Server error. Buksan ang check.php para makita ang problema."]);
});

function out($data, $code = 200) { http_response_code($code); echo json_encode($data); exit(); }

$action = $_GET["action"] ?? "";

$cid = $_SESSION["customer_id"] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "GET" && $action === "customer_me") {
    if (!$cid) out(["ok" => true, "customer" => null]);
    $s = mysqli_prepare($conn, "SELECT id, full_name AS name, email, phone FROM customers WHERE id = ?");
    mysqli_stmt_bind_param($s, "i", $cid);
    mysqli_stmt_execute($s);
    $c = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
    if (!$c) unset($_SESSION["customer_id"]);
    out(["ok" => true, "customer" => $c ?: null]);
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && $action === "my_orders") {
    if (!$cid) out(["ok" => false, "error" => "Not signed in"], 401);
    $s = mysqli_prepare($conn, "SELECT * FROM orders WHERE customer_id = ? ORDER BY id DESC LIMIT 50");
    mysqli_stmt_bind_param($s, "i", $cid);
    mysqli_stmt_execute($s);
    $res = mysqli_stmt_get_result($s);
    $orders = [];
    while ($o = mysqli_fetch_assoc($res)) {
        $orders[(int) $o["id"]] = [
            "orderId" => $o["order_code"], "queueNo" => $o["queue_no"],
            "timestamp" => date("M j, Y, g:i A", strtotime($o["created_at"])),
            "name" => $o["customer_name"], "phone" => $o["phone"], "address" => $o["address"],
            "type" => $o["order_type"], "schedule" => $o["schedule"], "items" => [],
            "subtotal" => (float) $o["subtotal"], "shippingFee" => (float) $o["shipping_fee"],
            "grandTotal" => (float) $o["grand_total"], "paymentMethod" => $o["payment_method"],
            "paymentStatus" => $o["payment_status"], "status" => $o["status"],
        ];
    }
    if ($orders) {
        $in = implode(",", array_keys($orders));
        $r = mysqli_query($conn, "SELECT i.order_id, i.product_id, i.product_name, i.unit_price, i.qty, p.image_url, r.rating, r.comment
            FROM order_items i LEFT JOIN products p ON p.id = i.product_id
            LEFT JOIN product_reviews r ON r.order_id = i.order_id AND r.product_id = i.product_id
            WHERE i.order_id IN ($in)");
        while ($i = mysqli_fetch_assoc($r)) {
            $orders[(int) $i["order_id"]]["items"][] = [
                "id" => (int) $i["product_id"], "name" => $i["product_name"], "price" => (float) $i["unit_price"],
                "qty" => (int) $i["qty"], "img" => $i["image_url"], "rating" => (int) ($i["rating"] ?? 0), "comment" => $i["comment"] ?? "",
            ];
        }
    }
    out(["ok" => true, "orders" => array_values($orders)]);
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && $action === "feedback") {
    $r = mysqli_query($conn, "SELECT name, rating, comment, DATE_FORMAT(created_at, '%m/%d/%Y') AS date FROM service_feedback ORDER BY id DESC LIMIT 30");
    $rows = [];
    while ($x = mysqli_fetch_assoc($r)) { $x["rating"] = (int) $x["rating"]; $rows[] = $x; }
    out(["ok" => true, "items" => array_reverse($rows)]);
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") out(["ok" => false, "error" => "Method not allowed"], 405);
$in = json_decode(file_get_contents("php://input"), true) ?? [];

if ($action === "customer_register") {
    $name = mb_substr(trim($in["name"] ?? ""), 0, 80);
    $email = strtolower(trim($in["email"] ?? ""));
    $phone = trim($in["phone"] ?? "");
    $pass = (string) ($in["password"] ?? "");
    if (mb_strlen($name) < 2) out(["ok" => false, "error" => "Enter your full name."], 422);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) out(["ok" => false, "error" => "Enter a valid email address."], 422);
    if ($phone !== "" && !preg_match('/^09\d{9}$/', $phone)) out(["ok" => false, "error" => "Mobile number must be 11 digits starting with 09."], 422);
    if (strlen($pass) < 8) out(["ok" => false, "error" => "Password must be at least 8 characters."], 422);

    $s = mysqli_prepare($conn, "SELECT id FROM customers WHERE email = ?");
    mysqli_stmt_bind_param($s, "s", $email);
    mysqli_stmt_execute($s);
    if (mysqli_fetch_assoc(mysqli_stmt_get_result($s))) out(["ok" => false, "error" => "That email is already registered. Sign in instead."], 409);

    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $ph = $phone === "" ? null : $phone;
    $s = mysqli_prepare($conn, "INSERT INTO customers (full_name, email, phone, password) VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($s, "ssss", $name, $email, $ph, $hash);
    mysqli_stmt_execute($s);
    session_regenerate_id(true);
    $_SESSION["customer_id"] = mysqli_insert_id($conn);
    out(["ok" => true, "customer" => ["id" => $_SESSION["customer_id"], "name" => $name, "email" => $email, "phone" => $phone]]);
}

if ($action === "customer_login") {
    if (($_SESSION["lock_until"] ?? 0) > time()) out(["ok" => false, "error" => "Too many attempts. Try again in a minute."], 429);
    $email = strtolower(trim($in["email"] ?? ""));
    $s = mysqli_prepare($conn, "SELECT id, full_name, email, phone, password FROM customers WHERE email = ?");
    mysqli_stmt_bind_param($s, "s", $email);
    mysqli_stmt_execute($s);
    $c = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
    if ($c && password_verify((string) ($in["password"] ?? ""), $c["password"])) {
        session_regenerate_id(true);
        unset($_SESSION["fails"], $_SESSION["lock_until"]);
        $_SESSION["customer_id"] = (int) $c["id"];
        out(["ok" => true, "customer" => ["id" => (int) $c["id"], "name" => $c["full_name"], "email" => $c["email"], "phone" => $c["phone"] ?? ""]]);
    }
    $_SESSION["fails"] = ($_SESSION["fails"] ?? 0) + 1;
    if ($_SESSION["fails"] >= 5) { $_SESSION["lock_until"] = time() + 60; $_SESSION["fails"] = 0; }
    out(["ok" => false, "error" => "Wrong email or password."], 401);
}

if ($action === "customer_logout") {
    unset($_SESSION["customer_id"]);
    out(["ok" => true]);
}

if ($action === "order") {
    $name = mb_substr(trim($in["name"] ?? ""), 0, 100);
    $phone = trim($in["phone"] ?? "");
    $address = mb_substr(trim($in["address"] ?? ""), 0, 255);
    $schedule = mb_substr(trim($in["schedule"] ?? ""), 0, 80);
    $type = ($in["type"] ?? "") === "DELIVERY" ? "DELIVERY" : "PICKUP";
    $items = is_array($in["items"] ?? null) ? $in["items"] : [];
    if ($name === "" || $address === "" || !preg_match('/^09\d{9}$/', $phone) || !$items) {
        out(["ok" => false, "error" => "Invalid order details"], 422);
    }

    try {
        $lines = []; $subtotal = 0;
        $q = mysqli_prepare($conn, "SELECT name, price FROM products WHERE id = ? AND is_available = 1");
        foreach ($items as $it) {
            $pid = (int) ($it["id"] ?? 0);
            $qty = max(1, min(99, (int) ($it["qty"] ?? 1)));
            mysqli_stmt_bind_param($q, "i", $pid);
            mysqli_stmt_execute($q);
            $p = mysqli_fetch_assoc(mysqli_stmt_get_result($q));
            if (!$p) out(["ok" => false, "error" => "Product $pid is not available"], 422);
            $lines[] = [$pid, $p["name"], (float) $p["price"], $qty];
            $subtotal += $p["price"] * $qty;
        }
        $fee = $type === "DELIVERY" ? 49.00 : 0.00;
        $total = $subtotal + $fee;
        $payMethod = $type === "DELIVERY" ? "Cash on Delivery" : "Cash on Counter";
        $payStatus = $type === "DELIVERY" ? "Pay on Delivery" : "Pay at Counter";
        $status = $type === "DELIVERY" ? "Out for Delivery" : "Ready for Pickup";

        mysqli_begin_transaction($conn);
        $n = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM orders WHERE DATE(created_at) = CURDATE()"))["c"] + 1;
        $queue = "#" . str_pad((string) $n, 3, "0", STR_PAD_LEFT);
        $code = "GEMINI-" . date("Y") . "-" . substr((string) round(microtime(true) * 1000), -8) . "-" . random_int(100, 999);

        $s = mysqli_prepare($conn, "INSERT INTO orders (customer_id, order_code, queue_no, customer_name, phone, address, order_type, schedule, payment_method, payment_status, subtotal, shipping_fee, grand_total, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($s, "i" . str_repeat("s", 9) . "ddd" . "s", $cid, $code, $queue, $name, $phone, $address, $type, $schedule, $payMethod, $payStatus, $subtotal, $fee, $total, $status);
        mysqli_stmt_execute($s);
        $oid = mysqli_insert_id($conn);

        $li = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, product_name, unit_price, qty) VALUES (?,?,?,?,?)");
        foreach ($lines as [$pid, $pname, $price, $qty]) {
            mysqli_stmt_bind_param($li, "iisdi", $oid, $pid, $pname, $price, $qty);
            mysqli_stmt_execute($li);
        }
        mysqli_commit($conn);
        out(["ok" => true, "orderId" => $code, "queueNo" => $queue]);
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        out(["ok" => false, "error" => "Could not save order"], 500);
    }
}

if ($action === "order_received") {
    $s = mysqli_prepare($conn, "UPDATE orders SET status = 'Completed', payment_status = 'Paid / Settled' WHERE order_code = ?");
    mysqli_stmt_bind_param($s, "s", $in["orderId"]);
    mysqli_stmt_execute($s);
    out(["ok" => true]);
}

if ($action === "review") {
    $rating = (int) ($in["rating"] ?? 0);
    if ($rating < 1 || $rating > 5) out(["ok" => false, "error" => "Invalid rating"], 422);
    $comment = mb_substr(trim($in["comment"] ?? ""), 0, 500);
    $pid = (int) ($in["productId"] ?? 0);
    $s = mysqli_prepare($conn, "INSERT IGNORE INTO product_reviews (order_id, product_id, rating, comment) SELECT id, ?, ?, ? FROM orders WHERE order_code = ?");
    mysqli_stmt_bind_param($s, "iiss", $pid, $rating, $comment, $in["orderId"]);
    mysqli_stmt_execute($s);
    out(["ok" => true]);
}

if ($action === "feedback") {
    $rating = (int) ($in["rating"] ?? 0);
    $name = mb_substr(trim($in["name"] ?? ""), 0, 40);
    if ($rating < 1 || $rating > 5 || $name === "") out(["ok" => false, "error" => "Invalid feedback"], 422);
    $comment = mb_substr(trim($in["comment"] ?? ""), 0, 300);
    $s = mysqli_prepare($conn, "INSERT INTO service_feedback (name, rating, comment) VALUES (?,?,?)");
    mysqli_stmt_bind_param($s, "sis", $name, $rating, $comment);
    mysqli_stmt_execute($s);
    out(["ok" => true]);
}

out(["ok" => false, "error" => "Unknown action"], 404);
