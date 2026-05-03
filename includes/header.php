<?php

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shavasana Cloaks</title>
   
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <div class="logo">
        <h2>Shavasana Yoga Boutique</h2>
    </div>
    <nav>
        <a href="index.php">Shop</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            
            <span style="margin-left: 20px; color: #1abc9c;">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>

<main class="container">