<?php 
$pageTitle = 'Edit Donation';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="mb-4">Edit Donation</h2>
                
                <?php if (isset($_SESSION['donation_errors'])): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($_SESSION['donation_errors'] as $error): ?>
                            <?php echo htmlspecialchars($error); ?><br>
                        <?php endforeach; ?>
                    </div>
                    <?php unset($_SESSION['donation_errors']); ?>
                <?php endif; ?>
                
                <form action="<?php echo BASE_URL; ?>/index.php?route=donor-edit-donation&id=<?php echo $donation['donation_id']; ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="food_name" class="form-label">Food Name</label>
                        <input type="text" class="form-control" id="food_name" name="food_name" 
                               value="<?php echo htmlspecialchars($donation['food_name']); ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity_value" class="form-label">Quantity</label>
                            <input type="number" step="0.01" class="form-control" id="quantity_value" name="quantity_value" 
                                   value="<?php echo htmlspecialchars($donation['quantity_value']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="quantity_unit" class="form-label">Unit</label>
                            <select class="form-select" id="quantity_unit" name="quantity_unit" required>
                                <option value="">Select unit</option>
                                <option value="kg" <?php echo $donation['quantity_unit'] === 'kg' ? 'selected' : ''; ?>>Kilograms (kg)</option>
                                <option value="lbs" <?php echo $donation['quantity_unit'] === 'lbs' ? 'selected' : ''; ?>>Pounds (lbs)</option>
                                <option value="liters" <?php echo $donation['quantity_unit'] === 'liters' ? 'selected' : ''; ?>>Liters</option>
                                <option value="pieces" <?php echo $donation['quantity_unit'] === 'pieces' ? 'selected' : ''; ?>>Pieces</option>
                                <option value="servings" <?php echo $donation['quantity_unit'] === 'servings' ? 'selected' : ''; ?>>Servings</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="food_type" class="form-label">Food Type</label>
                        <select class="form-select" id="food_type" name="food_type" required>
                            <option value="">Select type</option>
                            <option value="cooked" <?php echo $donation['food_type'] === 'cooked' ? 'selected' : ''; ?>>Cooked Food</option>
                            <option value="raw" <?php echo $donation['food_type'] === 'raw' ? 'selected' : ''; ?>>Raw Ingredients</option>
                            <option value="packaged" <?php echo $donation['food_type'] === 'packaged' ? 'selected' : ''; ?>>Packaged Food</option>
                            <option value="beverages" <?php echo $donation['food_type'] === 'beverages' ? 'selected' : ''; ?>>Beverages</option>
                            <option value="bakery" <?php echo $donation['food_type'] === 'bakery' ? 'selected' : ''; ?>>Bakery Items</option>
                            <option value="dairy" <?php echo $donation['food_type'] === 'dairy' ? 'selected' : ''; ?>>Dairy Products</option>
                            <option value="fruits" <?php echo $donation['food_type'] === 'fruits' ? 'selected' : ''; ?>>Fruits & Vegetables</option>
                            <option value="other" <?php echo $donation['food_type'] === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="preparation_date" class="form-label">Preparation Date</label>
                            <input type="datetime-local" class="form-control" id="preparation_date" name="preparation_date" 
                                   value="<?php echo date('Y-m-d\TH:i', strtotime($donation['preparation_date'])); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="expiry_time" class="form-label">Expiry Time</label>
                            <input type="datetime-local" class="form-control" id="expiry_time" name="expiry_time" 
                                   value="<?php echo date('Y-m-d\TH:i', strtotime($donation['expiry_time'])); ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="pickup_location" class="form-label">Pickup Location</label>
                        <textarea class="form-control" id="pickup_location" name="pickup_location" rows="3" required><?php echo htmlspecialchars($donation['pickup_location']); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitude (optional)</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" 
                                   value="<?php echo htmlspecialchars($donation['latitude'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitude (optional)</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" 
                                   value="<?php echo htmlspecialchars($donation['longitude'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Food Image (optional)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <?php if ($donation['image_path']): ?>
                            <div class="mt-2">
                                <small class="text-muted">Current image:</small><br>
                                <img src="<?php echo UPLOAD_URL; ?>/<?php echo htmlspecialchars($donation['image_path']); ?>" alt="Food image" style="max-width: 200px; max-height: 200px;">
                            </div>
                        <?php endif; ?>
                        <small class="text-muted">Maximum file size: 5MB. Allowed formats: JPG, PNG, GIF</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Update Donation</button>
                        <a href="<?php echo BASE_URL; ?>/index.php?route=donor-dashboard" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
