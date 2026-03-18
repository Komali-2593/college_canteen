<?php
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id   = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$stmt = $conn->prepare("SELECT item, quantity, price, total_price, ordered_at FROM orders WHERE user_id = ? ORDER BY ordered_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders     = [];
$grand_total = 0;
while ($row = $result->fetch_assoc()) {
    $orders[]    = $row;
    $grand_total += $row['total_price'];
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders | CanteenU</title>
  <link rel="stylesheet" href="menu.css">
</head>
<body>
  <nav class="navbar">
    <a href="index.php" class="logo">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary-color)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path>
        <path d="M7 2v20"></path>
        <path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path>
      </svg>
      Canteen<span>U</span>
    </a>
    <ul class="nav-links">
      <li><a href="index.php">Menu</a></li>
      <li><a href="orders.php" class="active">My Orders</a></li>
      <li class="nav-user">
        Hi, <?= htmlspecialchars($user_name) ?> &nbsp;|&nbsp; <a href="logout.php">Logout</a>
      </li>
    </ul>
  </nav>

  <div class="container" style="padding-top: 3rem; padding-bottom: 5rem;">
    <h2 class="section-title animate-fade-up">My Orders</h2>

    <?php if (empty($orders)): ?>
      <div class="empty-orders animate-fade-up">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1.5">
          <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
        <h3>No orders yet</h3>
        <p>Head back to the menu and place your first order!</p>
        <a href="index.php" class="btn" style="width:auto; margin-top:1rem;">Browse Menu</a>
      </div>
    <?php else: ?>
      <div class="orders-table-wrapper animate-fade-up">
        <table class="orders-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Item</th>
              <th>Qty</th>
              <th>Unit Price</th>
              <th>Total</th>
              <th>Date & Time</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $i => $order): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><strong><?= htmlspecialchars($order['item']) ?></strong></td>
              <td><?= $order['quantity'] ?></td>
              <td>₹<?= number_format($order['price'], 2) ?></td>
              <td class="total-cell">₹<?= number_format($order['total_price'], 2) ?></td>
              <td class="date-cell"><?= date('d M Y, h:i A', strtotime($order['ordered_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" style="text-align:right; font-weight:600;">Grand Total</td>
              <td class="grand-total" colspan="2">₹<?= number_format($grand_total, 2) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <footer>
    <p>&copy; 2026 CanteenU. All rights reserved.</p>
  </footer>
</body>
</html>
