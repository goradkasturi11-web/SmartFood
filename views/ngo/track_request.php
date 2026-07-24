<?php 
$pageTitle = 'Track Request';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Track Food Request</h2>
    <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-dashboard" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Request details</h5>
                <p><strong>Food:</strong> <?php echo htmlspecialchars($request['food_name']); ?></p>
                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($request['quantity_value'] . ' ' . $request['quantity_unit']); ?></p>
                <p><strong>Donor:</strong> <?php echo htmlspecialchars($request['donor_name']); ?></p>
                <p><strong>Pickup location:</strong> <?php echo htmlspecialchars($request['pickup_location']); ?></p>
                <p><strong>Request date:</strong> <?php echo date('M d, Y H:i', strtotime($request['request_date'])); ?></p>
                <p><strong>Current status:</strong>
                    <span class="badge bg-<?php echo $request['status'] === 'pending' ? 'warning' : ($request['status'] === 'approved' ? 'success' : ($request['status'] === 'rejected' ? 'danger' : 'secondary')); ?>">
                        <?php echo ucfirst($request['status']); ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Progress</h5>
                <?php
                $progress = 0;
                $progressLabel = 'Request submitted';
                $progressClass = 'bg-warning';
                
                if ($request['status'] === 'pending') {
                    $progress = 25;
                    $progressLabel = 'Request submitted';
                    $progressClass = 'bg-warning';
                } elseif ($request['status'] === 'approved') {
                    $progress = $request['donation_status'] === 'completed' ? 100 : 75;
                    $progressLabel = $request['donation_status'] === 'completed' ? 'Food collected' : 'Request approved';
                    $progressClass = $request['donation_status'] === 'completed' ? 'bg-success' : 'bg-info';
                } elseif ($request['status'] === 'rejected' || $request['status'] === 'cancelled') {
                    $progress = 100;
                    $progressLabel = ucfirst($request['status']);
                    $progressClass = 'bg-danger';
                }
                ?>
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span><?php echo $progressLabel; ?></span>
                        <span class="fw-bold"><?php echo $progress; ?>%</span>
                    </div>
                    <div class="progress" style="height: 24px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated <?php echo $progressClass; ?>" role="progressbar" style="width: <?php echo $progress; ?>%;" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item <?php echo $request['status'] === 'pending' ? 'bg-light' : ''; ?>">
                        <i class="bi bi-circle-fill text-warning me-2"></i> Request submitted
                    </li>
                    <li class="list-group-item <?php echo $request['status'] === 'approved' || $request['donation_status'] === 'completed' ? 'bg-light' : ''; ?>">
                        <i class="bi bi-circle-fill text-info me-2"></i> Request approved by donor
                    </li>
                    <li class="list-group-item <?php echo $request['donation_status'] === 'completed' ? 'bg-light' : ''; ?>">
                        <i class="bi bi-circle-fill text-success me-2"></i> Food collected
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
