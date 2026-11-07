<?php
// ==========================================
// GEMINI BAKERY EXPRESS — MAIN STORE INDEX
// ==========================================
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(["httponly" => true, "samesite" => "Lax"]);
    session_start();
}
include "db.php";

// Lalabas lang nang isang beses ang error; pag-refresh, mawawala na (walang paulit-ulit na login)
$login_error = $_SESSION["login_error"] ?? "";
unset($_SESSION["login_error"]);
$is_admin = !empty($_SESSION["admin_logged_in"]);

// Handle Admin Login submission mula sa Modal
if (isset($_POST["login_btn"])) {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

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
            
            // Kung admin, deretso sa admin.php
            header("Location: admin.php");
            exit();
        } else {
            $login_error = "Mali ang username o password.";
        }
    } else {
        $login_error = "Mali ang username o password.";
    }
    mysqli_stmt_close($stmt);

    // Post/Redirect/Get: iwas ulit-submit ng maling login kapag nag-refresh
    $_SESSION["login_error"] = $login_error;
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gemini Bakery Express | Pre-Order Fresh Bread in Lucena City</title>
    <meta name="description" content="Pre-order fresh pandesal, ensaymada, kakanin, and cakes from Gemini Bakery, Palengke Branch, Lucena City. Pick-up or delivery. Open daily 5AM-8PM.">
    <meta name="theme-color" content="#b91c1c">
    <meta property="og:title" content="Gemini Bakery Express - Lucena City">
    <meta property="og:description" content="Mainit at bagong hurno araw-araw. Pre-order online, skip the line.">
    <meta property="og:type" content="website">
    <link rel="icon" href="logo.svg" type="image/svg+xml">

    <!-- FontAwesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Fredoka:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime(__DIR__ . '/style.css'); ?>">
