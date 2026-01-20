<?php
/**
 * Generate Receipts for Existing Donations
 * Run this once to generate receipts for all successful donations
 */

require_once 'config/config.php';
require_once 'includes/receipt_functions.php';

// Get database connection
$db = (new Database())->getConnection();

// Get all successful donations without receipts
$stmt = $db->query("
    SELECT d.*, c.campaign_name, c.campaign_description, u.full_name, u.email
    FROM donations d
    LEFT JOIN campaigns c ON d.campaign_id = c.campaign_id
    LEFT JOIN users u ON d.donor_id = u.user_id
    WHERE d.status = 'verified' 
    AND (d.receipt_path IS NULL OR d.receipt_path = '')
    ORDER BY d.created_at DESC
");

$donations = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Generate Missing Receipts</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #850E35; }
        .success { color: #28a745; padding: 10px; background: #d4edda; border-radius: 4px; margin: 10px 0; }
        .error { color: #dc3545; padding: 10px; background: #f8d7da; border-radius: 4px; margin: 10px 0; }
        .info { color: #0c5460; padding: 10px; background: #d1ecf1; border-radius: 4px; margin: 10px 0; }
        .donation { border-bottom: 1px solid #eee; padding: 10px 0; }
        .stats { display: flex; gap: 20px; margin: 20px 0; }
        .stat-box { flex: 1; padding: 15px; background: #FFC4C4; border-radius: 8px; text-align: center; }
        .stat-box h3 { margin: 0; color: #850E35; font-size: 32px; }
        .stat-box p { margin: 5px 0 0 0; color: #666; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🧾 Generate Missing Receipts</h1>
";

if (empty($donations)) {
    echo "<div class='info'>✅ All successful donations already have receipts!</div>";
    echo "<p><a href='admin/dashboard.php'>← Back to Dashboard</a></p>";
    echo "</div></body></html>";
    exit;
}

echo "<div class='info'>Found " . count($donations) . " successful donations without receipts</div>";

$generated = 0;
$failed = 0;

echo "<div style='margin: 20px 0;'>";

foreach ($donations as $donation) {
    echo "<div class='donation'>";
    echo "<strong>Donation #" . $donation['donation_id'] . "</strong> - ";
    echo htmlspecialchars($donation['campaign_name']) . " - ";
    echo "RM " . number_format($donation['amount'], 2) . " - ";
    echo date('d M Y', strtotime($donation['donation_date']));
    
    // Prepare campaign data
    $campaign = [
        'campaign_name' => $donation['campaign_name'],
        'campaign_description' => $donation['campaign_description']
    ];
    
    // Generate receipt
    $result = generateReceipt($donation, $campaign);
    
    if ($result['success']) {
        // Update database with receipt path
        $updateStmt = $db->prepare("
            UPDATE donations 
            SET receipt_path = ?
            WHERE donation_id = ?
        ");
        $updateStmt->execute([$result['path'], $donation['donation_id']]);
        
        echo " <span style='color: #28a745;'>✓ Receipt generated</span>";
        $generated++;
    } else {
        echo " <span style='color: #dc3545;'>✗ Failed: " . htmlspecialchars($result['message']) . "</span>";
        $failed++;
    }
    
    echo "</div>";
    flush();
}

echo "</div>";

echo "<div class='stats'>
    <div class='stat-box'>
        <h3>{$generated}</h3>
        <p>Generated</p>
    </div>
    <div class='stat-box'>
        <h3>{$failed}</h3>
        <p>Failed</p>
    </div>
</div>";

if ($generated > 0) {
    echo "<div class='success'>✅ Successfully generated {$generated} receipts!</div>";
}

if ($failed > 0) {
    echo "<div class='error'>❌ Failed to generate {$failed} receipts. Check error logs.</div>";
}

echo "<p><a href='donor/my_donations.php'>View My Donations →</a> | <a href='admin/dashboard.php'>← Back to Dashboard</a></p>";

echo "</div></body></html>";
?>

