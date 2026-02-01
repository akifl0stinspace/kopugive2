<?php
session_start();
require_once '../config/config.php';
require_once '../includes/functions.php';

// Only super admins can access donor records
if (!isLoggedIn() || !isSuperAdmin()) {
    setFlashMessage('error', 'Access denied. Only Super Admins can view donor records.');
    redirect('dashboard.php');
}

$db = (new Database())->getConnection();

// Fetch donors
$stmt = $db->query("SELECT user_id, full_name, email, phone, is_active, created_at FROM users WHERE role = 'donor' ORDER BY created_at DESC");
$donors = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donors - KopuGive Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include 'includes/admin_styles.php'; ?>
</head>
<body>
    <?php include 'includes/admin_sidebar.php'; ?>

    <!-- Main content -->
    <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Donors</h2>
                    <span class="text-muted"><?= count($donors) ?> total</span>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($donors)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">No donors found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($donors as $index => $donor): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= htmlspecialchars($donor['full_name']) ?></td>
                                                <td><?= htmlspecialchars($donor['email']) ?></td>
                                                <td><?= htmlspecialchars($donor['phone'] ?? '-') ?></td>
                                                <td>
                                                    <?php if ($donor['is_active']): ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d M Y, h:i A', strtotime($donor['created_at'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


