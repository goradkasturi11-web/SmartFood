<?php 
$pageTitle = 'Donation Details';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="mb-4">
    <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-browse-food" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Browse
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h4 class="mb-0"><?php echo htmlspecialchars($donation['food_name']); ?></h4>
            </div>
            <div class="card-body">
                <?php if ($donation['image_path']): ?>
                    <div class="mb-3">
<<<<<<< HEAD
                        <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($donation['image_path']); ?>" class="img-fluid rounded" alt="Food image">
=======
                        <img src="<?php echo UPLOAD_URL; ?>/<?php echo htmlspecialchars($donation['image_path']); ?>" class="img-fluid rounded" alt="Food image">
>>>>>>> b55fcb823b30212af05b5f3f73817827df7b53ba
                    </div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Quantity:</strong> <?php echo htmlspecialchars($donation['quantity_value'] . ' ' . $donation['quantity_unit']); ?></p>
                        <p><strong>Food Type:</strong> <?php echo htmlspecialchars($donation['food_type']); ?></p>
                        <p><strong>Preparation Date:</strong> <?php echo date('M d, Y H:i', strtotime($donation['preparation_date'])); ?></p>
                        <p><strong>Expiry Time:</strong> <?php echo date('M d, Y H:i', strtotime($donation['expiry_time'])); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Donor:</strong> <?php echo htmlspecialchars($donation['donor_name']); ?></p>
                        <p><strong>Donor Phone:</strong> <?php echo htmlspecialchars($donation['donor_phone']); ?></p>
                        <p><strong>Pickup Location:</strong> <?php echo htmlspecialchars($donation['pickup_location']); ?></p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-success">Available</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-request-donation&id=<?php echo $donation['donation_id']; ?>" class="btn btn-success w-100 mb-2" onclick="return confirm('Request this donation?');">
                    <i class="bi bi-hand-thumbs-up"></i> Request Donation
                </a>
                <p class="text-muted small mb-0">
                    By requesting this donation, you agree to collect the food from the donor's location before the expiry time.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