</head>
<body>

    <!-- 1. LANDING PORTAL SCREEN -->
    <section id="landing-portal" class="overlay-screen hero-section">
        <div class="landing-content">
            <div class="stripe-bar"></div>

            <div class="landing-card">
                <img src="logo.svg" alt="Gemini Bakery Since 1975 logo" class="landing-logo">
                <div class="badge-tag">
                    Lucena City • PALENGKE BRANCH
                </div>
            </div>

            <div class="landing-text">
                <h3>Bili na, Suki! 🍞</h3>
                <p>Mainit at Bagong Hurno Araw-Araw! Skip the long line at the Palengke Branch by pre-ordering your favorite breads, palaman, and kakanin online.</p>
            </div>

            <div class="hours-box">
                <i class="fa-solid fa-clock"></i>
                <span>Open Daily: <strong>5:00 AM - 8:00 PM</strong> | Juarez St. cor. Merchan St.</span>
            </div>

            <!-- Pag pinindot, ii-scroll papunta sa menu -->
            <button id="enter-store-btn" type="button" class="primary-btn lg-btn" onclick="startOrdering()">
                <i class="fa-solid fa-basket-shopping"></i>
                <span>ORDER NOW / BILI NA!</span>
                <i class="fa-solid fa-arrow-down"></i>
            </button>
        </div>
        </section>

    <!-- LOGIN MODAL (Lalabas pagka-click ng Order Now) -->
    <div id="login-modal" class="modal-overlay hidden-modal" role="dialog" aria-modal="true">
        <div class="modal-card" style="max-width: 420px; text-align: left; position: relative;">
            <button id="close-login-btn" type="button" class="close-icon-btn dark" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <img src="logo.svg" alt="Gemini Bakery logo" style="width: 70px; margin-bottom: 0.5rem;">
                <h2 style="color: var(--gemini-red); font-size: 1.5rem;">Admin Login</h2>
                <p style="color: #6b7280; font-size: 0.9rem;">Para sa Gemini Bakery staff lamang.</p>
            </div>

            <?php if (!empty($login_error)): ?>
                <div style="background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>

            <form action="index.php" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">Admin Username</label>
                    <input type="text" name="username" required placeholder="admin" autocomplete="username" style="width: 100%; padding: 0.75rem; border: 1.5px solid #fde68a; border-radius: 0.85rem; background: #fffbeb;">
                </div>

                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" autocomplete="current-password" style="width: 100%; padding: 0.75rem; border: 1.5px solid #fde68a; border-radius: 0.85rem; background: #fffbeb;">
                </div>

                <button type="submit" name="login_btn" class="primary-btn full-btn" style="margin-top: 0.5rem;">
                    <i class="fa-solid fa-right-to-bracket"></i> Log In to Admin Dashboard
                </button>
            </form>

            <div style="margin-top: 1.25rem; text-align: center; font-size: 0.85rem; color: #6b7280;">
                Customer ka ba? Hindi kailangan ng account sa pag-order: <br>
                <button id="continue-as-guest-btn" type="button" style="background: none; border: none; color: #b91c1c; font-weight: 700; cursor: pointer; margin-top: 0.5rem; text-decoration: underline;">
                    Back to the bakery
                </button>
            </div>
        </div>
    </div>

    <!-- CUSTOMER ACCOUNT MODAL -->
    <div id="account-modal" class="modal-overlay hidden-modal" role="dialog" aria-modal="true" aria-labelledby="account-title">
        <div class="modal-card" style="max-width: 420px; text-align: left; position: relative;">
            <button id="close-account-btn" type="button" class="close-icon-btn dark" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            <div style="text-align:center; margin-bottom:1rem;">
                <img src="logo.svg" alt="Gemini Bakery logo" style="width:70px; margin-bottom:0.5rem;">
                <h2 id="account-title" style="color: var(--gemini-red); font-size:1.4rem;">Your Suki Account</h2>
                <p style="color:#6b7280; font-size:0.85rem;">Subaybayan ang orders mo sa kahit anong device. Pwede pa ring mag-order bilang guest.</p>
            </div>
            <div class="acct-tabs">
                <button type="button" class="acct-tab active" data-tab="login">Sign in</button>
                <button type="button" class="acct-tab" data-tab="register">Create account</button>
            </div>
            <div id="account-error" class="acct-error" role="alert"></div>
            <form id="customer-login-form" class="acct-form">
                <input type="email" name="email" required placeholder="Email" autocomplete="email">
                <input type="password" name="password" required placeholder="Password" autocomplete="current-password">
                <button type="submit" class="primary-btn full-btn"><i class="fa-solid fa-right-to-bracket"></i> Sign in</button>
            </form>
            <form id="customer-register-form" class="acct-form hidden">
                <input type="text" name="name" required maxlength="80" placeholder="Full name" autocomplete="name">
                <input type="email" name="email" required placeholder="Email" autocomplete="email">
                <input type="tel" name="phone" id="register-phone" placeholder="Mobile (optional) e.g. 09171234567" autocomplete="tel">
                <input type="password" name="password" required minlength="8" placeholder="Password (at least 8 characters)" autocomplete="new-password">
                <button type="submit" class="primary-btn full-btn"><i class="fa-solid fa-user-plus"></i> Create account</button>
            </form>
            <div style="margin-top:1rem; text-align:center; font-size:0.85rem; color:#6b7280;">
                <button id="account-guest-btn" type="button" class="primary-btn full-btn" style="background:#fff; border:2px solid #fde68a;">Continue as guest (no account needed)</button>
                <button id="open-staff-login-btn" type="button" style="background:none; border:none; margin-top:0.75rem; color:#6b7280; text-decoration:underline; cursor:pointer;">Staff / Admin login</button>
            </div>
        </div>
    </div>

    <!-- MAIN APP SCREEN (Ordering Dashboard) -->
    <div id="main-app" class="app-container">
        <!-- HEADER / NAVBAR -->
        <header class="app-header">
            <div class="header-inner">
                <div class="brand-container" id="nav-brand-home">
                    <img src="logo.svg" alt="Gemini Bakery logo" class="header-logo">
                    <div class="brand-text">
                        <div class="brand-title-row">
                            <h1 class="gemini-logo-text">GEMINI <span>BAKERY</span></h1>
                            <span class="branch-pill">Palengke</span>
                        </div>
                        <p class="header-address"><i class="fa-solid fa-location-dot"></i> Juarez St. cor. Merchan St., Lucena Local Market</p>
                    </div>
                </div>

                <div class="header-actions">
                    <nav class="nav-tabs">
                        <button id="nav-menu-btn" class="nav-tab active">
                            <i class="fa-solid fa-store"></i> <span class="tab-label">Bakery</span>
                        </button>
                        <button id="nav-orders-btn" class="nav-tab">
                            <i class="fa-solid fa-box-archive"></i> <span class="tab-label">My Orders</span>
                        </button>
                        <button id="nav-review-btn" class="nav-tab">
                            <i class="fa-solid fa-star"></i> <span class="tab-label">To Review</span>
                        </button>
                    </nav>

                    <button id="open-account-btn" type="button" class="nav-tab"><i class="fa-solid fa-user"></i> <span class="tab-label">Sign in</span></button>
                    <span id="account-menu" class="hidden" style="display:inline-flex; align-items:center; gap:0.5rem;">
                        <span class="nav-tab" style="cursor:default;"><i class="fa-solid fa-circle-user"></i> <span id="account-name" class="tab-label"></span></span>
                        <button id="logout-customer-btn" type="button" class="nav-tab" aria-label="Sign out"><i class="fa-solid fa-right-from-bracket"></i></button>
                    </span>
                    <?php if ($is_admin): ?>
                        <a href="admin.php" class="nav-tab" style="text-decoration:none;"><i class="fa-solid fa-gauge"></i> <span class="tab-label">Dashboard</span></a>
                    <?php else: ?>
                        <button id="open-admin-login-btn" type="button" class="nav-tab"><i class="fa-solid fa-user-lock"></i> <span class="tab-label">Admin</span></button>
                    <?php endif; ?>
                    <button id="open-cart-btn" class="cart-trigger-btn">
                        <i class="fa-solid fa-basket-shopping"></i>
                        <span class="cart-label">Basket</span>
                        <span id="cart-badge" class="cart-badge">0</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- VIEW 1: ORDERING MENU -->
        <div id="view-menu" class="view-section">
            <div class="banner-box">
                <div class="banner-info">
                    <div class="badge-row">
                        <span id="store-status-badge" class="status-badge"></span>
                        <span class="sub-badge">• Express Pre-Order & Delivery</span>
                    </div>
                    <h2>Freshly Baked Goods Ready for Express Pickup & Delivery!</h2>
                    <p>Operating Daily: 5:00 AM - 8:00 PM • Palengke Branch, Lucena City</p>
                </div>
                <div class="live-clock-box">
                    <span id="live-date">--</span>
                    <span id="live-time">--:--:-- --</span>
                </div>
            </div>

            <div class="menu-layout">
                <section class="menu-main">
                    <div class="filter-card">
                        <div class="search-wrapper">
                            <i class="fa-solid fa-magnifying-glass search-icon"></i>
                            <input type="text" id="search-input" placeholder="Search Pandesal, Pan de Coco, Spanish Bread...">
                        </div>

                        <div class="categories-scroll custom-scrollbar" id="category-filters">
                            <button class="cat-btn active" data-category="ALL"><i class="fa-solid fa-border-all"></i>All Products (37)</button>
                            <button class="cat-btn" data-category="BREAD & BUNDLES"><i class="fa-solid fa-bread-slice"></i>Bread & Bundles</button>
                            <button class="cat-btn" data-category="PALAMAN SECTION"><i class="fa-solid fa-jar"></i>Palaman Section</button>
                            <button class="cat-btn" data-category="KAKANIN SECTION"><i class="fa-solid fa-bowl-rice"></i>Kakanin Section</button>
                            <button class="cat-btn" data-category="CAKES & PASTRIES"><i class="fa-solid fa-cake-candles"></i>Cakes & Pastries</button>
                        </div>
                    </div>

                    <div id="product-grid" class="products-grid"></div>

                    <div id="no-results" class="no-results-card hidden">
                        <i class="fa-solid fa-cookie-bite"></i>
                        <h3>Walang nahanap! 🍞</h3>
                    </div>
                </section>

               <aside class="menu-sidebar">
                    <div class="sidebar-card">
                        <div class="card-title-bar">
                            <span class="card-title"><i class="fa-solid fa-store"></i> Store Details</span>
                            <span class="branch-tag">Palengke Branch</span>
                        </div>
                        <div class="info-callout">
                            <strong style="color: var(--gemini-red); display: block; margin-bottom: 0.2rem;"> " Pabatid sa aming mga mahal na suki:</strong>
                            Ang Gemini Bakery Palengke branch ay bukas mula <strong>5:00 AM hanggang 8:00 PM</strong> araw-araw para maghatid ng sariwang tinapay.
                        </div>
                        <div class="sidebar-detail">
                            <i class="fa-solid fa-map-location-dot"></i>
                            <div><strong>Complete Address:</strong><span>Juarez St. corner Merchan St., Lucena Local Market</span></div>
                        </div>
                        <div class="sidebar-detail">
                            <i class="fa-solid fa-phone"></i>
                            <div><strong>Contact No:</strong><span>(042) 710-1975 / 0917-555-7575</span></div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        <!-- VIEW 2: ORDER STATUS -->
        <div id="view-orders" class="view-section hidden">
            <div class="orders-header-banner">
                <div>
                    <h2>Order Tracking & My Orders 📦</h2>
                    <p>Subaybayan ang status ng inyong active orders at past transaction history.</p>
                </div>
                <div class="active-badge-box">
                    <span id="active-orders-count">0 Active Orders</span>
                </div>
            </div>

            <div class="orders-group">
                <h3 class="section-title active-title"><i class="fa-solid fa-spinner"></i> Active Fulfillment Queue</h3>
                <div id="active-orders-list" class="orders-list"></div>
                <div id="no-active-orders" class="empty-orders-card">
                    <p>Walang kasalukuyang active order.</p>
                </div>
            </div>

            <div class="orders-group history-group">
                <h3 class="section-title"><i class="fa-solid fa-clock-rotate-left"></i> Completed Transaction History</h3>
                <div id="history-orders-list" class="orders-list"></div>
                <div id="no-history-orders" class="empty-orders-card font-light">
                    Wala pang nakaraang completed transaction.
                </div>
            </div>
        </div>

        <!-- VIEW 3: TO REVIEW SCREEN -->
        <div id="view-review" class="view-section hidden">
            <div class="review-header-banner">
                <h2>Service & Product Reviews ⭐</h2>
                <p>Mag-iwan ng Service Feedback sa itaas at mag-rate ng inyong biniling mga tinapay sa ibaba!</p>
            </div>
            <div class="menu-layout">
                <section class="menu-main">
                    <h3 class="section-title"><i class="fa-solid fa-box-open"></i> Rate Your Purchased Products</h3>
                    <div id="product-review-cards-container" style="display:grid; gap:1rem;"></div>
                    <div id="no-reviewable-products" class="empty-orders-card"><p>Wala pang produktong pwedeng i-review. Mag-order muna at i-click ang "Order Received".</p></div>
                </section>
                <aside class="menu-sidebar">
                    <div class="sidebar-card">
                        <div class="card-title-bar"><span class="card-title"><i class="fa-solid fa-comment-dots"></i> Service Feedback</span></div>
                        <form id="service-feedback-form" style="display:flex; flex-direction:column; gap:0.75rem;">
                            <input type="text" id="service-author" required maxlength="40" placeholder="Your name">
                            <div id="service-star-container" class="star-rating" role="radiogroup" aria-label="Service rating">
                                <i class="fa-solid fa-star" data-rating="1"></i><i class="fa-solid fa-star" data-rating="2"></i><i class="fa-solid fa-star" data-rating="3"></i><i class="fa-solid fa-star" data-rating="4"></i><i class="fa-solid fa-star" data-rating="5"></i>
                            </div>
                            <p id="service-rating-text" class="rating-hint">Pumili ng 1-5 stars</p>
                            <textarea id="service-comment" rows="3" maxlength="300" placeholder="Ano ang masasabi mo sa serbisyo namin?"></textarea>
                            <button type="submit" class="primary-btn full-btn"><i class="fa-solid fa-paper-plane"></i> Submit Feedback</button>
                        </form>
                        <h4 class="drawer-section-title" style="margin-top:1.25rem;">What suki say</h4>
                        <div id="published-feedback-list" class="published-feedback-container"></div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <!-- BASKET DRAWER -->
    <div id="cart-drawer-overlay" class="drawer-overlay hidden-modal">
        <div id="cart-drawer" class="cart-drawer" style="transform: translateX(100%);">
            <div class="drawer-header">
                <div class="drawer-title">
                    <i class="fa-solid fa-basket-shopping"></i>
                    <h2>Your Bakery Basket</h2>
                </div>
                <div class="drawer-header-actions">
                    <button id="clear-cart-btn" class="clear-btn hidden"><i class="fa-solid fa-trash-can"></i> Clear</button>
                    <button id="close-cart-btn" class="close-icon-btn"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>

            <div class="drawer-body custom-scrollbar">
                <div class="drawer-section">
                    <h3 class="drawer-section-title">ORDER ITEMS</h3>
                    <div id="cart-items-container" class="cart-items-list"></div>
                    <div id="empty-cart-msg" class="empty-cart-box">
                        <i class="fa-solid fa-basket-shopping empty-basket-icon"></i>
                        <p class="empty-cart-text">Your basket is currently empty.</p>
                    </div>
                </div>

                <hr class="drawer-divider">

                <form id="checkout-form" class="checkout-form">
                    <h3 class="drawer-section-title"><i class="fa-solid fa-truck-fast"></i> CHECKOUT & FULFILLMENT</h3>

                    <div class="form-group">
                        <label class="form-label">Order Type <span class="required">*</span></label>
                        <div class="order-type-selector">
                            <label id="lbl-pickup" class="type-option active">
                                <input type="radio" name="order-type" value="PICKUP" checked id="radio-pickup">
                                <i class="fa-solid fa-store"></i> Pick-up (Free)
                            </label>
                            <label id="lbl-delivery" class="type-option">
                                <input type="radio" name="order-type" value="DELIVERY" id="radio-delivery">
                                <i class="fa-solid fa-motorcycle"></i> Delivery (+₱49)
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Method <span class="required">*</span></label>
                        <select id="payment-method-select" required>
                            <option id="payment-counter-option" value="Cash on Counter">Payment on the Counter (for Pick-up)</option>
                            <option id="payment-cod-option" value="Cash on Delivery">Cash on Delivery (for Delivery)</option>
                        </select>
                        <p id="payment-subtext" class="input-subtext">Pick-up orders are paid at the counter. Delivery orders are paid to the rider upon arrival.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Full Name <span class="required">*</span></label>
                        <input type="text" id="cust-name" required placeholder="Juan Cruz">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Number <span class="required">*</span></label>
                        <input type="tel" id="cust-phone" required placeholder="e.g. 09171234567">
                        <p class="input-subtext">Enter exactly 11 digits, starting with 09.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lucena Address / Barangay <span class="required">*</span></label>
                        <textarea id="cust-address" rows="2" required placeholder="e.g. Brgy. 4, Juarez St., Lucena City"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Schedule Option <span class="required">*</span></label>
                        <select id="cust-schedule" required>
                            <option value="Today (As soon as possible)">Order Today (As soon as possible)</option>
                            <option value="Tomorrow Early Morning (5:00 AM - 8:00 AM)">Tomorrow Early Morning (5:00 AM - 8:00 AM)</option>
                            <option value="Schedule Next-Day Afternoon">Schedule Next-Day Afternoon</option>
                        </select>
                    </div>

                    <div class="summary-box">
                        <div class="summary-row"><span>Subtotal:</span><span id="summary-subtotal" class="amount-bold">₱0.00</span></div>
                        <div class="summary-row"><span>Fee:</span><span id="summary-shipping" class="free-text">FREE</span></div>
                        <div class="summary-row total-row">
                            <span class="grand-total-label">Grand Total:</span>
                            <span id="summary-total" class="grand-total-amount">₱0.00</span>
                        </div>
                    </div>

                    <button type="submit" id="place-order-btn" disabled class="primary-btn place-order-submit-btn">
                        <i class="fa-solid fa-paper-plane"></i> PLACE PRE-ORDER NOW
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- SPINNER OVERLAY -->
    <div id="loading-overlay" class="modal-overlay hidden-modal">
        <div class="spinner-card">
            <div class="spinner-ring"></div>
            <p>Processing Order...</p>
        </div>
    </div>

    <!-- RECEIPT MODAL -->
    <div id="receipt-modal" class="modal-overlay hidden-modal">
        <div class="modal-card receipt-card">
            <button id="close-modal-btn" class="close-icon-btn dark"><i class="fa-solid fa-xmark"></i></button>
            <div id="printable-receipt" class="printable-receipt">
                <div class="receipt-header">
                    <img src="logo.svg" alt="Logo" class="receipt-logo">
                    <p class="branch-name">Palengke Branch (Lucena City)</p>
                    <p class="receipt-subtext">Juarez St. cor. Merchan St. | Official Pre-Order Slip</p>
                    <div class="queue-card">
                        <span class="label">System-Generated Order Number</span>
                        <span id="receipt-order-id" class="order-id">GEMINI-2026-000</span>
                        <div class="queue-inner">
                            <span class="label">Queue Number</span>
                            <span id="receipt-queue-no" class="queue-num">#001</span>
                        </div>
                    </div>
                </div>

                <div class="receipt-details">
                    <p><strong>Timestamp:</strong> <span id="receipt-timestamp">--</span></p>
                    <p><strong>Customer:</strong> <span id="receipt-cust-name">--</span></p>
                    <p><strong>Contact:</strong> <span id="receipt-cust-phone">--</span></p>
                    <p><strong>Type:</strong> <span id="receipt-cust-type">--</span></p>
                    <p><strong>Payment Method:</strong> <span id="receipt-pay-method">--</span></p>
                    <p><strong>Payment Status:</strong> <span id="receipt-pay-status">--</span></p>
                </div>

                <div class="receipt-items-wrapper">
                    <div class="items-header"><span>ITEM</span><span>TOTAL</span></div>
                    <div id="receipt-items-list" class="receipt-items-list"></div>
                </div>

                <div class="receipt-totals">
                    <div class="total-row-sm"><span>Subtotal:</span><span id="receipt-subtotal">₱0.00</span></div>
                    <div class="total-row-sm"><span>Fee:</span><span id="receipt-shipping">₱0.00</span></div>
                    <div class="total-row-lg"><span>GRAND TOTAL DUE:</span><span id="receipt-grand-total">₱0.00</span></div>
                </div>
            </div>

            <div class="receipt-actions">
                <button id="download-receipt-btn" type="button" class="success-btn">
                    <i class="fa-solid fa-file-pdf"></i> Save Receipt (PDF / Photo)
                </button>
                <button id="finish-order-btn" type="button" class="red-btn">
                    <i class="fa-solid fa-circle-check"></i> Done & Track Order
                </button>
            </div>
        </div>
    </div>

    <!-- DOWNLOAD SUCCESS MODAL -->
    <div id="download-success-modal" class="modal-overlay hidden-modal z-top">
        <div class="modal-card mini-card">
            <div class="success-icon-circle">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="modal-header-text">
                <h3 class="success-title">Receipt Saved!</h3>
                <p>Naka-ready na ang resibo! Pumili ng "Save as PDF" o i-screenshot para ma-save agad sa Photos/Gallery ng device mo.</p>
            </div>
            <button id="close-download-success-btn" type="button" class="success-btn full-btn">
                Awesome, Got It!
            </button>
        </div>
    </div>

    <!-- THANK YOU MODAL -->
    <div id="thankyou-modal" class="modal-overlay hidden-modal">
        <div class="modal-card mini-card">
            <i class="fa-solid fa-heart heart-icon"></i>
            <h3 class="red-title">Thank you for purchasing, Suki!</h3>
            <button id="thankyou-continue-btn" class="yellow-btn full-btn">Continue to Review</button>
        </div>
    </div>

    <footer class="app-footer site-footer">
        <div class="footer-grid">
            <div>
                <img src="logo.svg" alt="Gemini Bakery" style="width:110px; border-radius:0.5rem;">
                <p>Mainit at bagong hurno araw-araw simula 1975.</p>
            </div>
            <div>
                <strong>Visit us</strong>
                <p>Juarez St. cor. Merchan St.,<br>Lucena Local Market, Lucena City</p>
                <p>Open daily 5:00 AM - 8:00 PM</p>
            </div>
            <div>
                <strong>Contact</strong>
                <p>(042) 710-1975<br>0917-555-7575</p>
            </div>
            <div>
                <strong>Settings</strong>
                <label class="remember-label"><input type="checkbox" id="remember-me-checkbox"> Save my basket and orders on this device</label>
                <?php if ($is_admin): ?><p><a href="logout.php">Log out (<?php echo htmlspecialchars($_SESSION["admin_username"] ?? "admin"); ?>)</a></p><?php endif; ?>
            </div>
        </div>
        <p class="footer-copy">© 2026 Gemini Bakery Express • Palengke Branch, Lucena City</p>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("login-modal");
        const open = () => { modal.classList.remove("hidden-modal"); const u = modal.querySelector("input[name=username]"); if (u) u.focus(); };
        const close = () => modal.classList.add("hidden-modal");

        document.getElementById("open-admin-login-btn")?.addEventListener("click", open);
        document.getElementById("continue-as-guest-btn")?.addEventListener("click", close);
        document.getElementById("close-login-btn")?.addEventListener("click", close);
        modal.addEventListener("click", e => { if (e.target === modal) close(); });
        document.addEventListener("keydown", e => { if (e.key === "Escape") close(); });

        <?php if (!empty($login_error)): ?> open(); <?php endif; ?>
    });
    </script>
    <script>
    // Fallback: kung luma ang script.js (hindi napalitan o naka-cache), magbigay ng malinaw na mensahe
    function startOrdering() {
        alert("Luma pa ang script.js sa website mo. Palitan ito ng bagong script.js, tapos pindutin ang Ctrl + Shift + R.");
    }
    </script>
    <script src="script.js?v=<?php echo filemtime(__DIR__ . '/script.js'); ?>"></script>
</body>
</html>