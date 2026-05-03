<?php

require_once 'includes/db.php';

$message = '';
$statusClass = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['firstname']);
    $lastName = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // PHP Server-Side Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $message = "All fields are required.";
        $statusClass = "error";
    } else {
        $stmt = $pdo->prepare("SELECT CustomerID FROM CUSTOMER WHERE Email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $message = "Email is already registered!";
            $statusClass = "error";
        } else {
            // SHA-256 Hashing (Rubric Requirement)
            $hashedPassword = hash('sha256', $password);
            $insertStmt = $pdo->prepare("INSERT INTO CUSTOMER (FirstName, LastName, Email, PasswordHash) VALUES (?, ?, ?, ?)");
            
            if ($insertStmt->execute([$firstName, $lastName, $email, $hashedPassword])) {
                $message = "Registration successful! You can now log in.";
                $statusClass = 'success';
            } else {
                $message = "Error registering user.";
                $statusClass = "error";
            }
        }
    }
}


include 'includes/header.php';
?>


<div class="auth-card">
    <h2>Create an Account</h2>

    <?php if(!empty($message)): ?>
        <div class="message <?php echo $statusClass; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST" onsubmit="return validateRegisterForm()">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" id="firstname" name="firstname">
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" id="lastname" name="lastname">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" id="email" name="email">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" id="password" name="password">
        </div>
        
        <button type="submit">Sign Up</button>
    </form>
</div>

<?php 

include 'includes/footer.php'; 
?>