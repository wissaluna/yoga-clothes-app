<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';
include 'includes/header.php';


if (!isset($_SESSION['user_id'])) {
    echo "<div class='auth-card' style='text-align: center;'><h2>Please <a href='login.php' style='color: #1abc9c;'>log in</a> to checkout.</h2></div>";
    include 'includes/footer.php';
    exit();
}

if (empty($_SESSION['cart'])) {
    echo "<div class='auth-card' style='text-align: center;'><h2>Your cart is empty.</h2><br><a href='index.php'><button>Go Shopping</button></a></div>";
    include 'includes/footer.php';
    exit();
}

$message = '';
$statusClass = '';
$orderSuccess = false;

$totalAmount = 0;
$productIds = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($productIds), '?'));
$stmt = $pdo->prepare("SELECT ProductID, Price FROM PRODUCT WHERE ProductID IN ($placeholders)");
$stmt->execute($productIds);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$priceMap = []; 
foreach ($products as $p) {
    $qty = $_SESSION['cart'][$p['ProductID']];
    $totalAmount += ($p['Price'] * $qty);
    $priceMap[$p['ProductID']] = $p['Price'];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardName = trim($_POST['cardName']);
    $cardNumber = trim($_POST['cardNumber']); 
    $expMonth = $_POST['expMonth'];
    $expYear = $_POST['expYear'];

    if (empty($cardName) || empty($cardNumber) || empty($expMonth) || empty($expYear)) {
        $message = "Please fill out all payment details.";
        $statusClass = "error";
    } else {
        try {
            $pdo->beginTransaction();

            $customerId = $_SESSION['user_id'];
            
            $orderStmt = $pdo->prepare("INSERT INTO ORDERS (CustomerID, TotalAmount, Status) VALUES (?, ?, 'Processing')");
            $orderStmt->execute([$customerId, $totalAmount]);
            $orderId = $pdo->lastInsertId(); 

            $detailStmt = $pdo->prepare("INSERT INTO ORDER_DETAIL (OrderID, ProductID, Quantity, UnitPrice) VALUES (?, ?, ?, ?)");
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $unitPrice = $priceMap[$productId];
                $detailStmt->execute([$orderId, $productId, $quantity, $unitPrice]);
            }

            $payStmt = $pdo->prepare("INSERT INTO PAYMENT (OrderID, Amount, PaymentType) VALUES (?, ?, 'CARD')");
            $payStmt->execute([$orderId, $totalAmount]);
            $paymentId = $pdo->lastInsertId();

            $lastFour = substr($cardNumber, -4); 
            $cardStmt = $pdo->prepare("INSERT INTO CARD_PAYMENT (PaymentID, CardHolderName, LastFourDigits, ExpirationMonth, ExpirationYear) VALUES (?, ?, ?, ?, ?)");
            $cardStmt->execute([$paymentId, $cardName, $lastFour, $expMonth, $expYear]);

            $pdo->commit();

            unset($_SESSION['cart']);
            
            $orderSuccess = true;
            $message = "Payment successful! Your order (ID: #$orderId) has been confirmed.";
            $statusClass = "success";

        } catch (Exception $e) {
            $pdo->rollBack(); 
            $message = "Checkout failed: " . $e->getMessage();
            $statusClass = "error";
        }
    }
}
?>

<div class="auth-card" style="max-width: 500px;">
    <h2>Checkout</h2>

    <?php if(!empty($message)): ?>
        <div class="message <?php echo $statusClass; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($orderSuccess): ?>
        <a href="index.php"><button style="margin-top: 20px;">Return to Shop</button></a>
    <?php else: ?>
        <p style="text-align: center; font-size: 18px; margin-bottom: 24px;">Total to pay: <strong>$<?php echo number_format($totalAmount, 2); ?></strong></p>

        <form action="checkout.php" method="POST">
            <div class="form-group">
                <label>Name on Card</label>
                <input type="text" name="cardName" placeholder="Your Full Name" required>
            </div>
            <div class="form-group">
                <label>Card Number</label>
                <input type="text" name="cardNumber" placeholder="1234 5678 9101 1121" maxlength="16" required>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <div class="form-group" style="flex: 1;">
                    <label>Exp Month</label>
                    <input type="number" name="expMonth" placeholder="12" min="1" max="12" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Exp Year</label>
                    <input type="number" name="expYear" placeholder="2028" min="2024" required>
                </div>
            </div>
            
            <button type="submit" style="background-color: #10b981; margin-top: 15px;">Complete Purchase</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>