<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';

if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $removeId = $_GET['id'];
    if (isset($_SESSION['cart'][$removeId])) {
        unset($_SESSION['cart'][$removeId]);
        header("Location: cart.php");
        exit();
    }
}


include 'includes/header.php';

$cartItems = [];
$totalPrice = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $productIds = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    
    $stmt = $pdo->prepare("SELECT ProductID, ProductName, Price FROM PRODUCT WHERE ProductID IN ($placeholders)");
    $stmt->execute($productIds);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        $id = $product['ProductID'];
        $qty = $_SESSION['cart'][$id];
        $subtotal = $product['Price'] * $qty;
        
        $product['Quantity'] = $qty;
        $product['Subtotal'] = $subtotal;
        $cartItems[] = $product;
        
        $totalPrice += $subtotal;
    }
}
?>

<div class="cart-container">
    <h2>Your Shopping Cart</h2>

    <?php if (empty($cartItems)): ?>
        <p style="color: #64748b; padding: 20px 0;">Your cart is currently empty. <a href="index.php" style="color: #1abc9c;">Go shopping!</a></p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($item['ProductName']); ?></strong></td>
                        <td>$<?php echo number_format($item['Price'], 2); ?></td>
                        <td><?php echo $item['Quantity']; ?></td>
                        <td class="price-col">$<?php echo number_format($item['Subtotal'], 2); ?></td>
                        <td>
                            <a href="cart.php?action=remove&id=<?php echo $item['ProductID']; ?>">
                                <button class="btn-remove">Remove</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <h3>Total: $<?php echo number_format($totalPrice, 2); ?></h3>
            <a href="checkout.php">
                <button class="btn-checkout">Proceed to Checkout</button>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>