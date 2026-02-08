<?php
session_start();
require_once '../config/config.php';
require_once '../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(isAdmin() ? '../admin/dashboard.php' : '../donor/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $loginType = $_POST['login_type'] ?? 'donor';
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        try {
            $db = (new Database())->getConnection();
            $stmt = $db->prepare("SELECT user_id, full_name, email, password_hash, role, is_active FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Verify role matches login type
                $isAdminLogin = ($loginType === 'admin');
                $isAdminRole = in_array($user['role'], ['admin', 'super_admin'], true);
                
                if ($isAdminLogin && !$isAdminRole) {
                    $error = 'This account is not an admin account. Please select "Donor" to login.';
                } elseif (!$isAdminLogin && $isAdminRole) {
                    $error = 'This is an admin account. Please select "Admin" to login.';
                } elseif (!$user['is_active']) {
                    $error = 'Your account has been deactivated. Please contact administrator.';
                } else {
                    // Login successful
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['login_time'] = time();
                    
                    logActivity($db, $user['user_id'], 'User logged in', 'user', $user['user_id']);
                    
                    setFlashMessage('success', 'Welcome back, ' . $user['full_name'] . '!');
                    // Admin and super admin both go to admin dashboard
                    if (in_array($user['role'], ['admin', 'super_admin'], true)) {
                        redirect('../admin/dashboard.php');
                    } else {
                        redirect('../donor/dashboard.php');
                    }
                }
            } else {
                $error = 'Invalid email or password';
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $error = 'An error occurred. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KopuGive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include '../includes/theme_styles.php'; ?>
    <style>
        body {
            background: var(--light-gray);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border-radius: 8px;
            box-shadow: var(--shadow-md);
            border-top: 3px solid var(--maroon-primary);
        }
        .brand-section {
            background: var(--maroon-primary);
            color: white;
            border-radius: 8px 8px 0 0;
            padding: 3rem;
        }
        @media (min-width: 768px) {
            .brand-section {
                border-radius: 8px 0 0 8px;
                border-right: 2px solid var(--gold-primary);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card login-card">
                    <div class="row g-0">
                        <div class="col-12 col-md-5 brand-section d-flex flex-column justify-content-center" style="background: #850E35 !important; color: white !important;">
                            <h2 class="fw-bold mb-4"><i class="fas fa-hand-holding-heart me-2"></i>KopuGive</h2>
                            <p class="lead">MRSM Kota Putra Donation Management System</p>
                            <p class="small">Connecting hearts, building futures together.</p>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="card-body p-5">
                                <h4 class="mb-4">Welcome Back!</h4>
                                
                                <?php if ($error): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Login As</label>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="login_type" id="login_admin" value="admin" checked>
                                            <label class="btn btn-outline-primary" for="login_admin">
                                                <i class="fas fa-user-shield me-1"></i>Admin
                                            </label>
                                            
                                            <input type="radio" class="btn-check" name="login_type" id="login_donor" value="donor">
                                            <label class="btn btn-outline-primary" for="login_donor">
                                                <i class="fas fa-hand-holding-heart me-1"></i>Donor
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?= htmlspecialchars($email ?? '') ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                    </button>
                                    
                                    <div class="text-center">
                                        <small class="text-muted">Don't have an account? 
                                            <a href="register.php">Register here</a>
                                        </small>
                                    </div>
                                    
                                    <div class="text-center mt-2">
                                        <small><a href="../index.php">Back to Home</a></small>
                                    </div>
                                </form>
                                
                                <div class="mt-4 p-3 bg-light rounded">
                                    <small class="text-muted">
                                        <strong>Quick Login:</strong><br>
                                        <span id="demo-superadmin" class="d-block mb-1" style="cursor: pointer;">
                                            <i class="fas fa-crown text-danger"></i> <strong>Super Admin:</strong> admin@mrsmkp.edu.my / admin123
                                        </span>
                                        <span id="demo-admin" class="d-block mb-1" style="cursor: pointer;">
                                            <i class="fas fa-user-shield text-primary"></i> <strong>Admin:</strong> admin1@mrsmkp.edu.my / admin123
                                        </span>
                                        <span id="demo-donor" class="d-block" style="cursor: pointer;">
                                            <i class="fas fa-hand-holding-heart text-success"></i> <strong>Donor:</strong> ahmad@example.com / admin123
                                        </span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        function togglePassword(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = button.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Auto-fill credentials when clicking demo accounts
        document.getElementById('demo-superadmin').addEventListener('click', function() {
            document.getElementById('email').value = 'admin@mrsmkp.edu.my';
            document.getElementById('password').value = 'admin123';
            document.getElementById('login_admin').checked = true;
        });
        
        document.getElementById('demo-admin').addEventListener('click', function() {
            document.getElementById('email').value = 'testadmin@mrsmkp.edu.my';
            document.getElementById('password').value = 'admin123';
            document.getElementById('login_admin').checked = true;
        });
        
        document.getElementById('demo-donor').addEventListener('click', function() {
            document.getElementById('email').value = 'ahmad@example.com';
            document.getElementById('password').value = 'admin123';
            document.getElementById('login_donor').checked = true;
        });
    </script>
</body>
</html>

