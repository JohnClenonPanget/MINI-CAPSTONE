// ==========================================
// GEMINI BAKERY EXPRESS — MAIN APPLICATION SCRIPT
// ==========================================

const PRODUCTS = [
    // BREAD & BUNDLES (10 Items)
    { id: 1, name: "Pandesal (Bundle of 10)", category: "BREAD & BUNDLES", price: 50.00, desc: "Classic warm, fluffy Lucena pandesal rolled in breadcrumbs.", img: "https://i.pinimg.com/736x/b4/5d/c2/b45dc2cb0979047ec161c728a526b038.jpg" },
    { id: 2, name: "Monay (Bundle of 5)", category: "BREAD & BUNDLES", price: 25.00, desc: "Dense, slightly sweet traditional Filipino bread rolls.", img: "https://i.pinimg.com/1200x/46/28/a4/4628a4a9f90dd521b7f3cff1538e487a.jpg" },
    { id: 3, name: "Tasty/Sliced Bread (Loaf)", category: "BREAD & BUNDLES", price: 65.00, desc: "Soft sliced white loaf bread baked fresh daily.", img: "https://i.pinimg.com/736x/61/a3/41/61a341ddfcbdd8846d1d3271528b69fd.jpg" },
    { id: 4, name: "Spanish Bread", category: "BREAD & BUNDLES", price: 7.00, desc: "Rolled sweet bread filled with buttery sugary filling.", img: "https://i.pinimg.com/1200x/88/32/2d/88322df735d2aeddfb60334ceb4b28c0.jpg" },
    { id: 5, name: "Ensaymada", category: "BREAD & BUNDLES", price: 8.00, desc: "Soft brioche topped with grated cheese and butter cream.", img: "https://i.pinimg.com/1200x/80/0a/7b/800a7bba7612fff3f2c8938f3d4b6d15.jpg" },
    { id: 6, name: "Pan de Coco", category: "BREAD & BUNDLES", price: 7.00, desc: "Sweet bread bun filled with grated sweetened coconut.", img: "https://i.pinimg.com/1200x/4b/ff/32/4bff3237b0cfa3321a55eabbf619bccb.jpg" },
    { id: 7, name: "Cheese Bread", category: "BREAD & BUNDLES", price: 8.00, desc: "Bite-sized sweet rolls coated in creamy cheese bits.", img: "https://i.pinimg.com/736x/e8/c6/63/e8c663a2469d7d4f743d52be35ecc81d.jpg" },
    { id: 8, name: "Star Bread", category: "BREAD & BUNDLES", price: 7.00, desc: "Star-shaped dense sweet bread with a light glaze crust.", img: "https://i.pinimg.com/1200x/fe/61/a4/fe61a48b718ea6b13e9b004f09229823.jpg" },
    { id: 9, name: "Pinagong", category: "BREAD & BUNDLES", price: 7.00, desc: "Famous turtle-shaped firm sweet bread standard.", img: "https://i.pinimg.com/736x/6d/b2/f9/6db2f9cc811fbf231fe55c4f8571683e.jpg" },
    { id: 10, name: "Kababayan", category: "BREAD & BUNDLES", price: 5.00, desc: "Gong-shaped moist Filipino muffin cake.", img: "https://i.pinimg.com/1200x/e3/bf/26/e3bf261c5ebd82b815f92297cc20ee4a.jpg" },

    // PALAMAN SECTION (5 Items)
    { id: 11, name: "Yema Spread (Jar)", category: "PALAMAN SECTION", price: 120.00, desc: "Rich and creamy sweet custard egg-yolk spread.", img: "https://i.pinimg.com/736x/48/ed/81/48ed81dbd1d8c85a6d0aec4a65feaf79.jpg" },
    { id: 12, name: "Coco Jam (Jar)", category: "PALAMAN SECTION", price: 95.00, desc: "Authentic coconut milk and latik caramelized jam.", img: "https://i.pinimg.com/1200x/07/ae/f0/07aef021a89e3ba7d4df404781c472a4.jpg" },
    { id: 13, name: "Peanut Butter (Jar)", category: "PALAMAN SECTION", price: 60.00, desc: "Homemade smooth and creamy roasted peanut spread.", img: "https://i.pinimg.com/1200x/43/2e/23/432e23b8890a5c46f3d2a5d40870c64d.jpg" },
    { id: 14, name: "Margarine Blend", category: "PALAMAN SECTION", price: 45.00, desc: "Rich butter-flavored margarine spread for warm breads.", img: "https://i.pinimg.com/1200x/8c/f2/7e/8cf27e3d59b72bd3e783c14209de0bdf.jpg" },
    { id: 15, name: "Condensed Milk Dip", category: "PALAMAN SECTION", price: 45.00, desc: "Sweetened condensed dip for breadsticks and rolls.", img: "https://i.pinimg.com/1200x/49/76/30/4976309f19b4cf2dde3f8aca4ea116b1.jpg" },

    // KAKANIN SECTION (6 Items)
    { id: 16, name: "Special Bibingka", category: "KAKANIN SECTION", price: 25.00, desc: "Baked rice cake topped with salted egg and cheese.", img: "https://i.pinimg.com/1200x/51/57/ab/5157abe0608454ce42e817c75176faef.jpg" },
    { id: 17, name: "Puto Lucena (Box)", category: "KAKANIN SECTION", price: 10.00, desc: "Fluffy steamed rice cakes topped with melting cheese.", img: "https://i.pinimg.com/1200x/5b/e0/a7/5be0a7459c79507fb3d82e5d5743834a.jpg" },
    { id: 18, name: "Kutsinta w/ Coconut", category: "KAKANIN SECTION", price: 10.00, desc: "Chewy steamed rice cakes served with freshly grated coconut.", img: "https://i.pinimg.com/1200x/6f/54/25/6f5425e4faeeadf176806660ee12d838.jpg" },
    { id: 19, name: "Sapin-Sapin Slice", category: "KAKANIN SECTION", price: 15.00, desc: "Layered sticky glutinous rice cake with jackfruit and ube.", img: "https://i.pinimg.com/736x/17/48/f7/1748f7c57f46753aad467937c4fb9276.jpg" },
    { id: 20, name: "Cassava Cake", category: "KAKANIN SECTION", price: 15.00, desc: "Grated cassava cake with caramelized custard topping.", img: "https://i.pinimg.com/736x/ad/7e/e2/ad7ee24ee8e006a1a7678c7b1a6821e6.jpg" },
    { id: 21, name: "Maja Blanca", category: "KAKANIN SECTION", price: 15.00, desc: "Creamy coconut milk pudding with sweet corn kernels.", img: "https://i.pinimg.com/1200x/4f/3f/c5/4f3fc5bda7add465f82ab3d2fa98a02f.jpg" },

    // CAKES & PASTRIES (9 Items)
    { id: 22, name: "Roll Cake (Chocolate)", category: "CAKES & PASTRIES", price: 20.00, desc: "Fluffy chocolate sponge cake roll filled with fudge.", img: "https://i.pinimg.com/736x/b0/4c/d1/b04cd16f0b8ed4dd3829441136257918.jpg" },
    { id: 23, name: "Roll Cake (Mocha)", category: "CAKES & PASTRIES", price: 20.00, desc: "Mocha coffee infused sponge roll with buttercream.", img: "https://i.pinimg.com/1200x/2c/ef/36/2cef36843f7fc164ad3ea5d5cd710847.jpg" },
    { id: 24, name: "Roll Cake (Ube)", category: "CAKES & PASTRIES", price: 20.00, desc: "Ube infused sponge roll with ube flavor.", img: "https://i.pinimg.com/736x/06/5b/c1/065bc1f0513591154022c9200afcfd9d.jpg" },
    { id: 25, name: "Chiffon Cake Slice", category: "CAKES & PASTRIES", price: 20.00, desc: "Light, airy slice of vanilla sponge chiffon cake.", img: "https://i.pinimg.com/736x/3b/5c/af/3b5cafd677242f029ac2242edc6b4fd0.jpg" },
    { id: 26, name: "Egg Pie (Whole)", category: "CAKES & PASTRIES", price: 160.00, desc: "Rich, creamy egg custard pie in a flaky pastry crust.", img: "https://i.pinimg.com/1200x/38/fb/34/38fb340666d2d1803f80b8ba8d61c31f.jpg" },
    { id: 27, name: "Egg Pie (Slice)", category: "CAKES & PASTRIES", price: 25.00, desc: "Slice of rich egg custard pie with golden toasted top.", img: "https://i.pinimg.com/1200x/3d/7b/a1/3d7ba161d0553b76af188cbd82df51d4.jpg" },
    { id: 28, name: "Brownies (Box)", category: "CAKES & PASTRIES", price: 10.00, desc: "Fudgy chocolate brownie squares topped with nuts.", img: "https://i.pinimg.com/736x/27/83/69/278369350d38276479a291db7f62525a.jpg" },
    { id: 29, name: "Banana Bread Loaf", category: "CAKES & PASTRIES", price: 20.00, desc: "Moist banana bread made with sweet local bananas.", img: "https://i.pinimg.com/1200x/80/e4/43/80e4433103ee934029466974fb4ff9d8.jpg" },
    { id: 30, name: "Chocolate Crinkles (Tub)", category: "CAKES & PASTRIES", price: 7.00, desc: "Soft chocolate fudge cookies rolled in powdered sugar.", img: "https://i.pinimg.com/1200x/6f/8a/05/6f8a05c6be94f746d8929ba158d3c094.jpg" },
    { id: 31, name: "Uraro Cookies (Pack)", category: "CAKES & PASTRIES", price: 80.00, desc: "Melt-in-your-mouth arrowroot starch cookies.", img: "https://i.pinimg.com/736x/e4/35/2c/e4352ce0d63371c4cef7014cbf82c7ab.jpg" }
];

