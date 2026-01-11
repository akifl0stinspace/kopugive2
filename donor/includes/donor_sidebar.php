<div class="container-fluid">
    <div class="row">
        <!-- Donor Sidebar -->
        <nav class="col-md-2 d-md-block sidebar text-white p-3 d-flex flex-column" style="height: 100vh; overflow-y: auto;">
            <div class="text-center mb-4">
                <h4><i class="fas fa-hand-holding-heart"></i> KopuGive</h4>
                <small>Donor Panel</small>
            </div>
            
            <hr class="text-white">
            
            <ul class="nav flex-column flex-grow-1">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                        <i class="fas fa-chart-line me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'campaigns.php' || basename($_SERVER['PHP_SELF']) == 'campaign_view.php' ? 'active' : '' ?>" href="campaigns.php">
                        <i class="fas fa-bullhorn me-2"></i> Browse Campaigns
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'my_donations.php' ? 'active' : '' ?>" href="my_donations.php">
                        <i class="fas fa-history me-2"></i> My Donations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>" href="profile.php">
                        <i class="fas fa-user me-2"></i> My Profile
                    </a>
                </li>
            </ul>
            
            <hr class="text-white">
            
            <div class="mt-2">
                <div class="mb-2">
                    <i class="fas fa-user-circle me-2"></i>
                    <small><?= htmlspecialchars($_SESSION['full_name']) ?></small>
                </div>
                <a href="../auth/logout.php" class="btn btn-outline-light btn-sm w-100">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </nav>
    </div>
</div>


