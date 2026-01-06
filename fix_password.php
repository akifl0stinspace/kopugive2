<?php
require_once 'config/database.php';

try {
    $db = (new Database())->getConnection();
    
    // Create new password hash for 'admin123'
    $newPassword = 'admin123';
    $hash = password_hash($newPassword, PASSWORD_BCRYPT);
    
    // Update admin password
    $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE email = 'admin@mrsmkp.edu.my'");
    $stmt->execute([$hash]);
    
    // Update all donor passwords to admin123
    $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE role = 'donor'");
    $stmt->execute([$hash]);
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Password Reset</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body style='background: #f8f9fa; padding: 50px;'>
        <div class='container' style='max-width: 600px;'>
            <div class='card shadow'>
                <div class='card-header bg-success text-white'>
                    <h3 class='mb-0'>✅ Passwords Reset Successfully!</h3>
                </div>
                <div class='card-body'>
                    <h5>All accounts now use password: <code class='text-danger fs-4'>admin123</code></h5>
                    
                    <hr>
                    
                    <h5>Login Credentials:</h5>
                    
                    <div class='alert alert-danger'>
                        <strong>Admin:</strong><br>
                        Email: <code>admin@mrsmkp.edu.my</code><br>
                        Password: <code>admin123</code>
                    </div>
                    
                    <div class='alert alert-success'>
                        <strong>Donors (all use same password):</strong><br>
                        • <code>ahmad@example.com</code> / <code>admin123</code><br>
                        • <code>siti@example.com</code> / <code>admin123</code><br>
                        • <code>hisyam@example.com</code> / <code>admin123</code>
                    </div>
                    
                    <div class='text-center mt-4'>
                        <a href='auth/login.php' class='btn btn-primary btn-lg'>Go to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>";
    
} catch (Exception $e) {
    echo "<h3>Error: " . $e->getMessage() . "</h3>";
    echo "<p>Make sure you ran auto_setup.php first!</p>";
}
?>