let cart = [];
let activeOrders = [];
let historyOrders = [];
let reviewableItems = [];
let currentCategory = "ALL";
let searchQuery = "";
let orderCounter = 42;
let serviceRating = 0;
let rememberMeEnabled = false;
let publishedServiceFeedback = [];

const REMEMBER_KEY = "geminiBakeryRememberMe";
const APP_STATE_KEY = "geminiBakeryDemoState";
const FEEDBACK_KEY = "geminiBakeryPublishedFeedback";

function loadRememberedState() {
    try {
        rememberMeEnabled = localStorage.getItem(REMEMBER_KEY) === "true";
        if (!rememberMeEnabled) return;
        const saved = JSON.parse(localStorage.getItem(APP_STATE_KEY) || "null");
        if (saved) {
            cart = saved.cart || [];
            activeOrders = saved.activeOrders || [];
            historyOrders = saved.historyOrders || [];
            reviewableItems = saved.reviewableItems || [];
            orderCounter = saved.orderCounter || orderCounter;
        }
        publishedServiceFeedback = JSON.parse(localStorage.getItem(FEEDBACK_KEY) || "[]");
    } catch (error) {
        console.warn("Could not load remembered demo data.", error);
    }
}

function saveRememberedState() {
    if (!rememberMeEnabled) return;
    try {
        localStorage.setItem(APP_STATE_KEY, JSON.stringify({ cart, activeOrders, historyOrders, reviewableItems, orderCounter }));
        localStorage.setItem(FEEDBACK_KEY, JSON.stringify(publishedServiceFeedback));
    } catch (error) {
        console.warn("Could not save remembered demo data.", error);
    }
}

