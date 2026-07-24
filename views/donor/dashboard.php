<?php 
$pageTitle = 'Donor Dashboard';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Donor Dashboard</h2>
    <div>
        <a href="<?php echo BASE_URL; ?>/index.php?route=donor-add-donation" class="btn btn-success">
            <i class="bi bi-plus"></i> Add Donation
        </a>
    </div>
</div>

<?php if (isset($_SESSION['donation_success'])): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_SESSION['donation_success']); ?>
    </div>
    <?php unset($_SESSION['donation_success']); ?>
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
                <h5 class="card-title">Total Donations</h5>
                <h2 class="card-text"><?php echo count($donations); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Available</h5>
                <h2 class="card-text"><?php echo count(array_filter($donations, function($d) { return $d['status'] === 'available'; })); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Requested</h5>
                <h2 class="card-text"><?php echo count(array_filter($donations, function($d) { return $d['status'] === 'requested'; })); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Completed</h5>
                <h2 class="card-text"><?php echo count(array_filter($donations, function($d) { return $d['status'] === 'completed'; })); ?></h2>
            </div>
        </div>
    </div>
</div>

<h3 class="mb-3">My Donations</h3>

<?php if (empty($donations)): ?>
    <div class="alert alert-info">
        You haven't made any donations yet. <a href="<?php echo BASE_URL; ?>/index.php?route=donor-add-donation">Add your first donation</a>.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Food Name</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Type</th>
                    <th>Expiry</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donations as $donation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donation['food_name']); ?></td>
                        <td>
                            <?php if (!empty($donation['image_path'])): ?>
                                <img src="<?php echo UPLOAD_URL; ?>/<?php echo htmlspecialchars($donation['image_path']); ?>"
                                     alt="<?php echo htmlspecialchars($donation['food_name']); ?>"
                                     class="img-thumbnail"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-muted">No image</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($donation['quantity_value'] . ' ' . $donation['quantity_unit']); ?></td>
                        <td><?php echo htmlspecialchars($donation['food_type']); ?></td>
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
                        <td>
                            <?php if (!empty($donation['image_path'])): ?>
                                <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($donation['image_path']); ?>"
                                     alt="<?php echo htmlspecialchars($donation['food_name']); ?>"
                                     class="img-thumbnail"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-muted">No image</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/index.php?route=donor-view-requests&id=<?php echo $donation['donation_id']; ?>" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> View Requests
                            </a>
                            <?php if ($donation['status'] === 'available'): ?>
                                <a href="<?php echo BASE_URL; ?>/index.php?route=donor-edit-donation&id=<?php echo $donation['donation_id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="<?php echo BASE_URL; ?>/index.php?route=donor-delete-donation&id=<?php echo $donation['donation_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this donation?');">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>