<?php 
$pageTitle = 'NGO Verification';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>NGO Verification Queue</h2>
    <a href="<?php echo BASE_URL; ?>/index.php?route=admin-dashboard" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
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

<?php if (empty($pendingNGOs)): ?>
    <div class="alert alert-info">
        No NGOs pending verification.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Organization Name</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Registration Number</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sr = 1;
                    foreach ($pendingNGOs as $ngo):
                ?>
                    <tr>
                        <td><?php echo $sr++; ?></td>
                        <td><?php echo htmlspecialchars($ngo['organization_name']); ?></td>
                        <td><?php echo htmlspecialchars($ngo['organization_name']); ?></td>
                        <td><?php echo htmlspecialchars($ngo['name']); ?></td>
                        <td><?php echo htmlspecialchars($ngo['email']); ?></td>
                        <td><?php echo htmlspecialchars($ngo['phone']); ?></td>
                        <td>
                            <?php
                                if (!empty($ngo['registration_number'])) {
                                    echo htmlspecialchars($ngo['registration_number']);
                                } else {
                                    echo '<span class="text-danger fw-bold">Not Available</span>';
                                }
                            ?>
                        </td>
                        <td title="<?php echo htmlspecialchars($ngo['address']); ?>">
                            <?php echo htmlspecialchars(strlen($ngo['address']) > 40 ? substr($ngo['address'], 0, 40) . '...' : $ngo['address']); ?>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/index.php?route=admin-verify-ngo&id=<?php echo $ngo['ngo_id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Are you sure to verify this NGO?');">
                                <i class="bi bi-check"></i> Verify
                            </a>
                            <a href="<?php echo BASE_URL; ?>/index.php?route=admin-reject-ngo&id=<?php echo $ngo['ngo_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to reject this NGO?');">
                                <i class="bi bi-x"></i> Reject
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
