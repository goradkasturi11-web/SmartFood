<?php 
$pageTitle = 'View Requests';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="mb-4">
    <a href="<?php echo BASE_URL; ?>/index.php?route=donor-dashboard" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header">
        <h4 class="mb-0">Donation: <?php echo htmlspecialchars($donation['food_name']); ?></h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($donation['quantity_value'] . ' ' . $donation['quantity_unit']); ?></p>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($donation['food_type']); ?></p>
                <p><strong>Pickup Location:</strong> <?php echo htmlspecialchars($donation['pickup_location']); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Preparation Date:</strong> <?php echo date('M d, Y H:i', strtotime($donation['preparation_date'])); ?></p>
                <p><strong>Expiry Time:</strong> <?php echo date('M d, Y H:i', strtotime($donation['expiry_time'])); ?></p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-<?php echo $donation['status'] === 'available' ? 'success' : ($donation['status'] === 'requested' ? 'warning' : 'secondary'); ?>">
                        <?php echo ucfirst($donation['status']); ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<h3 class="mb-3">Requests from NGOs</h3>

<?php if (empty($requests)): ?>
    <div class="alert alert-info">
        No requests received yet for this donation.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>NGO Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['ngo_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['ngo_phone']); ?></td>
                        <td><?php echo htmlspecialchars($request['ngo_address']); ?></td>
                        <td><?php echo date('M d, Y H:i', strtotime($request['request_date'])); ?></td>
                        <td>
                            <?php
                            $statusClass = 'secondary';
                            switch ($request['status']) {
                                case 'pending': $statusClass = 'warning'; break;
                                case 'approved': $statusClass = 'success'; break;
                                case 'rejected': $statusClass = 'danger'; break;
                                case 'cancelled': $statusClass = 'secondary'; break;
                            }
                            ?>
                            <span class="badge bg-<?php echo $statusClass; ?>"><?php echo ucfirst($request['status']); ?></span>
                        </td>
                        <td>
                            <?php if ($request['status'] === 'pending' && $donation['status'] === 'available'): ?>
                                <a href="<?php echo BASE_URL; ?>/index.php?route=donor-accept-request&id=<?php echo $request['request_id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Accept this request? This will reject all other pending requests.')">
                                    <i class="bi bi-check"></i> Accept
                                </a>
                                <a href="<?php echo BASE_URL; ?>/index.php?route=donor-reject-request&id=<?php echo $request['request_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject this request?');">
                                    <i class="bi bi-x"></i> Reject
                                </a>
                            <?php elseif ($request['status'] === 'approved'): ?>
                                <span class="text-success"><i class="bi bi-check-circle"></i> Accepted</span>
                            <?php elseif ($request['status'] === 'rejected'): ?>
                                <span class="text-danger"><i class="bi bi-x-circle"></i> Rejected</span>
                            <?php elseif ($request['status'] === 'cancelled'): ?>
                                <span class="text-secondary"><i class="bi bi-x-circle"></i> Cancelled by NGO</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