function renderPublishedFeedback() {
    const container = document.getElementById("published-feedback-list");
    if (!container) return;
    if (!publishedServiceFeedback.length) {
        container.innerHTML = `<p class="text-xs text-gray-500 bg-amber-50 rounded-xl p-3">No published customer feedback is available yet. Feedback submitted in this demo stays on this device; shared customer reviews require a backend and database.</p>`;
        return;
    }
    container.innerHTML = publishedServiceFeedback.slice().reverse().map(item => `
        <article class="published-feedback-card">
            <div class="flex items-start justify-between gap-2">
                <p class="text-xs font-black text-gemini-dark break-words">${escapeFeedbackText(item.name)}</p>
                <span class="published-feedback-stars text-xs">${"★".repeat(item.rating)}${"☆".repeat(5-item.rating)}</span>
            </div>
            <p class="text-xs text-gray-700 mt-1 break-words">${escapeFeedbackText(item.comment || "Rated the service.")}</p>
            <p class="text-[10px] text-gray-400 mt-2">${escapeFeedbackText(item.date || "Recently submitted")}</p>
        </article>`).join("");
}

function escapeFeedbackText(value) {
    return String(value ?? "").replace(/[&<>"']/g, char => ({"&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#39;"}[char]));
}

// DOM Elements
const landingPortal = document.getElementById("landing-portal");
const enterStoreBtn = document.getElementById("enter-store-btn");
const viewMenu = document.getElementById("view-menu");
const viewOrders = document.getElementById("view-orders");
const viewReview = document.getElementById("view-review");
const navMenuBtn = document.getElementById("nav-menu-btn");
const navOrdersBtn = document.getElementById("nav-orders-btn");
const navReviewBtn = document.getElementById("nav-review-btn");

window.addEventListener("DOMContentLoaded", () => {
    loadRememberedState();
    initClockAndStatus();
    renderProducts();
    updateCartUI();

    const welcomeModal = document.getElementById("welcome-modal");
    const rememberCheckbox = document.getElementById("remember-me-checkbox");
    if (rememberCheckbox) {
        rememberCheckbox.checked = rememberMeEnabled;
    }

    if (enterStoreBtn) {
        enterStoreBtn.addEventListener("click", () => {
            if (welcomeModal) welcomeModal.classList.remove("opacity-0", "pointer-events-none");
        });
    }

    const continueGuestBtn = document.getElementById("continue-as-guest-btn");
    if (continueGuestBtn) {
        continueGuestBtn.addEventListener("click", () => {
            rememberMeEnabled = rememberCheckbox ? rememberCheckbox.checked : false;
            localStorage.setItem(REMEMBER_KEY, rememberMeEnabled ? "true" : "false");
            if (!rememberMeEnabled) {
                localStorage.removeItem(APP_STATE_KEY);
                localStorage.removeItem(FEEDBACK_KEY);
                publishedServiceFeedback = [];
            } else {
                saveRememberedState();
            }
            if (welcomeModal) welcomeModal.classList.add("opacity-0", "pointer-events-none");
            if (landingPortal) landingPortal.classList.add("opacity-0", "pointer-events-none");
            updateCartUI();
            renderProducts();
            renderOrdersUI();
            renderReviewableProductsUI();
            renderPublishedFeedback();
        });
    }

    if (navMenuBtn) navMenuBtn.addEventListener("click", () => switchView("MENU"));
    if (navOrdersBtn) navOrdersBtn.addEventListener("click", () => switchView("ORDERS"));
    if (navReviewBtn) navReviewBtn.addEventListener("click", () => switchView("REVIEW"));
    
    const brandHome = document.getElementById("nav-brand-home");
    if (brandHome) brandHome.addEventListener("click", () => switchView("MENU"));

    const searchInput = document.getElementById("search-input");
    if (searchInput) {
        searchInput.addEventListener("input", (e) => {
            searchQuery = e.target.value.toLowerCase();
            renderProducts();
        });
    }

    const categoryFilters = document.getElementById("category-filters");
    if (categoryFilters) {
        categoryFilters.addEventListener("click", (e) => {
            const btn = e.target.closest(".cat-btn");
            if (!btn) return;
            document.querySelectorAll(".cat-btn").forEach(b => {
                b.classList.remove("active", "bg-gemini-red", "text-white");
                b.classList.add("bg-amber-50", "text-gemini-dark");
            });
            btn.classList.add("active", "bg-gemini-red", "text-white");
            btn.classList.remove("bg-amber-50", "text-gemini-dark");
            currentCategory = btn.dataset.category;
            renderProducts();
        });
    }

    const openCartBtn = document.getElementById("open-cart-btn");
    if (openCartBtn) openCartBtn.addEventListener("click", openCartDrawer);

    const closeCartBtn = document.getElementById("close-cart-btn");
    if (closeCartBtn) closeCartBtn.addEventListener("click", closeCartDrawer);

    const cartDrawerOverlay = document.getElementById("cart-drawer-overlay");
    if (cartDrawerOverlay) {
        cartDrawerOverlay.addEventListener("click", (e) => {
            if (e.target === e.currentTarget) closeCartDrawer();
        });
    }

    const clearCartBtn = document.getElementById("clear-cart-btn");
    if (clearCartBtn) {
        clearCartBtn.addEventListener("click", () => {
            cart = [];
            updateCartUI();
            saveRememberedState();
        });
    }

    const radioPickup = document.getElementById("radio-pickup");
    if (radioPickup) radioPickup.addEventListener("change", handleOrderTypeChange);

    const radioDelivery = document.getElementById("radio-delivery");
    if (radioDelivery) radioDelivery.addEventListener("change", handleOrderTypeChange);

    const checkoutForm = document.getElementById("checkout-form");
    if (checkoutForm) checkoutForm.addEventListener("submit", handleCheckoutProcess);
    
    const closeModalBtn = document.getElementById("close-modal-btn");
    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", () => {
            const receiptModal = document.getElementById("receipt-modal");
            if (receiptModal) receiptModal.classList.add("opacity-0", "pointer-events-none");
        });
    }

    const finishOrderBtn = document.getElementById("finish-order-btn");
    if (finishOrderBtn) {
        finishOrderBtn.addEventListener("click", () => {
            const receiptModal = document.getElementById("receipt-modal");
            if (receiptModal) receiptModal.classList.add("opacity-0", "pointer-events-none");
            switchView("ORDERS");
        });
    }

    setupServiceRating();
    setupProductReviewStars();
    renderOrdersUI();
    renderPublishedFeedback();
});

