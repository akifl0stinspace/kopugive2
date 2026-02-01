<?php
session_start();
require_once '../config/config.php';
require_once '../includes/functions.php';

// Only super admins can access this page
if (!isLoggedIn() || !isSuperAdmin()) {
    setFlashMessage('error', 'Access denied. Only Super Admins can manage admins.');
    redirect('dashboard.php');
}

$db = (new Database())->getConnection();
$error = '';
$success = '';

// Handle create admin form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_admin') {
    $fullName = sanitize($_POST['full_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($fullName) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields';
    } elseif (!isValidEmail($email)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < PASSWORD_MIN_LENGTH) {
        $error = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } else {
        try {
            // Check if email already exists
            $stmt = $db->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email already exists';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (full_name, email, phone, password_hash, role, is_active) VALUES (?, ?, ?, ?, 'admin', 1)");
                $stmt->execute([$fullName, $email, $phone, $passwordHash]);

                $newId = $db->lastInsertId();
                logActivity($db, $_SESSION['user_id'], 'Created admin user', 'user', $newId);

                $success = 'Admin account created successfully';
            }
        } catch (Exception $e) {
            error_log('Create admin error: ' . $e->getMessage());
            $error = 'An error occurred while creating admin.';
        }
    }
}

// Fetch all admins and super admins
$stmt = $db->query("
    SELECT user_id, full_name, email, phone, role, is_active, created_at
    FROM users
    WHERE role IN ('admin','super_admin')
    ORDER BY role DESC, created_at DESC
");
$admins = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins - KopuGive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include 'includes/admin_styles.php'; ?>
</head>
<body>
    <?php include 'includes/admin_sidebar.php'; ?>

    <main class="col-md-10 ms-sm-auto px-md-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user-shield me-2"></i>Manage Admins</h2>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Add New Admin</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="create_admin">
                            <div class="mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="full_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control" placeholder="0123456789">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password *</label>
                                <input type="password" name="password" class="form-control" required>
                                <small class="text-muted">Min <?= PASSWORD_MIN_LENGTH ?> characters</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password *</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus me-2"></i>Create Admin
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-users-cog me-2"></i>Existing Admins</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($admins)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No admin accounts yet.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($admins as $index => $admin): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= htmlspecialchars($admin['full_name']) ?></td>
                                                <td><?= htmlspecialchars($admin['email']) ?></td>
                                                <td>
                                                    <?php if ($admin['role'] === 'super_admin'): ?>
                                                        <span class="badge bg-danger">Super Admin</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-primary">Admin</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($admin['is_active']): ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



