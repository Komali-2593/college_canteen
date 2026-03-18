<?php
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$input   = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['items'])) {
    echo json_encode(['success' => false, 'message' => 'No items received']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO orders (user_id, item, quantity, price, total_price) VALUES (?, ?, ?, ?, ?)");

$inserted = 0;
foreach ($input['items'] as $item) {
    $item_name   = htmlspecialchars(strip_tags($item['name']));
    $quantity    = intval($item['quantity']);
    $price       = floatval($item['price']);
    $total_price = $quantity * $price;

    $stmt->bind_param("isids", $user_id, $item_name, $quantity, $price, $total_price);
    if ($stmt->execute()) {
        $inserted++;
    }
}

$conn->close();

if ($inserted > 0) {
    echo json_encode(['success' => true, 'message' => "$inserted item(s) ordered successfully!"]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to place order.']);
}
?>