function switchView(view) {
    [viewMenu, viewOrders, viewReview].forEach(v => v.classList.add("hidden"));
    [navMenuBtn, navOrdersBtn, navReviewBtn].forEach(b => {
        b.className = "nav-tab px-3 py-1.5 rounded-lg text-amber-100 hover:text-white flex items-center gap-1.5 transition";
    });

    if (view === "MENU") {
        viewMenu.classList.remove("hidden");
        navMenuBtn.className = "nav-tab active px-3 py-1.5 rounded-lg bg-gemini-yellow text-gemini-dark shadow font-extrabold flex items-center gap-1.5 transition";
    } else if (view === "ORDERS") {
        viewOrders.classList.remove("hidden");
        navOrdersBtn.className = "nav-tab active px-3 py-1.5 rounded-lg bg-gemini-yellow text-gemini-dark shadow font-extrabold flex items-center gap-1.5 transition";
        renderOrdersUI();
    } else if (view === "REVIEW") {
        viewReview.classList.remove("hidden");
        navReviewBtn.className = "nav-tab active px-3 py-1.5 rounded-lg bg-gemini-yellow text-gemini-dark shadow font-extrabold flex items-center gap-1.5 transition";
        renderReviewableProductsUI();
        renderPublishedFeedback();
    }
}

