<?php 
$pageTitle = 'Maintenance Mode';
require_once __DIR__ . '/layouts/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <div class="card shadow">
            <div class="card-body p-5">
                <i class="bi bi-tools display-1 text-warning mb-4"></i>
                <h2 class="mb-3">Under Maintenance</h2>
                <p class="lead mb-4">We're currently performing scheduled maintenance. Please check back soon.</p>
                <p class="text-muted">We apologize for any inconvenience.</p>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <div class="alert alert-info mt-4">
                        <strong>Admin Access:</strong> You can still access the site. To disable maintenance mode, delete the <code>.maintenance</code> file from the project root.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
