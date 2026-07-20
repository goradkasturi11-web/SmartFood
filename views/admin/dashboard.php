<?php 
$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Admin Dashboard</h2>
</div>

<?php if (isset($_SESSION['admin_success'])): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_SESSION['admin_success']); ?>
    </div>
    <?php unset($_SESSION['admin_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['admin_error'])): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($_SESSION['admin_error']); ?>
    </div>
    <?php unset($_SESSION['admin_error']); ?>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Donors</h5>
                <h2 class="card-text"><?php echo $totalDonors; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total NGOs</h5>
                <h2 class="card-text"><?php echo $totalNGOs; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Total Donations</h5>
                <h2 class="card-text"><?php echo $totalDonations; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Requests</h5>
                <h2 class="card-text"><?php echo $totalRequests; ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pending NGO Verifications</h5>
                <a href="<?php echo BASE_URL; ?>/index.php?route=admin-ngo-verification" class="btn btn-sm btn-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($pendingNGOs)): ?>
                    <p class="text-muted mb-0">No pending NGO verifications</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach (array_slice($pendingNGOs, 0, 5) as $ngo): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo htmlspecialchars($ngo['organization_name']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($ngo['email']); ?></small>
                                </div>
                                <div>
                                    <a href="<?php echo BASE_URL; ?>/index.php?route=admin-verify-ngo&id=<?php echo $ngo['ngo_id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Verify this NGO?');">
                                        <i class="bi bi-check"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/index.php?route=admin-reject-ngo&id=<?php echo $ngo['ngo_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject this NGO?');">
                                        <i class="bi bi-x"></i>
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo BASE_URL; ?>/index.php?route=admin-ngo-verification" class="btn btn-primary">
                        <i class="bi bi-shield-check"></i> NGO Verification
                    </a>
                    <a href="<?php echo BASE_URL; ?>/index.php?route=admin-user-management" class="btn btn-secondary">
                        <i class="bi bi-people"></i> User Management
                    </a>
                    <a href="<?php echo BASE_URL; ?>/index.php?route=admin-reports" class="btn btn-info">
                        <i class="bi bi-graph-up"></i> Generate Reports
                    </a>
                    <a href="<?php echo BASE_URL; ?>/index.php?route=admin-donation-history" class="btn btn-warning">
                        <i class="bi bi-box-seam"></i> Donation History
                    </a>
                    <a href="<?php echo BASE_URL; ?>/index.php?route=admin-request-history" class="btn btn-success">
                        <i class="bi bi-envelope"></i> Request History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