function initClockAndStatus() {
    function updateTime() {
        const now = new Date();
        const options = { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' };
        document.getElementById("live-date").textContent = now.toLocaleDateString('en-US', options);
        document.getElementById("live-time").textContent = now.toLocaleTimeString('en-US');

        const hour = now.getHours();
        const isOpen = hour >= 5 && hour < 20;

        const mainBadge = document.getElementById("store-status-badge");
        if (isOpen) {
            mainBadge.className = "px-3 py-0.5 rounded-full text-xs font-black tracking-wide uppercase flex items-center gap-1.5 bg-emerald-100 text-emerald-800 border border-emerald-300 animate-pulse-ring";
            mainBadge.innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-500"></span> OPEN NOW`;
        } else {
            mainBadge.className = "px-3 py-0.5 rounded-full text-xs font-black tracking-wide uppercase flex items-center gap-1.5 bg-red-100 text-red-800 border border-red-300";
            mainBadge.innerHTML = `<span class="w-2 h-2 rounded-full bg-red-500"></span> STORE CLOSED`;
        }

        const branchTags = document.querySelectorAll(".branch-status-tag");
        branchTags.forEach(tag => {
            if (isOpen) {
                tag.className = "branch-status-tag text-[10px] font-bold px-2 py-0.5 rounded uppercase bg-emerald-100 text-emerald-800 border border-emerald-300";
                tag.textContent = "OPEN";
            } else {
                tag.className = "branch-status-tag text-[10px] font-bold px-2 py-0.5 rounded uppercase bg-red-100 text-red-800 border border-red-300";
                tag.textContent = "CLOSED";
            }
        });
    }

    updateTime();
    setInterval(updateTime, 1000);
}

function renderProducts() {
    const grid = document.getElementById("product-grid");
    const noResults = document.getElementById("no-results");
    const filtered = PRODUCTS.filter(p => {
        const matchesCat = (currentCategory === "ALL" || p.category === currentCategory);
        const matchesSearch = p.name.toLowerCase().includes(searchQuery) || p.desc.toLowerCase().includes(searchQuery);
        return matchesCat && matchesSearch;
    });

    if (filtered.length === 0) {
        grid.innerHTML = "";
        noResults.classList.remove("hidden");
        return;
    }

    noResults.classList.add("hidden");
    grid.innerHTML = filtered.map(product => {
        const inCart = cart.find(item => item.id === product.id);
        const cartQty = inCart ? inCart.qty : 0;
        return `
        <div class="product-card bg-white rounded-2xl overflow-hidden border border-red-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="relative h-40 w-full bg-amber-50 overflow-hidden">
                    <img src="${product.img}" alt="${product.name}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" loading="lazy">
                    <span class="absolute top-2 left-2 bg-gemini-red text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider shadow">
                        ${product.category}
                    </span>
                </div>
                <div class="p-3.5 space-y-1">
                    <div class="flex justify-between items-start gap-1">
                        <h3 class="font-extrabold text-gemini-dark text-xs sm:text-sm leading-snug">${product.name}</h3>
                        <span class="font-black text-gemini-red text-xs sm:text-sm bg-red-50 px-2 py-0.5 rounded-md shrink-0">₱${product.price.toFixed(2)}</span>
                    </div>
                    <p class="text-[11px] text-gray-500 line-clamp-2 leading-normal">${product.desc}</p>
                </div>
            </div>
            <div class="p-3.5 pt-0">
                ${cartQty > 0 ? `
                    <div class="flex items-center justify-between bg-amber-100/80 border border-amber-300 rounded-xl p-1">
                        <button onclick="updateQty(${product.id}, -1)" class="w-7 h-7 rounded-lg bg-white text-gemini-dark font-bold hover:bg-amber-200 transition shadow-sm flex items-center justify-center">-</button>
                        <span class="font-black text-xs text-gemini-dark">${cartQty} in basket</span>
                        <button onclick="updateQty(${product.id}, 1)" class="w-7 h-7 rounded-lg bg-gemini-red text-white font-bold hover:bg-red-800 transition shadow-sm flex items-center justify-center">+</button>
                    </div>
                ` : `
                    <button onclick="addToCart(${product.id})" class="w-full py-2 bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-300 text-gemini-dark font-black text-xs rounded-xl shadow transition transform active:scale-95 flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-cart-plus"></i><span>Add to Basket</span>
                    </button>
                `}
            </div>
        </div>`;
    }).join("");
}

function addToCart(id) {
    const p = PRODUCTS.find(item => item.id === id);
    if (!p) return;
    const existing = cart.find(item => item.id === id);
    if (existing) existing.qty++;
    else cart.push({ ...p, qty: 1 });
    updateCartUI();
    renderProducts();
    saveRememberedState();
}

function updateQty(id, change) {
    const idx = cart.findIndex(item => item.id === id);
    if (idx !== -1) {
        cart[idx].qty += change;
        if (cart[idx].qty <= 0) cart.splice(idx, 1);
    }
    updateCartUI();
    renderProducts();
    saveRememberedState();
}

function handleOrderTypeChange() {
    const isDel = document.getElementById("radio-delivery").checked;
    document.getElementById("lbl-delivery").className = isDel ? "border-2 border-gemini-red bg-amber-50 p-2.5 rounded-xl flex items-center justify-center space-x-2 cursor-pointer transition text-xs font-bold text-gemini-dark" : "border-2 border-gray-200 bg-white p-2.5 rounded-xl flex items-center justify-center space-x-2 cursor-pointer transition text-xs font-bold text-gemini-dark";
    document.getElementById("lbl-pickup").className = !isDel ? "border-2 border-gemini-red bg-amber-50 p-2.5 rounded-xl flex items-center justify-center space-x-2 cursor-pointer transition text-xs font-bold text-gemini-dark" : "border-2 border-gray-200 bg-white p-2.5 rounded-xl flex items-center justify-center space-x-2 cursor-pointer transition text-xs font-bold text-gemini-dark";
    const paymentSelect = document.getElementById("payment-method-select");
    const counterOption = document.getElementById("payment-counter-option");
    const codOption = document.getElementById("payment-cod-option");

    counterOption.disabled = isDel;
    codOption.disabled = !isDel;
    paymentSelect.value = isDel ? "Cash on Delivery" : "Cash on Counter";

    updateCartUI();
}

function updateCartUI() {
    const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
    const cartBadge = document.getElementById("cart-badge");
    cartBadge.textContent = totalItems;
    cartBadge.classList.toggle("hidden", totalItems === 0);
    document.getElementById("clear-cart-btn").classList.toggle("hidden", totalItems === 0);
    document.getElementById("place-order-btn").disabled = totalItems === 0;
    document.getElementById("empty-cart-msg").classList.toggle("hidden", totalItems > 0);

    document.getElementById("cart-items-container").innerHTML = cart.map(item => `
        <div class="flex items-center justify-between bg-amber-50/80 p-2 rounded-xl border border-amber-200">
            <div class="flex items-center space-x-2.5">
                <img src="${item.img}" class="w-10 h-10 object-cover rounded-lg border border-amber-200" alt="${item.name}">
                <div>
                    <h4 class="font-extrabold text-xs text-gemini-dark">${item.name}</h4>
                    <p class="text-[11px] text-gemini-red font-semibold">₱${item.price.toFixed(2)} x ${item.qty}</p>
                </div>
            </div>
            <div class="flex items-center space-x-1">
                <button onclick="updateQty(${item.id}, -1)" class="w-5 h-5 rounded bg-white text-gemini-dark border border-amber-300 font-bold text-xs">-</button>
                <span class="text-xs font-black text-gemini-dark w-4 text-center">${item.qty}</span>
                <button onclick="updateQty(${item.id}, 1)" class="w-5 h-5 rounded bg-gemini-red text-white font-bold text-xs">+</button>
            </div>
        </div>
    `).join("");

    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const isDelivery = document.getElementById("radio-delivery").checked;
    const shippingFee = isDelivery ? 49.00 : 0.00;
    const grandTotal = subtotal + shippingFee;

    document.getElementById("summary-subtotal").textContent = `₱${subtotal.toFixed(2)}`;
    document.getElementById("summary-shipping").textContent = isDelivery ? "₱49.00" : "FREE";
    document.getElementById("summary-total").textContent = `₱${grandTotal.toFixed(2)}`;
}

function openCartDrawer() {
    document.getElementById("cart-drawer-overlay").classList.remove("opacity-0", "pointer-events-none");
    document.getElementById("cart-drawer").classList.remove("translate-x-full");
}

function closeCartDrawer() {
    document.getElementById("cart-drawer-overlay").classList.add("opacity-0", "pointer-events-none");
    document.getElementById("cart-drawer").classList.add("translate-x-full");
}

function handleCheckoutProcess(e) {
    e.preventDefault();
    if (cart.length === 0) return;

    const name = document.getElementById("cust-name").value.trim();
    const phoneInput = document.getElementById("cust-phone");
    const phone = phoneInput.value.trim();
    const address = document.getElementById("cust-address").value.trim();

    if (!/^09[0-9]{9}$/.test(phone)) {
        alert("Enter exactly 11 digits starting with 09.");
        phoneInput.focus();
        return;
    }
    const schedule = document.getElementById("cust-schedule").value;
    const isDelivery = document.getElementById("radio-delivery").checked;
    const paymentMethod = document.getElementById("payment-method-select").value;

    closeCartDrawer();
    document.getElementById("loading-overlay").classList.remove("opacity-0", "pointer-events-none");

    setTimeout(() => {
        document.getElementById("loading-overlay").classList.add("opacity-0", "pointer-events-none");

        orderCounter++;
        const queueNo = "#" + String(orderCounter).padStart(3, '0');
        const orderId = "GEMINI-" +
            new Date().getFullYear() + "-" +
            Date.now().toString().slice(-8) + "-" +
            Math.floor(100 + Math.random() * 900);
        const subtotal = cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
        const shippingFee = isDelivery ? 49.00 : 0.00;
        const grandTotal = subtotal + shippingFee;
        const timestamp = new Date().toLocaleString('en-US', { dateStyle: 'medium', timeStyle: 'short' });

        let paymentStatus = "Pay at Counter";
        if (paymentMethod === "Cash on Delivery") {
            paymentStatus = "Pay on Delivery";
        }

        const newOrder = {
            orderId,
            queueNo,
            timestamp,
            name,
            phone,
            address,
            type: isDelivery ? "DELIVERY" : "PICKUP",
            schedule,
            items: [...cart],
            subtotal,
            shippingFee,
            grandTotal,
            paymentMethod,
            paymentStatus,
            status: isDelivery ? "Out for Delivery" : "Ready for Pickup"
        };

        activeOrders.unshift(newOrder);
        saveRememberedState();

        document.getElementById("receipt-queue-no").textContent = queueNo;
        document.getElementById("receipt-order-id").textContent = orderId;
        document.getElementById("receipt-timestamp").textContent = timestamp;
        document.getElementById("receipt-cust-name").textContent = name;
        document.getElementById("receipt-cust-phone").textContent = phone;
        document.getElementById("receipt-cust-type").textContent = newOrder.type;
        document.getElementById("receipt-pay-method").textContent = paymentMethod;
        document.getElementById("receipt-pay-status").textContent = paymentStatus;
        document.getElementById("receipt-subtotal").textContent = `₱${subtotal.toFixed(2)}`;
        document.getElementById("receipt-shipping").textContent = `₱${shippingFee.toFixed(2)}`;
        document.getElementById("receipt-grand-total").textContent = `₱${grandTotal.toFixed(2)}`;
        document.getElementById("receipt-items-list").innerHTML = cart.map(i => `
            <div class="flex justify-between items-center text-[10px]">
                <span class="font-bold text-gray-800">${i.name}</span>
                <span>${i.qty} x ₱${i.price.toFixed(2)} = <strong>₱${(i.qty * i.price).toFixed(2)}</strong></span>
            </div>
        `).join("");

        document.getElementById("receipt-modal").classList.remove("opacity-0", "pointer-events-none");

        cart = [];
        document.getElementById("checkout-form").reset();
        document.getElementById("radio-pickup").checked = true;
        handleOrderTypeChange();
        updateCartUI();
        renderProducts();
        renderOrdersUI();
    }, 1500);
}

function renderOrdersUI() {
    const activeList = document.getElementById("active-orders-list");
    const noActive = document.getElementById("no-active-orders");
    const historyList = document.getElementById("history-orders-list");
    const noHistory = document.getElementById("no-history-orders");

    document.getElementById("active-orders-count").textContent = `${activeOrders.length} Active`;

    if (activeOrders.length === 0) {
        activeList.innerHTML = "";
        noActive.classList.remove("hidden");
    } else {
        noActive.classList.add("hidden");
        activeList.innerHTML = activeOrders.map((o, idx) => `
            <div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-amber-200 space-y-3">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-red-100 pb-3 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-black text-gemini-red text-base">${o.queueNo}</span>
                            <span class="text-xs bg-amber-100 text-gemini-dark font-bold px-2 py-0.5 rounded">${o.orderId}</span>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-0.5">${o.timestamp} • Type: <strong class="text-gemini-dark">${o.type}</strong></p>
                    </div>
                    <span class="px-3 py-1 bg-amber-100 text-amber-900 rounded-full text-xs font-black uppercase tracking-wide">
                        Status: ${o.status}
                    </span>
                </div>

                <div class="text-xs space-y-1 bg-amber-50/50 p-3 rounded-xl">
                    <p><strong>Customer:</strong> ${o.name} (${o.phone})</p>
                    <p><strong>Delivery/Pickup Address:</strong> ${o.address}</p>
                    <p><strong>Payment:</strong> ${o.paymentMethod} (<span class="text-emerald-700 font-bold">${o.paymentStatus}</span>)</p>
                </div>

                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-700">Ordered Items:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        ${o.items.map(i => `
                            <div class="flex items-center justify-between bg-gray-50 p-2 rounded-lg text-xs">
                                <span>${i.name} (x${i.qty})</span>
                                <span class="font-bold">₱${(i.price * i.qty).toFixed(2)}</span>
                            </div>
                        `).join("")}
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-red-100">
                    <span class="text-sm font-black text-gemini-red">Total: ₱${o.grandTotal.toFixed(2)}</span>
                    <button onclick="markOrderReceived(${idx})" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-xl shadow transition flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i> Order Received
                    </button>
                </div>
            </div>
        `).join("");
    }

    if (historyOrders.length === 0) {
        historyList.innerHTML = "";
        noHistory.classList.remove("hidden");
    } else {
        noHistory.classList.add("hidden");
        historyList.innerHTML = historyOrders.map(o => `
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 text-xs space-y-2">
                <div class="flex justify-between items-center font-bold">
                    <span class="text-gemini-red">${o.queueNo} (${o.orderId})</span>
                    <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Completed / Received</span>
                </div>
                <div class="text-gray-600 grid grid-cols-1 sm:grid-cols-2 gap-1">
                    <p><strong>Customer:</strong> ${o.name}</p>
                    <p><strong>Date:</strong> ${o.timestamp}</p>
                    <p><strong>Total Amount:</strong> ₱${o.grandTotal.toFixed(2)} (${o.paymentMethod})</p>
                    <p><strong>Items:</strong> ${o.items.map(i => `${i.name} (x${i.qty})`).join(', ')}</p>
                </div>
            </div>
        `).join("");
    }
}

function markOrderReceived(idx) {
    const order = activeOrders[idx];
    
    if (!["Ready for Pickup", "Out for Delivery", "Delivered or Ready for Collection"].includes(order.status)) {
        alert("This order is not yet eligible to be marked as received.");
        return;
    }

    document.getElementById("loading-overlay").classList.remove("opacity-0", "pointer-events-none");

    setTimeout(() => {
        document.getElementById("loading-overlay").classList.add("opacity-0", "pointer-events-none");

        order.status = "Received / Completed";
        order.paymentStatus = "Paid / Settled";
        historyOrders.unshift(order);
        activeOrders.splice(idx, 1);

        order.items.forEach(item => {
            reviewableItems.push({
                ...item,
                orderId: order.orderId,
                reviewed: false,
                rating: 0,
                comment: ""
            });
        });

        renderOrdersUI();
        saveRememberedState();

        const thankyouModal = document.getElementById("thankyou-modal");
        thankyouModal.classList.remove("opacity-0", "pointer-events-none");

        document.getElementById("thankyou-continue-btn").onclick = () => {
            thankyouModal.classList.add("opacity-0", "pointer-events-none");
            switchView("REVIEW");
        };
    }, 1000);
}

function setupServiceRating() {
    const stars = document.querySelectorAll("#service-star-container i");
    const ratingText = document.getElementById("service-rating-text");
    
    stars.forEach(star => {
        star.addEventListener("click", () => {
            serviceRating = parseInt(star.dataset.rating);
            stars.forEach(s => {
                s.classList.toggle("active", parseInt(s.dataset.rating) <= serviceRating);
            });
            const labels = ["", "1 Star - Poor", "2 Stars - Fair", "3 Stars - Good", "4 Stars - Very Good!", "5 Stars - Excellent! 🔥"];
            ratingText.textContent = labels[serviceRating];
        });
    });

    document.getElementById("service-feedback-form").addEventListener("submit", (e) => {
        e.preventDefault();
        if (serviceRating === 0) {
            alert("Mangyaring pumili muna ng Service Star Rating (1 to 5 stars).");
            return;
        }
        const feedbackName = document.getElementById("service-author").value.trim();
        const feedbackComment = document.getElementById("service-comment").value.trim();
        publishedServiceFeedback.push({
            name: feedbackName,
            rating: serviceRating,
            comment: feedbackComment,
            date: new Date().toLocaleDateString()
        });
        saveRememberedState();
        renderPublishedFeedback();
        alert("Salamat po! Na-submit na ang inyong Service Feedback sa Gemini Bakery.");
        document.getElementById("service-feedback-form").reset();
        serviceRating = 0;
        stars.forEach(s => s.classList.remove("active"));
        ratingText.textContent = "Pumili ng 1-5 stars";
    });
}

function renderReviewableProductsUI() {
    const container = document.getElementById("product-review-cards-container");
    const noReviewMsg = document.getElementById("no-reviewable-products");

    if (reviewableItems.length === 0) {
        container.innerHTML = "";
        noReviewMsg.classList.remove("hidden");
        return;
    }

    noReviewMsg.classList.add("hidden");
    container.innerHTML = reviewableItems.map((item, idx) => `
        <div class="bg-amber-50/60 p-4 rounded-2xl border border-amber-200 space-y-3">
            <div class="flex items-center space-x-3">
                <img src="${item.img}" class="w-14 h-14 object-cover rounded-xl border border-amber-300" alt="${item.name}">
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <h4 class="font-extrabold text-sm text-gemini-dark">${item.name}</h4>
                        <span class="text-[10px] font-bold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded">Qty Purchased: ${item.qty}</span>
                    </div>
                    <p class="text-[11px] text-gray-500">Order ID: ${item.orderId}</p>
                </div>
            </div>

            <div class="space-y-2 pt-2 border-t border-amber-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gemini-dark">Product Rating:</span>
                    <div class="star-rating product-stars-${idx} flex items-center space-x-1 text-xl text-gray-300" data-index="${idx}">
                        <i class="fa-solid fa-star" data-val="1"></i>
                        <i class="fa-solid fa-star" data-val="2"></i>
                        <i class="fa-solid fa-star" data-val="3"></i>
                        <i class="fa-solid fa-star" data-val="4"></i>
                        <i class="fa-solid fa-star" data-val="5"></i>
                    </div>
                </div>

                <textarea id="prod-comment-${idx}" rows="2" placeholder="Masarap at bagong hurno ang tinapay na ito!"
                    class="w-full px-3 py-2 bg-white border border-amber-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-gemini-red focus:outline-none" ${item.reviewed ? 'disabled' : ''}>${item.comment}</textarea>

                <div class="flex justify-between items-center">
                    <span class="text-[11px] font-bold ${item.reviewed ? 'text-emerald-700' : 'text-amber-800'}">
                        Status: ${item.reviewed ? 'Reviewed ✓' : 'Not Reviewed'}
                    </span>
                    <button onclick="submitProductReview(${idx})" class="px-4 py-2 bg-gemini-red hover:bg-red-800 text-white font-bold text-xs rounded-xl shadow transition" ${item.reviewed ? 'disabled style="opacity:0.6; cursor:not-allowed;"' : ''}>
                        ${item.reviewed ? 'Review Submitted' : 'Submit Product Review'}
                    </button>
                </div>
            </div>
        </div>
    `).join("");

    reviewableItems.forEach((item, idx) => {
        const starContainer = document.querySelector(`.product-stars-${idx}`);
        if (!starContainer || item.reviewed) return;
        starContainer.querySelectorAll("i").forEach(star => {
            star.addEventListener("click", () => {
                const val = parseInt(star.dataset.val);
                reviewableItems[idx].rating = val;
                starContainer.querySelectorAll("i").forEach(s => {
                    s.classList.toggle("active", parseInt(s.dataset.val) <= val);
                });
            });
        });
        if (item.rating > 0) {
            starContainer.querySelectorAll("i").forEach(s => {
                s.classList.toggle("active", parseInt(s.dataset.val) <= item.rating);
            });
        }
    });
}

function submitProductReview(idx) {
    const item = reviewableItems[idx];
    if (item.rating === 0) {
        alert("Mangyaring pumili muna ng Star Rating (1 to 5) para sa produktong ito.");
        return;
    }
    const commentInput = document.getElementById(`prod-comment-${idx}`);
    item.comment = commentInput.value.trim();
    item.reviewed = true;

    alert(`Salamat! Ang review mo para sa "${item.name}" ay matagumpay nang na-submit.`);
    renderReviewableProductsUI();
}

document.addEventListener("DOMContentLoaded", function () {
    const phoneField = document.getElementById("cust-phone");
    if (!phoneField) return;
    phoneField.addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, "").slice(0, 11);
    });
});