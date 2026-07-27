<?php 
$pageTitle = 'Request History';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Request History</h2>
    <div>
        <form action="<?php echo BASE_URL; ?>/index.php" method="GET">

    <input type="hidden"
           name="route"
           value="admin-request-history">

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

<?php if (empty($requests)): ?>
    <div class="alert alert-info">
        No requests found.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Food Name</th>
                    <th>NGO</th>
                    <th>Donor</th>
                    <th>Request Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['request_id']); ?></td>
                        <td><?php echo htmlspecialchars($request['food_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['ngo_name']); ?></td>
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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
