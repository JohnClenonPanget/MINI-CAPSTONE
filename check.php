<?php
// Diagnostic page: buksan ang http://localhost/<folder>/check.php para makita kung ano ang sira.
// BURAHIN ANG FILE NA ITO bago i-upload sa totoong server.
mysqli_report(MYSQLI_REPORT_OFF);
$rows = [];
function chk($ok, $label, $detail = "") { global $rows; $rows[] = [$ok, $label, $detail]; }

chk(version_compare(PHP_VERSION, "7.4", ">="), "PHP version", PHP_VERSION);
chk(function_exists("mysqli_stmt_get_result"), "mysqli + mysqlnd", function_exists("mysqli_stmt_get_result") ? "OK" : "Kailangan i-enable ang mysqlnd");

foreach (["index.php", "script.js", "style.css", "api.php", "db.php", "install.php", "admin.php", "login.php", "logout.php", "logo.svg"] as $f) {
    chk(is_file(__DIR__ . "/$f"), "File: $f");
}
$js = @file_get_contents(__DIR__ . "/script.js") ?: "";
chk(strpos($js, "function startOrdering") !== false, "script.js ay ang BAGONG bersyon", "may startOrdering()");
$idx = @file_get_contents(__DIR__ . "/index.php") ?: "";
chk(strpos($idx, "startOrdering()") !== false, "index.php ay ang BAGONG bersyon");
chk(strpos($idx, 'id="account-modal"') !== false, "index.php may account modal");

// DB credentials mula sa db.php
$dbsrc = @file_get_contents(__DIR__ . "/db.php") ?: "";
$get = function ($n, $def) use ($dbsrc) { return preg_match('/\$' . $n . '\s*=\s*"([^"]*)"/', $dbsrc, $m) ? $m[1] : $def; };
[$h, $u, $p, $d] = [$get("host", "localhost"), $get("user", "root"), $get("password", ""), $get("database", "gemini_db")];

$conn = @mysqli_connect($h, $u, $p);
chk((bool) $conn, "MySQL connection ($u@$h)", $conn ? "OK" : mysqli_connect_error());
if ($conn) {
    $hasdb = @mysqli_select_db($conn, $d);
    chk($hasdb, "Database '$d'", $hasdb ? "OK" : "Wala pa. Buksan ang index.php para magawa.");
    if ($hasdb) {
        foreach (["admin_users", "customers", "products", "orders", "order_items", "product_reviews", "service_feedback", "gemini_baked"] as $t) {
            $r = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM `$t`");
            chk((bool) $r, "Table: $t", $r ? mysqli_fetch_assoc($r)["c"] . " rows" : "Wala pa");
        }
        $r = @mysqli_query($conn, "SHOW COLUMNS FROM orders LIKE 'customer_id'");
        chk($r && mysqli_num_rows($r) > 0, "orders.customer_id column");
        $r = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM products");
        chk($r && (int) mysqli_fetch_assoc($r)["c"] >= 37, "Products naka-seed (dapat 37)");
    }
}
session_start();
chk(session_status() === PHP_SESSION_ACTIVE, "PHP sessions");
$bad = count(array_filter($rows, fn($r) => !$r[0]));
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="robots" content="noindex"><title>Gemini Bakery - System Check</title>
<style>body{font-family:system-ui,sans-serif;max-width:720px;margin:2rem auto;padding:0 1rem}td{padding:.35rem .6rem;border-bottom:1px solid #eee}table{width:100%;border-collapse:collapse}.sum{padding:.8rem 1rem;border-radius:.6rem;font-weight:700;margin:1rem 0}</style></head>
<body>
<h1>System Check</h1>
<div class="sum" style="background:<?php echo $bad ? '#fee2e2' : '#d1fae5'; ?>">
    <?php echo $bad ? "$bad na problema ang nakita (❌ sa ibaba)." : "Lahat OK. Pwede nang gamitin ang website."; ?>
</div>
<table>
<?php foreach ($rows as [$ok, $label, $detail]): ?>
<tr><td><?php echo $ok ? "✅" : "❌"; ?></td><td><?php echo htmlspecialchars($label); ?></td><td style="color:#6b7280"><?php echo htmlspecialchars((string) $detail); ?></td></tr>
<?php endforeach; ?>
</table>
<p><a href="index.php">Pumunta sa website</a></p>
</body></html>
