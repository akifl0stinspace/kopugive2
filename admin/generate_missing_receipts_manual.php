<?php
/**
 * Generate Missing Receipts for Successful Donations
 * Run this once to generate receipts for donations that don't have them
 */

session_start();
require_once '../config/config.php';
require_once '../includes/functions.php';
require_once '../includes/receipt_functions.php';

// Only admins can access this
if (!isLoggedIn() || !isAdmin()) {
    die('Access denied. Admin only.');
}

$db = (new Database())->getConnection();

echo "<h2>Generating Missing Receipts...</h2>";
echo "<hr>";

// Find all successful donations without receipts
$stmt = $db->query("
    SELECT donation_id, donor_name, amount, campaign_id 
    FROM donations 
    WHERE status = 'verified' 
    AND (receipt_path IS NULL OR receipt_path = '')
    ORDER BY donation_date DESC
");

$donations = $stmt->fetchAll();
$totalCount = count($donations);
$successCount = 0;
$failCount = 0;

echo "<p>Found <strong>{$totalCount}</strong> successful donations without receipts.</p>";
echo "<hr>";

if ($totalCount > 0) {
    foreach ($donations as $donation) {
        echo "<p>Processing Donation ID #{$donation['donation_id']} - {$donation['donor_name']} - RM {$donation['amount']}... ";
        
        $result = processReceiptForDonation($donation['donation_id'], $db);
        
        if ($result['success']) {
            echo "<span style='color: green;'>✓ Success! Receipt: {$result['receipt_path']}</span></p>";
            $successCount++;
        } else {
            echo "<span style='color: red;'>✗ Failed: {$result['message']}</span></p>";
            $failCount++;
        }
        
        // Small delay to avoid overwhelming the system
        usleep(100000); // 0.1 second
    }
    
    echo "<hr>";
    echo "<h3>Summary:</h3>";
    echo "<p>✓ Successfully generated: <strong style='color: green;'>{$successCount}</strong></p>";
    echo "<p>✗ Failed: <strong style='color: red;'>{$failCount}</strong></p>";
    echo "<p>📊 Total processed: <strong>{$totalCount}</strong></p>";
    
    echo "<hr>";
    echo "<p><a href='donations.php' class='btn btn-primary'>View Donations</a></p>";
} else {
    echo "<p style='color: green;'>✓ All successful donations already have receipts!</p>";
}

echo "<hr>";
echo "<p><small>Done! You can close this page or <a href='javascript:history.back()'>go back</a>.</small></p>";
?>







