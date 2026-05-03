<?php

require_once 'includes/db.php';


$sql = "SELECT p.ProductID, p.ProductName, p.Description, p.Price, p.StockQuantity, c.CategoryName 
        FROM PRODUCT p
        LEFT JOIN CATEGORY c ON p.CategoryID = c.CategoryID
        ORDER BY p.ProductID DESC";


$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


include 'includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Our Shavasana Collection</h2>
    <?php if(isset($_SESSION['user_name'])): ?>
        <p style="color: #64748b;">Ready to relax, <?php echo htmlspecialchars($_SESSION['user_name']); ?>?</p>
    <?php endif; ?>
</div>

<div class="product-grid">
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                
                <div class="product-category"><?php echo htmlspecialchars($product['CategoryName'] ?? 'Uncategorized'); ?></div>
                
                <h3><?php echo htmlspecialchars($product['ProductName']); ?></h3>
                
                <div class="product-desc"><?php echo htmlspecialchars($product['Description']); ?></div>
                
                <div class="product-footer">
                    <div class="price">$<?php echo number_format($product['Price'], 2); ?></div>
                    <div class="stock-status">In Stock: <?php echo $product['StockQuantity']; ?></div>
                </div>
                
                
                <button class="add-to-cart-btn" data-id="<?php echo $product['ProductID']; ?>">
                    Add to Cart
                </button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products available at the moment.</p>
    <?php endif; ?>
</div>

<?php 

include 'includes/footer.php'; 
?>