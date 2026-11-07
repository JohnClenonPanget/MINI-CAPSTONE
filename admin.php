<?php
session_start();

// Seguridad: Kung hindi naka-log in ang admin, ibalik sa index.php
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: index.php");
    exit();
}

include "db.php";
if (empty($_SESSION["csrf"])) $_SESSION["csrf"] = bin2hex(random_bytes(16));
$message = $_SESSION["flash"] ?? "";
unset($_SESSION["flash"]);

// Pag-save ng bagong data sa gemini_baked table
if (isset($_POST["update_status"]) && hash_equals($_SESSION["csrf"], $_POST["csrf"] ?? "")) {
    $allowed = ["Placed", "Ready for Pickup", "Out for Delivery", "Completed", "Cancelled"];
    if (in_array($_POST["status"] ?? "", $allowed, true)) {
        $u = mysqli_prepare($conn, "UPDATE orders SET status = ? WHERE id = ?");
        $oid = (int) $_POST["order_id"];
        mysqli_stmt_bind_param($u, "si", $_POST["status"], $oid);
        mysqli_stmt_execute($u);
        $_SESSION["flash"] = "Order status updated.";
    }
    header("Location: admin.php");
    exit();
}

if (isset($_POST["submit"]) && hash_equals($_SESSION["csrf"], $_POST["csrf"] ?? "")) {
    $name = trim($_POST["Name"]);
    $email = trim($_POST["Email"]);

    $sql = "INSERT INTO gemini_baked (Name, Email) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $name, $email);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION["flash"] = "Data saved successfully to gemini_baked!";
    } else {
        $_SESSION["flash"] = "Error saving data.";
    }
    mysqli_stmt_close($stmt);
    header("Location: admin.php"); // iwas double-submit kapag nag-refresh
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="icon" href="logo.svg" type="image/svg+xml">
    <title>Admin Dashboard - Gemini Bakery Express</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body style="background:#fdfbf7; padding:2rem;">
    <div style="max-width:800px; margin:auto; display:flex; flex-direction:column; gap:1.5rem;">
        
        <!-- Header Bar -->
        <div style="display:flex; justify-content:space-between; align-items:center; background:#b91c1c; color:#fff; padding:1rem 1.5rem; border-radius:1rem;">
            <h2 style="font-size:1.2rem; margin:0;"><i class="fa-solid fa-gauge"></i> Admin Dashboard (gemini_baked)</h2>
            <a href="index.php" style="color:#fff; margin-left:auto; margin-right:1rem; text-decoration:none; font-weight:700;"><i class="fa-solid fa-store"></i> View Store</a>
            <a href="logout.php" style="background:#facc15; color:#3b0a0a; padding:0.5rem 1rem; border-radius:0.5rem; font-weight:800; text-decoration:none;">
                <i class="fa-solid fa-right-from-bracket"></i> Log Out
            </a>
        </div>

        <?php if (!empty($message)): ?>
            <div style="background:#d1fae5; color:#065f46; padding:0.85rem; border-radius:0.85rem; font-weight:700;"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- Form para sa pag-add ng record -->
        <div class="modal-card" style="max-width:100%; text-align:left; background:#fff; padding:1.5rem; border-radius:1rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
            <h3 style="margin-top:0; color:#3b0a0a;"><i class="fa-solid fa-user-plus"></i> Add New Record to gemini_baked</h3>
            <form action="admin.php" method="POST" style="display:flex; flex-direction:column; gap:1rem;">
                <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                <div class="form-group">
                    <label style="font-weight:600; font-size:0.9rem;">Name</label>
                    <input type="text" name="Name" required style="width:100%; padding:0.75rem; border:1.5px solid #fde68a; border-radius:0.85rem;">
                </div>
                <div class="form-group">
                    <label style="font-weight:600; font-size:0.9rem;">Email</label>
                    <input type="email" name="Email" required style="width:100%; padding:0.75rem; border:1.5px solid #fde68a; border-radius:0.85rem;">
                </div>
                <button type="submit" name="submit" class="primary-btn" style="background:#b91c1c; color:#fff; padding:0.75rem; border:none; border-radius:0.85rem; font-weight:700; cursor:pointer;">
                    <i class="fa-solid fa-floppy-disk"></i> Save Data
                </button>
            </form>
        </div>

        <!-- Table ng mga Records -->
        <div class="modal-card" style="max-width:100%; text-align:left; background:#fff; padding:1.5rem; border-radius:1rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
            <h3 style="margin-top:0; color:#3b0a0a;"><i class="fa-solid fa-database"></i> gemini_baked Records List</h3>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; margin-top:0.5rem;">
                    <thead>
                        <tr style="background:#fffbeb; text-align:left; border-bottom:2px solid #fde68a;">
                            <th style="padding:0.75rem;">ID</th>
                            <th style="padding:0.75rem;">Name</th>
                            <th style="padding:0.75rem;">Email</th>
                            <th style="padding:0.75rem;">Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $result = mysqli_query($conn, "SELECT * FROM gemini_baked ORDER BY id DESC");
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr style='border-bottom:1px solid #fee2e2;'>";
                                echo "<td style='padding:0.75rem;'>" . (int)$row['id'] . "</td>";
                                echo "<td style='padding:0.75rem; font-weight:700;'>" . htmlspecialchars($row['Name']) . "</td>";
                                echo "<td style='padding:0.75rem;'>" . htmlspecialchars($row['Email']) . "</td>";
                                echo "<td style='padding:0.75rem; color:#6b7280; font-size:0.8rem;'>" . htmlspecialchars($row['created_at']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='padding:1rem; text-align:center; color:#6b7280;'>Wala pang record sa gemini_baked.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Registered customers -->
        <div class="modal-card" style="max-width:100%; text-align:left; background:#fff; padding:1.5rem; border-radius:1rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
            <h3 style="margin-top:0; color:#3b0a0a;"><i class="fa-solid fa-users"></i> Registered Customers</h3>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead><tr style="background:#fffbeb; text-align:left; border-bottom:2px solid #fde68a;">
                        <th style="padding:0.6rem;">Name</th><th style="padding:0.6rem;">Email</th><th style="padding:0.6rem;">Mobile</th><th style="padding:0.6rem;">Joined</th>
                    </tr></thead>
                    <tbody>
                    <?php
                    $cust = mysqli_query($conn, "SELECT full_name, email, phone, created_at FROM customers ORDER BY id DESC LIMIT 50");
                    if (mysqli_num_rows($cust) > 0):
                        while ($cu = mysqli_fetch_assoc($cust)): ?>
                        <tr style="border-bottom:1px solid #fee2e2;">
                            <td style="padding:0.6rem; font-weight:700;"><?php echo htmlspecialchars($cu["full_name"]); ?></td>
                            <td style="padding:0.6rem;"><?php echo htmlspecialchars($cu["email"]); ?></td>
                            <td style="padding:0.6rem;"><?php echo htmlspecialchars($cu["phone"] ?? "-"); ?></td>
                            <td style="padding:0.6rem; color:#6b7280; font-size:0.8rem;"><?php echo htmlspecialchars($cu["created_at"]); ?></td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="4" style="padding:1rem; text-align:center; color:#6b7280;">Wala pang registered customer.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Orders -->
        <div class="modal-card" style="max-width:100%; text-align:left; background:#fff; padding:1.5rem; border-radius:1rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
            <h3 style="margin-top:0; color:#3b0a0a;"><i class="fa-solid fa-receipt"></i> Customer Orders</h3>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead><tr style="background:#fffbeb; text-align:left; border-bottom:2px solid #fde68a;">
                        <th style="padding:0.6rem;">Queue</th><th style="padding:0.6rem;">Customer</th><th style="padding:0.6rem;">Items</th><th style="padding:0.6rem;">Total</th><th style="padding:0.6rem;">Status</th>
                    </tr></thead>
                    <tbody>
                    <?php
                    $orders = mysqli_query($conn, "SELECT o.*, GROUP_CONCAT(CONCAT(i.product_name, ' x', i.qty) SEPARATOR ', ') AS items FROM orders o LEFT JOIN order_items i ON i.order_id = o.id GROUP BY o.id ORDER BY o.id DESC LIMIT 50");
                    if ($orders && mysqli_num_rows($orders) > 0):
                        while ($o = mysqli_fetch_assoc($orders)): ?>
                        <tr style="border-bottom:1px solid #fee2e2; vertical-align:top;">
                            <td style="padding:0.6rem; font-weight:800;"><?php echo htmlspecialchars($o["queue_no"]); ?><br><small style="color:#6b7280; font-weight:400;"><?php echo htmlspecialchars($o["created_at"]); ?></small></td>
                            <td style="padding:0.6rem;"><?php echo htmlspecialchars($o["customer_name"]); ?><br><small><?php echo htmlspecialchars($o["phone"]); ?> &bull; <?php echo htmlspecialchars($o["order_type"]); ?></small><br><small style="color:#6b7280;"><?php echo htmlspecialchars($o["address"]); ?></small></td>
                            <td style="padding:0.6rem; font-size:0.85rem;"><?php echo htmlspecialchars($o["items"] ?? ""); ?></td>
                            <td style="padding:0.6rem; font-weight:700;">&#8369;<?php echo number_format((float) $o["grand_total"], 2); ?></td>
                            <td style="padding:0.6rem;">
                                <form method="POST" style="display:flex; gap:0.4rem;">
                                    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                    <input type="hidden" name="order_id" value="<?php echo (int) $o["id"]; ?>">
                                    <select name="status" style="padding:0.4rem; border-radius:0.5rem; border:1.5px solid #fde68a;">
                                        <?php foreach (["Placed", "Ready for Pickup", "Out for Delivery", "Completed", "Cancelled"] as $st): ?>
                                            <option <?php echo $o["status"] === $st ? "selected" : ""; ?>><?php echo $st; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button name="update_status" class="primary-btn" style="background:#b91c1c; color:#fff; border:none; border-radius:0.5rem; padding:0.4rem 0.7rem; cursor:pointer;">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="5" style="padding:1rem; text-align:center; color:#6b7280;">Wala pang order.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>