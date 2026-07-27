<?php 
$pageTitle = 'Donation History';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Donation History</h2>
    <div>
        <form action="<?php echo BASE_URL; ?>/index.php" method="GET">

    <input type="hidden"
           name="route"
           value="admin-donation-history">

    <input type="date"
           name="start_date"
           value="<?php echo $_GET['start_date'] ?? ''; ?>">

    <input type="date"
           name="end_date"
           value="<?php echo $_GET['end_date'] ?? ''; ?>">

    <button type="submit" class="btn btn-primary">
        Filter
    </button>

</form>
        <a href="<?php echo BASE_URL; ?>/index.php?route=admin-dashboard" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<?php if (empty($donations)): ?>
    <div class="alert alert-info">
        No donations found.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Food Name</th>
                    <th>Quantity</th>
                    <th>Type</th>
                    <th>Donor</th>
                    <th>Pickup Location</th>
                    <th>Expiry</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donations as $donation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donation['donation_id']); ?></td>
                        <td><?php echo htmlspecialchars($donation['food_name']); ?></td>
                        <td><?php echo htmlspecialchars($donation['quantity_value'] . ' ' . $donation['quantity_unit']); ?></td>
                        <td><?php echo htmlspecialchars($donation['food_type']); ?></td>
                        <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                        <td><?php echo htmlspecialchars(substr($donation['pickup_location'], 0, 30)); ?>...</td>
                        <td><?php echo date('M d, Y H:i', strtotime($donation['expiry_time'])); ?></td>
                        <td>
                            <?php
                            $statusClass = 'secondary';
                            switch ($donation['status']) {
                                case 'available': $statusClass = 'success'; break;
                                case 'requested': $statusClass = 'warning'; break;
                                case 'completed': $statusClass = 'info'; break;
                                case 'expired': $statusClass = 'danger'; break;
                            }
                            ?>
                            <span class="badge bg-<?php echo $statusClass; ?>"><?php echo ucfirst($donation['status']); ?></span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($donation['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
