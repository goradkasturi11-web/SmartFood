<?php 
$pageTitle = 'Browse Food';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Browse Available Food</h2>
    <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-dashboard" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filter Donations</h5>
    </div>
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>/index.php?route=ngo-browse-food" method="GET">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="food_type" class="form-label">Food Type</label>
                    <select class="form-select" id="food_type" name="food_type">
                        <option value="">All Types</option>
                        <option value="cooked" <?php echo (isset($filters['food_type']) && $filters['food_type'] === 'cooked') ? 'selected' : ''; ?>>Cooked Food</option>
                        <option value="raw" <?php echo (isset($filters['food_type']) && $filters['food_type'] === 'raw') ? 'selected' : ''; ?>>Raw Ingredients</option>
                        <option value="packaged" <?php echo (isset($filters['food_type']) && $filters['food_type'] === 'packaged') ? 'selected' : ''; ?>>Packaged Food</option>
                        <option value="beverages" <?php echo (isset($filters['food_type']) && $filters['food_type'] === 'beverages') ? 'selected' : ''; ?>>Beverages</option>
                        <option value="bakery" <?php echo (isset($filters['food_type']) && $filters['food_type'] === 'bakery') ? 'selected' : ''; ?>>Bakery Items</option>
                        <option value="dairy" <?php echo (isset($filters['food_type']) && $filters['food_type'] === 'dairy') ? 'selected' : ''; ?>>Dairy Products</option>
                        <option value="fruits" <?php echo (isset($filters['food_type']) && $filters['food_type'] === 'fruits') ? 'selected' : ''; ?>>Fruits & Vegetables</option>
                        <option value="other" <?php echo (isset($filters['food_type']) && $filters['food_type'] === 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="min_quantity" class="form-label">Min Quantity</label>
                    <input type="number" step="0.01" class="form-control" id="min_quantity" name="min_quantity" 
                           value="<?php echo isset($filters['min_quantity']) ? htmlspecialchars($filters['min_quantity']) : ''; ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" 
                           value="<?php echo isset($filters['location']) ? htmlspecialchars($filters['location']) : ''; ?>" placeholder="City or area">
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (empty($donations)): ?>
    <div class="alert alert-info">
        No available donations found matching your criteria.
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($donations as $donation): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow">
                    <?php if ($donation['image_path']): ?>
                        <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($donation['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($donation['food_name']); ?>" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($donation['food_name']); ?></h5>
                        <p class="card-text">
                            <strong>Quantity:</strong> <?php echo htmlspecialchars($donation['quantity_value'] . ' ' . $donation['quantity_unit']); ?><br>
                            <strong>Type:</strong> <?php echo htmlspecialchars($donation['food_type']); ?><br>
                            <strong>Donor:</strong> <?php echo htmlspecialchars($donation['donor_name']); ?><br>
                            <strong>Pickup:</strong> <?php echo htmlspecialchars($donation['pickup_location']); ?><br>
                            <strong>Expires:</strong> <?php echo date('M d, Y H:i', strtotime($donation['expiry_time'])); ?>
                        </p>
                        <?php if ($donation['pending_requests'] > 0): ?>
                            <small class="text-muted"><?php echo $donation['pending_requests']; ?> pending request(s)</small>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-view-donation&id=<?php echo $donation['donation_id']; ?>" class="btn btn-success w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
