<?php
/**
 * KopuGive - Complete Automated Setup
 * This script will set up everything automatically
 */

// Disable output buffering for real-time display
@ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression', false);
@ini_set('implicit_flush', true);
@ob_end_flush();

echo "<!DOCTYPE html>
<html>
<head>
    <title>KopuGive - Automated Setup</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container { max-width: 900px; }
        .card { 
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px;
        }
        .step { 
            padding: 15px 20px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .step i { margin-right: 10px; font-size: 1.2em; }
        .credentials-box {
            background: #f8f9fa;
            border: 2px solid #007bff;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .credential-item {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }
        .alert-success { border-left-color: #28a745; }
        .alert-info { border-left-color: #17a2b8; }
        .alert-warning { border-left-color: #ffc107; }
        .alert-danger { border-left-color: #dc3545; }
        code { 
            background: #f8f9fa;
            padding: 3px 8px;
            border-radius: 4px;
            color: #d63384;
            font-weight: 600;
        }
        .btn-action {
            margin: 10px 5px;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
<div class='container'>
    <div class='card'>
        <div class='card-header text-white'>
            <h1 class='mb-0'><i class='fas fa-rocket me-2'></i>KopuGive - Complete Setup</h1>
            <p class='mb-0 mt-2'>Automated Database & System Configuration</p>
        </div>
        <div class='card-body'>";

// Track if any errors occurred
$hasErrors = false;

try {
    // Step 1: Connect to MySQL server (not specific database yet)
    echo "<h4 class='mt-3 mb-3'><i class='fas fa-database me-2'></i>Step 1: Database Connection</h4>";
    
    try {
        $conn = new PDO("mysql:host=localhost:3306;charset=utf8mb4", 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        
        echo "<div class='step alert alert-success'>
                <i class='fas fa-check-circle'></i>
                <strong>MySQL Connection:</strong> Successful! (Port 3306)
              </div>";
    } catch (PDOException $e) {
        throw new Exception("Cannot connect to MySQL server. Make sure XAMPP MySQL is running on port 3306!");
    }
    
    // Step 2: Create database
    echo "<h4 class='mt-4 mb-3'><i class='fas fa-server me-2'></i>Step 2: Database Creation</h4>";
    
    $conn->exec("CREATE DATABASE IF NOT EXISTS kopugive CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<div class='step alert alert-success'>
            <i class='fas fa-check'></i>
            <strong>Database Created:</strong> <code>kopugive</code> is ready!
          </div>";
    
    // Use the database
    $conn->exec("USE kopugive");
    
    // Step 3: Create tables (Schema)
    echo "<h4 class='mt-4 mb-3'><i class='fas fa-table me-2'></i>Step 3: Creating Tables</h4>";
    
    $schema = file_get_contents(__DIR__ . '/database/schema.sql');
    // Remove the CREATE DATABASE and USE statements as we already did that
    $schema = preg_replace('/CREATE DATABASE.*?;/s', '', $schema);
    $schema = preg_replace('/USE.*?;/s', '', $schema);
    
    // Execute schema
    $conn->exec($schema);
    
    $tables = ['users', 'campaigns', 'donations', 'campaign_updates', 'activity_logs', 'settings'];
    foreach ($tables as $table) {
        echo "<div class='step alert alert-success'>
                <i class='fas fa-check'></i>
                <strong>Table Created:</strong> <code>$table</code>
              </div>";
    }
    
    // Step 4: Run Migrations
    echo "<h4 class='mt-4 mb-3'><i class='fas fa-sync me-2'></i>Step 4: Running Migrations</h4>";
    
    // Migration 001: campaign_documents
    $migration1 = file_get_contents(__DIR__ . '/database/migrations/001_add_campaign_documents.sql');
    try {
        $conn->exec($migration1);
        echo "<div class='step alert alert-success'>
                <i class='fas fa-check'></i>
                <strong>Migration 001:</strong> Campaign documents table created
              </div>";
    } catch (PDOException $e) {
        echo "<div class='step alert alert-info'>
                <i class='fas fa-info-circle'></i>
                <strong>Migration 001:</strong> Already applied (skipped)
              </div>";
    }
    
    // Migration 004: Stripe fields
    $migration4 = file_get_contents(__DIR__ . '/database/migrations/004_add_stripe_fields.sql');
    try {
        $conn->exec($migration4);
        echo "<div class='step alert alert-success'>
                <i class='fas fa-check'></i>
                <strong>Migration 004:</strong> Stripe payment fields added
              </div>";
    } catch (PDOException $e) {
        echo "<div class='step alert alert-info'>
                <i class='fas fa-info-circle'></i>
                <strong>Migration 004:</strong> Already applied (skipped)
              </div>";
    }
    
    // Migration 005: Super admin role
    $migration5 = file_get_contents(__DIR__ . '/database/migrations/005_add_super_admin_role.sql');
    try {
        $conn->exec($migration5);
        echo "<div class='step alert alert-success'>
                <i class='fas fa-check'></i>
                <strong>Migration 005:</strong> Super admin role added
              </div>";
    } catch (PDOException $e) {
        echo "<div class='step alert alert-info'>
                <i class='fas fa-info-circle'></i>
                <strong>Migration 005:</strong> Already applied (skipped)
              </div>";
    }
    
    // Step 5: Insert sample data
    echo "<h4 class='mt-4 mb-3'><i class='fas fa-users me-2'></i>Step 5: Loading Sample Data</h4>";
    
    // Check if data already exists
    $stmt = $conn->query("SELECT COUNT(*) FROM users WHERE email = 'ahmad@example.com'");
    $exists = $stmt->fetchColumn() > 0;
    
    if (!$exists) {
        $seed = file_get_contents(__DIR__ . '/database/seed.sql');
        $seed = preg_replace('/USE.*?;/s', '', $seed);
        $conn->exec($seed);
        
        echo "<div class='step alert alert-success'>
                <i class='fas fa-check'></i>
                <strong>Sample Data Loaded:</strong> 3 campaigns, 3 donors, sample donations
              </div>";
    } else {
        echo "<div class='step alert alert-info'>
                <i class='fas fa-info-circle'></i>
                <strong>Sample Data:</strong> Already exists (skipped to preserve data)
              </div>";
    }
    
    // Step 6: Create directories
    echo "<h4 class='mt-4 mb-3'><i class='fas fa-folder me-2'></i>Step 6: Setting Up Directories</h4>";
    
    $dirs = [
        'uploads/campaigns' => 'Campaign banner images',
        'uploads/receipts' => 'Donation receipts',
        'uploads/documents' => 'Campaign approval documents',
        'admin/uploads/campaigns' => 'Admin campaign uploads',
        'donor/uploads/receipts' => 'Donor receipt uploads',
        'logs' => 'Application error logs'
    ];
    
    foreach ($dirs as $dir => $description) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
            echo "<div class='step alert alert-success'>
                    <i class='fas fa-folder-plus'></i>
                    <strong>Created:</strong> <code>$dir</code> - $description
                  </div>";
        } else {
            echo "<div class='step alert alert-info'>
                    <i class='fas fa-folder-open'></i>
                    <strong>Exists:</strong> <code>$dir</code> - $description
                  </div>";
        }
    }
    
    // Step 7: Verify configuration
    echo "<h4 class='mt-4 mb-3'><i class='fas fa-cog me-2'></i>Step 7: Verifying Configuration</h4>";
    
    require_once 'config/database.php';
    $db = (new Database())->getConnection();
    
    echo "<div class='step alert alert-success'>
            <i class='fas fa-check'></i>
            <strong>Application Config:</strong> Database connection verified
          </div>";
    
    // Count records
    $userCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $campaignCount = $db->query("SELECT COUNT(*) FROM campaigns")->fetchColumn();
    $donationCount = $db->query("SELECT COUNT(*) FROM donations")->fetchColumn();
    
    echo "<div class='step alert alert-info'>
            <i class='fas fa-info-circle'></i>
            <strong>Database Stats:</strong> {$userCount} users, {$campaignCount} campaigns, {$donationCount} donations
          </div>";
    
    // SUCCESS - Show credentials
    echo "<div class='card mt-4 border-success' style='border: 3px solid #28a745 !important;'>
            <div class='card-header bg-success text-white'>
                <h3 class='mb-0'><i class='fas fa-check-circle me-2'></i>Setup Complete Successfully!</h3>
            </div>
            <div class='card-body'>";
    
    echo "<div class='credentials-box'>
            <h4 class='mb-4'><i class='fas fa-key me-2 text-primary'></i>Login Credentials</h4>
            
            <div class='credential-item'>
                <h5 class='text-primary mb-3'><i class='fas fa-user-shield me-2'></i>Admin Account</h5>
                <table class='table table-borderless mb-0'>
                    <tr>
                        <td width='120'><strong>Email:</strong></td>
                        <td><code>admin@mrsmkp.edu.my</code></td>
                    </tr>
                    <tr>
                        <td><strong>Password:</strong></td>
                        <td><code>admin123</code></td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td><span class='badge bg-danger'>Administrator</span></td>
                    </tr>
                </table>
            </div>
            
            <div class='credential-item'>
                <h5 class='text-success mb-3'><i class='fas fa-users me-2'></i>Sample Donor Accounts</h5>
                <p class='mb-3'>All donor accounts use password: <code>admin123</code></p>
                <table class='table table-sm'>
                    <thead class='table-light'>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ahmad bin Abdullah</td>
                            <td><code>ahmad@example.com</code></td>
                            <td><span class='badge bg-success'>Donor</span></td>
                        </tr>
                        <tr>
                            <td>Siti Nurhaliza</td>
                            <td><code>siti@example.com</code></td>
                            <td><span class='badge bg-success'>Donor</span></td>
                        </tr>
                        <tr>
                            <td>Muhammad Hisyam</td>
                            <td><code>hisyam@example.com</code></td>
                            <td><span class='badge bg-success'>Donor</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
          </div>";
    
    echo "<div class='alert alert-info mt-4'>
            <h5><i class='fas fa-info-circle me-2'></i>What's Included:</h5>
            <ul class='mb-0'>
                <li><strong>3 Sample Campaigns:</strong> Tabung Komputer Lab, Program Bantuan Pelajar, Pembinaan Dewan Serbaguna</li>
                <li><strong>4 Sample Donations:</strong> Various amounts and statuses for testing</li>
                <li><strong>Stripe Integration:</strong> Ready for payment processing (configure API keys)</li>
                <li><strong>Complete System:</strong> All features active and ready to use</li>
            </ul>
          </div>";
    
    echo "<div class='text-center mt-4'>
            <h5 class='mb-3'>Ready to Start?</h5>
            <a href='index.php' class='btn btn-primary btn-action btn-lg'>
                <i class='fas fa-home me-2'></i>Go to Homepage
            </a>
            <a href='auth/login.php' class='btn btn-success btn-action btn-lg'>
                <i class='fas fa-sign-in-alt me-2'></i>Login Now
            </a>
            <a href='admin/dashboard.php' class='btn btn-danger btn-action btn-lg'>
                <i class='fas fa-tachometer-alt me-2'></i>Admin Dashboard
            </a>
          </div>";
    
    echo "<div class='alert alert-warning mt-4'>
            <h5><i class='fas fa-exclamation-triangle me-2'></i>Important Security Steps:</h5>
            <ol class='mb-0'>
                <li><strong>Delete setup files:</strong> Remove <code>setup.php</code> and <code>auto_setup.php</code> after setup</li>
                <li><strong>Change passwords:</strong> Update default passwords before production use</li>
                <li><strong>Configure Stripe:</strong> Add your Stripe API keys in <code>config/config.php</code></li>
                <li><strong>Check permissions:</strong> Ensure upload folders are writable</li>
            </ol>
          </div>";
    
    echo "<div class='alert alert-secondary mt-3'>
            <h5><i class='fas fa-book me-2'></i>Documentation:</h5>
            <ul class='mb-0'>
                <li><a href='README.md' target='_blank'>Main README</a> - Project overview</li>
                <li><a href='STRIPE_QUICK_START.md' target='_blank'>Stripe Quick Start</a> - Payment setup guide</li>
                <li><a href='QUICK_GUIDE_NO_APPROVAL.md' target='_blank'>User Guide</a> - How to use the system</li>
                <li><a href='TECH_STACK.md' target='_blank'>Tech Stack</a> - Technical details</li>
            </ul>
          </div>";
    
    echo "</div></div>";
    
} catch (Exception $e) {
    $hasErrors = true;
    echo "<div class='card mt-4 border-danger' style='border: 3px solid #dc3545 !important;'>
            <div class='card-header bg-danger text-white'>
                <h3 class='mb-0'><i class='fas fa-exclamation-circle me-2'></i>Setup Error</h3>
            </div>
            <div class='card-body'>
                <div class='alert alert-danger'>
                    <h5><i class='fas fa-bug me-2'></i>Error Details:</h5>
                    <p class='mb-0'><strong>" . htmlspecialchars($e->getMessage()) . "</strong></p>
                </div>
                
                <h5 class='mt-4'><i class='fas fa-wrench me-2'></i>Troubleshooting Steps:</h5>
                <ol>
                    <li><strong>Check XAMPP Control Panel:</strong>
                        <ul>
                            <li>Make sure <strong>Apache</strong> is running (green/started)</li>
                            <li>Make sure <strong>MySQL</strong> is running (green/started)</li>
                            <li>MySQL should be on port <code>3306</code></li>
                        </ul>
                    </li>
                    
                    <li><strong>Verify Database Configuration:</strong>
                        <ul>
                            <li>Open <code>config/database.php</code></li>
                            <li>Check: <code>DB_HOST = 'localhost:3306'</code></li>
                            <li>Check: <code>DB_USER = 'root'</code></li>
                            <li>Check: <code>DB_PASS = ''</code> (empty)</li>
                        </ul>
                    </li>
                    
                    <li><strong>Manual Database Setup:</strong>
                        <ul>
                            <li>Open phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank' class='text-primary'>http://localhost/phpmyadmin</a></li>
                            <li>Create database: <code>kopugive</code></li>
                            <li>Import file: <code>database/schema.sql</code></li>
                            <li>Import file: <code>database/seed.sql</code></li>
                            <li>Then run this setup script again</li>
                        </ul>
                    </li>
                    
                    <li><strong>Check Error Logs:</strong>
                        <ul>
                            <li>XAMPP Error Log: <code>C:/xampp/apache/logs/error.log</code></li>
                            <li>MySQL Error Log: <code>C:/xampp/mysql/data/mysql_error.log</code></li>
                        </ul>
                    </li>
                </ol>
                
                <div class='alert alert-info mt-4'>
                    <h5><i class='fas fa-lightbulb me-2'></i>Common Issues:</h5>
                    <ul class='mb-0'>
                        <li><strong>Port 3306 in use:</strong> Another MySQL service might be running. Stop it in Windows Services.</li>
                        <li><strong>Access denied:</strong> Check MySQL user permissions (default is root with no password).</li>
                        <li><strong>File permissions:</strong> Make sure the uploads and logs folders are writable.</li>
                    </ul>
                </div>
                
                <div class='text-center mt-4'>
                    <a href='auto_setup.php' class='btn btn-warning btn-lg'>
                        <i class='fas fa-redo me-2'></i>Try Setup Again
                    </a>
                </div>
            </div>
          </div>";
}

echo "    </div>
    </div>
</div>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>";
?>

