<?php 
$pageTitle = 'NGO Dashboard';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>NGO Dashboard</h2>
    <div>
        <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-browse-food" class="btn btn-success">
            <i class="bi bi-search"></i> Browse Food
        </a>
        <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-notifications" class="btn btn-info position-relative">
            <i class="bi bi-bell"></i> Notifications
            <?php if ($unreadCount > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo $unreadCount; ?>
                </span>
            <?php endif; ?>
        </a>
    </div>
</div>

<?php if (isset($_SESSION['request_success'])): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_SESSION['request_success']); ?>
    </div>
    <?php unset($_SESSION['request_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Requests</h5>
                <h2 class="card-text"><?php echo count($requests); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Pending</h5>
                <h2 class="card-text"><?php echo count(array_filter($requests, function($r) { return $r['status'] === 'pending'; })); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Approved</h5>
                <h2 class="card-text"><?php echo count(array_filter($requests, function($r) { return $r['status'] === 'approved'; })); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Completed</h5>
                <h2 class="card-text"><?php echo count(array_filter($requests, function($r) { return $r['donation_status'] === 'completed'; })); ?></h2>
            </div>
        </div>
    </div>
</div>

<h3 class="mb-3">My Requests</h3>

<?php if (empty($requests)): ?>
    <div class="alert alert-info">
        You haven't made any requests yet. <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-browse-food">Browse available food</a>.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Food Name</th>
                    <th>Quantity</th>
                    <th>Donor</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['food_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['quantity_value'] . ' ' . $request['quantity_unit']); ?></td>
                        <td><?php echo htmlspecialchars($request['donor_name']); ?></td>
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
                            <?php if ($request['status'] === 'pending'): ?>
                                <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-cancel-request&id=<?php echo $request['request_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this request?');">
                                    <i class="bi bi-x"></i> Cancel
                                </a>
                            <?php elseif ($request['status'] === 'approved' && $request['donation_status'] !== 'completed'): ?>
                                <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-mark-collected&id=<?php echo $request['request_id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Mark this food as collected?');">
                                    <i class="bi bi-check"></i> Mark Collected
                                </a>
                            <?php elseif ($request['status'] === 'approved' && $request['donation_status'] === 'completed'): ?>
                                <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-feedback&id=<?php echo $request['request_id']; ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-star"></i> Feedback
                                </a>
                            <?php elseif ($request['status'] === 'rejected'): ?>
                                <span class="text-danger">Rejected</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
