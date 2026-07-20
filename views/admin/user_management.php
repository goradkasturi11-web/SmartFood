<?php 
$pageTitle = 'User Management';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>User Management</h2>
    <div>
        <form action="<?php echo BASE_URL; ?>/index.php?route=admin-user-management" method="GET" class="d-inline">
            <select name="role" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                <option value="">All Users</option>
                <option value="donor" <?php echo (isset($_GET['role']) && $_GET['role'] === 'donor') ? 'selected' : ''; ?>>Donors Only</option>
                <option value="ngo" <?php echo (isset($_GET['role']) && $_GET['role'] === 'ngo') ? 'selected' : ''; ?>>NGOs Only</option>
            </select>
        </form>
        <a href="<?php echo BASE_URL; ?>/index.php?route=admin-dashboard" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
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

<?php if (empty($users)): ?>
    <div class="alert alert-info">
        No users found.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Address</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $user['role'] === 'donor' ? 'primary' : ($user['role'] === 'ngo' ? 'success' : 'danger'); ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars(substr($user['address'], 0, 50)); ?>...</td>
                        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <?php if ($user['role'] !== 'admin'): ?>
                                <a href="<?php echo BASE_URL; ?>/index.php?route=admin-suspend-user&id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Suspend this user?');">
                                    <i class="bi bi-person-x"></i> Suspend
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
