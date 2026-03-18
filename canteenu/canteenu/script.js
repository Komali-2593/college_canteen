// ===========================
// CanteenU - Cart & UI Logic
// ===========================

let cart = JSON.parse(localStorage.getItem('canteenu_cart')) || [];

// ---- Cart Persistence ----
function saveCart() {
  localStorage.setItem('canteenu_cart', JSON.stringify(cart));
}

// ---- Add to Cart ----
function addToCart(name, price) {
  const existing = cart.find(i => i.name === name);
  if (existing) {
    existing.quantity++;
  } else {
    cart.push({ name, price, quantity: 1 });
  }
  saveCart();
  renderCart();
  showToast(`${name} added to cart!`);

  // Briefly open cart to show feedback
  const sidebar = document.getElementById('cartSidebar');
  if (!sidebar.classList.contains('open')) {
    toggleCart();
    setTimeout(() => {
      if (sidebar.classList.contains('open')) toggleCart();
    }, 1800);
  }
}

// ---- Change Quantity ----
function changeQty(name, delta) {
  const item = cart.find(i => i.name === name);
  if (!item) return;
  item.quantity += delta;
  if (item.quantity <= 0) {
    cart = cart.filter(i => i.name !== name);
  }
  saveCart();
  renderCart();
}

// ---- Remove Item ----
function removeItem(name) {
  cart = cart.filter(i => i.name !== name);
  saveCart();
  renderCart();
}

// ---- Render Cart ----
function renderCart() {
  const container  = document.getElementById('cartItems');
  const emptyMsg   = document.getElementById('cartEmpty');
  const countBadge = document.getElementById('cartCount');
  const totalEl    = document.getElementById('cartTotal');

  const totalQty   = cart.reduce((s, i) => s + i.quantity, 0);
  const totalPrice = cart.reduce((s, i) => s + i.price * i.quantity, 0);

  countBadge.textContent = totalQty;
  countBadge.style.display = totalQty > 0 ? 'flex' : 'none';
  totalEl.textContent = `₹${totalPrice}`;

  if (cart.length === 0) {
    container.innerHTML = '';
    container.appendChild(emptyMsg);
    emptyMsg.style.display = 'flex';
    return;
  }

  emptyMsg.style.display = 'none';

  let html = '';
  cart.forEach(item => {
    html += `
      <div class="cart-item">
        <div class="cart-item-info">
          <span class="cart-item-name">${item.name}</span>
          <span class="cart-item-price">₹${item.price} × ${item.quantity} = ₹${item.price * item.quantity}</span>
        </div>
        <div class="cart-item-controls">
          <button class="qty-btn" onclick="changeQty('${item.name}', -1)">−</button>
          <span class="qty-val">${item.quantity}</span>
          <button class="qty-btn" onclick="changeQty('${item.name}', 1)">+</button>
          <button class="remove-btn" onclick="removeItem('${item.name}')" title="Remove">🗑</button>
        </div>
      </div>`;
  });

  container.innerHTML = html;
}

// ---- Toggle Cart Sidebar ----
function toggleCart() {
  const sidebar = document.getElementById('cartSidebar');
  const overlay = document.getElementById('cartOverlay');
  sidebar.classList.toggle('open');
  overlay.classList.toggle('show');
  document.body.classList.toggle('cart-open');
}

// ---- Place Order ----
async function placeOrder() {
  if (!IS_LOGGED_IN) {
    showToast('Please login to place an order!', 'error');
    setTimeout(() => { window.location.href = 'login.php'; }, 1500);
    return;
  }

  if (cart.length === 0) {
    showToast('Your cart is empty!', 'error');
    return;
  }

  const btn = document.getElementById('placeOrderBtn');
  btn.disabled = true;
  btn.textContent = 'Placing Order...';

  try {
    const res = await fetch('order.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ items: cart })
    });

    const data = await res.json();

    if (data.success) {
      // Save to localStorage for HTML orders page too
      savePastOrder(cart);
      cart = [];
      saveCart();
      renderCart();
      toggleCart();
      showToast('🎉 Order placed successfully!', 'success');
      setTimeout(() => { window.location.href = 'orders.php'; }, 2000);
    } else {
      showToast(data.message || 'Order failed!', 'error');
    }
  } catch (err) {
    showToast('Network error. Please try again.', 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Place Order';
  }
}

// ---- Category Filter ----
function filterCategory(category, btn) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  document.querySelectorAll('.card').forEach(card => {
    if (category === 'all' || card.dataset.category === category) {
      card.style.display = '';
      card.style.animation = 'fadeInUp 0.4s ease forwards';
    } else {
      card.style.display = 'none';
    }
  });
}

// ---- Toast Notification ----
function showToast(msg, type = 'info') {
  const toast = document.getElementById('toast');
  toast.textContent = msg;
  toast.className = `toast show ${type}`;
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => { toast.className = 'toast'; }, 3000);
}

// ---- Save Past Order (for HTML orders page) ----
function savePastOrder(items) {
  const past = JSON.parse(localStorage.getItem('canteenu_past_orders') || '[]');
  past.unshift({
    items: items.map(i => ({ name: i.name, price: i.price, quantity: i.quantity })),
    date: new Date().toLocaleString('en-IN', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
  });
  localStorage.setItem('canteenu_past_orders', JSON.stringify(past));
}

// ---- Init ----
document.addEventListener('DOMContentLoaded', () => {
  renderCart();
});
