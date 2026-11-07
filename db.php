<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$user = "root";
$password = "";
$database = "gemini_db";

try {
    $conn = mysqli_connect($host, $user, $password);
    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    mysqli_select_db($conn, $database);
    mysqli_set_charset($conn, "utf8mb4");
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    die("Cannot connect to the database. Check that MySQL is running and the settings in db.php are correct.");
}

require_once __DIR__ . "/install.php";
$setup_error = "";
try {
    gemini_install($conn);
} catch (Throwable $e) {
    // Huwag patayin ang buong website; ipakita ang dahilan sa check.php
    $setup_error = $e->getMessage();
    error_log("Gemini setup: " . $setup_error);
}

// Auto-gawa ng default admin kung wala pang laman ang admin_users.
// PALITAN ANG PASSWORD bago i-upload sa totoong server.
try {
$check = mysqli_query($conn, "SELECT COUNT(*) AS total FROM admin_users");
if ((int) mysqli_fetch_assoc($check)["total"] === 0) {
    $defaultUser = "admin";
    $defaultHash = password_hash("admin123", PASSWORD_DEFAULT);
    $seed = mysqli_prepare($conn, "INSERT INTO admin_users (username, password) VALUES (?, ?)");
    mysqli_stmt_bind_param($seed, "ss", $defaultUser, $defaultHash);
    mysqli_stmt_execute($seed);
    mysqli_stmt_close($seed);
}
} catch (Throwable $e) {
    error_log("Gemini admin seed: " . $e->getMessage());
}
