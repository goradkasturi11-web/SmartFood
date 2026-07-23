<?php
/**
 * Check donations in database
 */
require_once __DIR__ . '/../config/config.php';

try {
    $db = Database::getInstance()->getConnection();

    echo "<h2>Database Donation Check</h2>";

    // Check total donations
    $stmt = $db->query("SELECT COUNT(*) as total FROM donations");
    $result = $stmt->fetch();
    echo "<p><strong>Total donations:</strong> " . $result['total'] . "</p>";

    // Check available donations
    $stmt = $db->query("SELECT COUNT(*) as available FROM donations WHERE status = 'available' AND expiry_time > NOW()");
    $result = $stmt->fetch();
    echo "<p><strong>Available donations (not expired):</strong> " . $result['available'] . "</p>";

    // Show all donations with details
    $stmt = $db->query("SELECT d.*, u.name as donor_name FROM donations d JOIN users u ON d.donor_id = u.user_id ORDER BY d.created_at DESC LIMIT 10");
    $donations = $stmt->fetchAll();

    if (empty($donations)) {
        echo "<p class='alert alert-warning'>No donations found in database. Please create a donation first as a donor.</p>";
    } else {
        echo "<h3>Recent Donations:</h3>";
        echo "<table class='table table-bordered'>";
        echo "<thead><tr><th>ID</th><th>Food Name</th><th>Status</th><th>Expiry</th><th>Donor</th></tr></thead>";
        echo "<tbody>";
        foreach ($donations as $donation) {
            echo "<tr>";
            echo "<td>" . $donation['donation_id'] . "</td>";
            echo "<td>" . htmlspecialchars($donation['food_name']) . "</td>";
            echo "<td>" . $donation['status'] . "</td>";
            echo "<td>" . $donation['expiry_time'] . "</td>";
            echo "<td>" . htmlspecialchars($donation['donor_name']) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }

    echo "<hr>";
    echo "<a href='index.php?route=home'>Back to Home</a> | ";
    echo "<a href='index.php?route=login'>Login</a>";

} catch (PDOException $e) {
    echo "<p class='alert alert-danger'>Database error: " . $e->getMessage() . "</p>";
}
?>
