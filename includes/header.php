<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shavasana Boutique</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-container">
        
        <div class="logo">
            <a href="index.php" style="text-decoration: none;">
                <h2>Shavasana.</h2>
            </a>
        </div>
        
        <nav class="main-nav">
            <a href="index.php" class="nav-link">Shop</a>
            
            <a href="cart.php" class="nav-link cart-link">
                Cart <span id="cart-count" class="cart-badge"><?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?></span>
            </a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <span class="user-greeting">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="logout.php" class="nav-btn btn-ghost">Logout</a>
            <?php else: ?>
                <a href="login.php" class="nav-btn btn-ghost">Login</a>
                <a href="register.php" class="nav-btn btn-primary">Register</a>
            <?php endif; ?>
        </nav>
        
    </div>
</header>

<main class="container">