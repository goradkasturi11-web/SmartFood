<?php 
$pageTitle = 'Reports';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Generate Reports</h2>
    <a href="<?php echo BASE_URL; ?>/index.php?route=admin-dashboard" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filter by Date Range</h5>
    </div>
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>/index.php?route=admin-reports" method="GET">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="<?php echo isset($startDate) ? htmlspecialchars($startDate) : ''; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="<?php echo isset($endDate) ? htmlspecialchars($endDate) : ''; ?>">
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Donations</h5>
                <h2 class="card-text"><?php echo $totalDonations; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Completed Donations</h5>
                <h2 class="card-text"><?php echo $completedDonations; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Total Requests</h5>
                <h2 class="card-text"><?php echo $totalRequests; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Approved Requests</h5>
                <h2 class="card-text"><?php echo $approvedRequests; ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">Recent Donations</h5>
            </div>
            <div class="card-body">
                <?php if (empty($donations)): ?>
                    <p class="text-muted mb-0">No donations found</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Food</th>
                                    <th>Donor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($donations, 0, 10) as $donation): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($donation['food_name']); ?></td>
                                        <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $donation['status'] === 'completed' ? 'success' : 'secondary'; ?>">
                                                <?php echo ucfirst($donation['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">Recent Requests</h5>
            </div>
            <div class="card-body">
                <?php if (empty($requests)): ?>
                    <p class="text-muted mb-0">No requests found</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Food</th>
                                    <th>NGO</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($requests, 0, 10) as $request): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($request['food_name']); ?></td>
                                        <td><?php echo htmlspecialchars($request['ngo_name']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $request['status'] === 'approved' ? 'success' : 'secondary'; ?>">
                                                <?php echo ucfirst($request['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
