<?php
session_start();
include "db.php";

// Kung naka-log in na, direktang pumunta sa admin dashboard
if (isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] === true) {
    header("Location: admin.php");
    exit();
}

// Kung pinuntahan nang direkta ang login.php nang walang form submission, ibalik sa index.php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

$sql = "SELECT id, username, password FROM admin_users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $row["password"])) {
        session_regenerate_id(true);
        $_SESSION["admin_logged_in"] = true;
        $_SESSION["admin_username"] = $row["username"];
        header("Location: admin.php");
        exit();
    } else {
        // Ibalik sa index na may kasamang error query kung mali ang password
        $_SESSION["login_error"] = "Mali ang username o password.";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION["login_error"] = "Mali ang username o password.";
        header("Location: index.php");
    exit();
}
mysqli_stmt_close($stmt);
?>