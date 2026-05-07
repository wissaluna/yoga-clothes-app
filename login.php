<?php
session_start();
require_once 'includes/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';
$statusClass = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $message = "Please enter both email and password.";
        $statusClass = "error";
    } else {
        
        $stmt = $pdo->prepare("SELECT CustomerID, FirstName, PasswordHash FROM CUSTOMER WHERE Email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            
            $hashedInputPassword = hash('sha256', $password);
            
            if ($hashedInputPassword === $user['PasswordHash']) {
                
                $_SESSION['user_id'] = $user['CustomerID'];
                $_SESSION['user_name'] = $user['FirstName'];
                
                
                header("Location: index.php");
                exit();
            } else {
                $message = "Incorrect password.";
                $statusClass = "error";
            }
        } else {
            $message = "No account found with that email.";
            $statusClass = "error";
        }
    }
}

include 'includes/header.php';
?>

<div class="auth-card">
    <h2>Welcome Back</h2>

    <?php if(!empty($message)): ?>
        <div class="message <?php echo $statusClass; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST" onsubmit="return validateLoginForm()">
        <div class="form-group">
            <label>Email</label>
            <input type="email" id="login-email" name="email">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" id="login-password" name="password">
        </div>
        
        <button type="submit">Sign In</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>